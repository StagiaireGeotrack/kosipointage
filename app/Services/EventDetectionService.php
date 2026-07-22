<?php
// app/Services/EventDetectionService.php

namespace App\Services;

use App\Models\Employe;
use App\Scopes\SiegeScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventDetectionService
{
    /**
     * Séquence normale attendue pour une journée complète :
     *   entry → exit → entry → exit  (matin + après-midi)
     * ou pour une demi-journée :
     *   entry → exit
     *
     * Une erreur = tout écart par rapport à ce pattern alternant.
     */

    public function detect(int $siegeId): Collection
    {
        $errors = collect();

        // ── Récupérer toutes les combinaisons employé+jour avec leur séquence ──
        // Une seule requête SQL : plus efficace que N requêtes individuelles
        $allDays = DB::table('Pointages')
            ->where('SiegeID', $siegeId)
            ->select(
                'employee_id',
                DB::raw("DATE(timestamp_) as date"),
                DB::raw("GROUP_CONCAT(type_ ORDER BY timestamp_ SEPARATOR ',') as sequence"),
                DB::raw("GROUP_CONCAT(timestamp_ ORDER BY timestamp_ SEPARATOR ',') as timestamps"),
                DB::raw("SUM(CASE WHEN type_ = 'entry' THEN 1 ELSE 0 END) as entry_count"),
                DB::raw("SUM(CASE WHEN type_ = 'exit'  THEN 1 ELSE 0 END) as exit_count")
            )
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->get();

        foreach ($allDays as $day) {
            $types      = explode(',', $day->sequence);
            $timestamps = explode(',', $day->timestamps);

            // ── Doublon : 2 types identiques consécutifs ET écart ≤ 30 minutes ──
            $hasDoublonEntree = false;
            $hasDoublonSortie = false;

            for ($i = 1; $i < count($types); $i++) {
                if ($types[$i] === $types[$i - 1]) {
                    $t1      = Carbon::parse($timestamps[$i - 1]);
                    $t2      = Carbon::parse($timestamps[$i]);
                    $minutes = $t1->diffInMinutes($t2);

                    if ($minutes <= 30) {
                        if ($types[$i] === 'entry') {
                            $hasDoublonEntree = true;
                        } else {
                            $hasDoublonSortie = true;
                        }
                    }
                }
            }

            if ($hasDoublonEntree) {
                $errors->push($this->buildError(
                    'doublon_entree', 'Doublon d\'entrée', 'danger', 'bi-arrow-down-circle-fill',
                    $day->employee_id, $day->date, $siegeId
                ));
            }

            if ($hasDoublonSortie) {
                $errors->push($this->buildError(
                    'doublon_sortie', 'Doublon de sortie', 'warning', 'bi-arrow-up-circle-fill',
                    $day->employee_id, $day->date, $siegeId
                ));
            }

            // ── Manque de sortie ──
            // Plus d'entrées que de sorties → au moins une sortie manque
            if ($day->entry_count > $day->exit_count) {
                $errors->push($this->buildError(
                    'manque_sortie', 'Manque de sortie', 'warning', 'bi-box-arrow-right',
                    $day->employee_id, $day->date, $siegeId,
                    manqueType: 'exit'
                ));
            }

            // ── Manque d'entrée ──
            // Plus de sorties que d'entrées → au moins une entrée manque
            if ($day->exit_count > $day->entry_count) {
                $errors->push($this->buildError(
                    'manque_entree', 'Manque d\'entrée', 'info', 'bi-box-arrow-in-right',
                    $day->employee_id, $day->date, $siegeId,
                    manqueType: 'entry'
                ));
            }
        }

        // ── Erreurs contextuelles (acknowledgeable) ──
        $errors = $errors
            ->merge($this->detectJourFerie($siegeId))
            ->merge($this->detectWeekend($siegeId));

        return $errors->sortByDesc('date');
    }

    /**
     * Compte les erreurs non résolues — utilisé pour bloquer les exports.
     */
    public function countUnresolved(int $siegeId): int
    {
        return $this->detect($siegeId)->count();
    }

    /**
     * Compte les erreurs non résolues DANS UNE PLAGE DE DATES.
     * Réplique la logique de detect() mais filtrée et optimisée (sans buildError).
     * Utilisé pour le blocage d'export par période.
     * Si siegeId est null → vérifie TOUS les sièges (SuperAdmin sans filtre).
     */
    public function countUnresolvedInRange(?int $siegeId, string $dateFrom, string $dateTo): int
    {
        $count = 0;

        // ── 1. Doublons & asymétries (même logique que detect()) ──
        $allDays = DB::table('Pointages')
            ->when($siegeId, fn($q) => $q->where('SiegeID', $siegeId))
            ->whereBetween(DB::raw('DATE(timestamp_)'), [$dateFrom, $dateTo])
            ->select(
                'employee_id',
                DB::raw("DATE(timestamp_) as date"),
                DB::raw("GROUP_CONCAT(type_ ORDER BY timestamp_ SEPARATOR ',') as sequence"),
                DB::raw("GROUP_CONCAT(timestamp_ ORDER BY timestamp_ SEPARATOR ',') as timestamps"),
                DB::raw("SUM(CASE WHEN type_ = 'entry' THEN 1 ELSE 0 END) as entry_count"),
                DB::raw("SUM(CASE WHEN type_ = 'exit'  THEN 1 ELSE 0 END) as exit_count")
            )
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->get();

        foreach ($allDays as $day) {
            $types      = explode(',', $day->sequence);
            $timestamps = explode(',', $day->timestamps);

            $hasDoublonEntree = false;
            $hasDoublonSortie = false;

            for ($i = 1; $i < count($types); $i++) {
                if ($types[$i] === $types[$i - 1]) {
                    $t1 = Carbon::parse($timestamps[$i - 1]);
                    $t2 = Carbon::parse($timestamps[$i]);
                    if ($t1->diffInMinutes($t2) <= 30) {
                        if ($types[$i] === 'entry') $hasDoublonEntree = true;
                        else $hasDoublonSortie = true;
                    }
                }
            }

            if ($hasDoublonEntree)                        $count++; // doublon_entree
            if ($hasDoublonSortie)                        $count++; // doublon_sortie
            if ($day->entry_count > $day->exit_count)     $count++; // manque_sortie
            if ($day->exit_count  > $day->entry_count)    $count++; // manque_entree
        }

        // ── 2. Jours fériés dans la plage (non acquittés) ──
        $count += DB::table('Pointages as p')
            ->join('jours_non_travailles as j', function ($join) use ($siegeId) {
                $join->whereRaw('DATE(p.timestamp_) = j.Date')
                     ->when($siegeId, fn($j2) => $j2->where('j.SiegeID', $siegeId))
                     ->where('j.Actived', 1);
            })
            ->when($siegeId, fn($q) => $q->where('p.SiegeID', $siegeId))
            ->whereBetween(DB::raw('DATE(p.timestamp_)'), [$dateFrom, $dateTo])
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_jour_ferie');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'), 'j.Nom')
            ->count();

        // ── 3. Weekends dans la plage (non acquittés) ──
        $count += DB::table('Pointages as p')
            ->when($siegeId, fn($q) => $q->where('p.SiegeID', $siegeId))
            ->whereRaw('DAYOFWEEK(p.timestamp_) IN (1, 7)')
            ->whereBetween(DB::raw('DATE(p.timestamp_)'), [$dateFrom, $dateTo])
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_weekend');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->count();

        return $count;
    }

    // ─── JOUR FÉRIÉ ───────────────────────────────────────────────────────────

    private function detectJourFerie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->join('jours_non_travailles as j', function ($join) use ($siegeId) {
                $join->whereRaw('DATE(p.timestamp_) = j.Date')
                     ->where('j.SiegeID', $siegeId)
                     ->where('j.Actived', 1);
            })
            ->where('p.SiegeID', $siegeId)
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"), 'j.Nom as jour_nom')
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_jour_ferie');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'), 'j.Nom')
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'pointage_jour_ferie', 'Pointage jour férié', 'secondary', 'bi-calendar-x-fill',
            $row->employee_id, $row->date, $siegeId,
            extra: "Jour : {$row->jour_nom}"
        ));
    }

    // ─── WEEKEND ──────────────────────────────────────────────────────────────

    private function detectWeekend(int $siegeId): Collection
    {
        // DAYOFWEEK MySQL : 1 = Dimanche, 7 = Samedi
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->whereRaw('DAYOFWEEK(p.timestamp_) IN (1, 7)')
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_weekend');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(function ($row) use ($siegeId) {
            $date        = Carbon::parse($row->date);
            $jourSemaine = $date->dayOfWeek === 0 ? 'Dimanche' : 'Samedi';

            return $this->buildError(
                'pointage_weekend', 'Pointage weekend', 'secondary', 'bi-calendar2-week-fill',
                $row->employee_id, $row->date, $siegeId,
                extra: $jourSemaine
            );
        });
    }

    // ─── BUILDER ──────────────────────────────────────────────────────────────

    /**
     * Construit un objet erreur avec TOUS les pointages du jour.
     * Chaque pointage est enrichi d'un flag `is_duplicate` (vrai si consécutif identique).
     */
    private function buildError(
        string  $type,
        string  $label,
        string  $color,
        string  $icon,
        int     $employeeId,
        string  $date,
        int     $siegeId,
        ?string $manqueType = null,  // 'entry' ou 'exit' à ajouter si manque
        ?string $extra      = null
    ): object {
        $employe = Employe::withoutGlobalScope(SiegeScope::class)->find($employeeId);

        // Charger TOUS les pointages du jour dans l'ordre chronologique
        $rawPointages = DB::table('Pointages')
            ->where('employee_id', $employeeId)
            ->where('SiegeID', $siegeId)
            ->whereDate('timestamp_', $date)
            ->orderBy('timestamp_')
            ->get();

        // Marquer les doublons consécutifs ≤ 30 min (pour les surligner dans la vue)
        $pointages    = collect();
        $prevType     = null;
        $prevPointage = null;

        foreach ($rawPointages as $p) {
            $isDuplicate = false;

            if ($prevType !== null && $p->type_ === $prevType) {
                $t1          = Carbon::parse($prevPointage->timestamp_);
                $t2          = Carbon::parse($p->timestamp_);
                $isDuplicate = $t1->diffInMinutes($t2) <= 30;
            }

            $pointages->push((object) array_merge((array) $p, ['is_duplicate' => $isDuplicate]));
            $prevType     = $p->type_;
            $prevPointage = $p;
        }

        return (object) [
            'type'       => $type,
            'label'      => $label,
            'color'      => $color,
            'icon'       => $icon,
            'employee'   => $employe,
            'date'       => Carbon::parse($date),
            'pointages'  => $pointages,
            'manqueType' => $manqueType,
            'extra'      => $extra,
            'key'        => "{$type}_{$employeeId}_{$date}",
        ];
    }
}
