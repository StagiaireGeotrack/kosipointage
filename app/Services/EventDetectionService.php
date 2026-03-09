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
     * Détecte toutes les erreurs non résolues pour un siège.
     */
    public function detect(int $siegeId): Collection
    {
        return collect()
            ->merge($this->detectDoublonsEntree($siegeId))
            ->merge($this->detectDoublonsSortie($siegeId))
            ->merge($this->detectManqueSortie($siegeId))
            ->merge($this->detectManqueEntree($siegeId))
            ->merge($this->detectJourFerie($siegeId))
            ->merge($this->detectWeekend($siegeId))
            ->sortBy('date');
    }

    /**
     * Compte les erreurs non résolues (utilisé pour bloquer les exports).
     */
    public function countUnresolved(int $siegeId): int
    {
        return $this->detect($siegeId)->count();
    }

    // ─── DOUBLON ENTRÉE ───────────────────────────────────────────────────────

    private function detectDoublonsEntree(int $siegeId): Collection
    {
        $rows = DB::table('Pointages')
            ->where('SiegeID', $siegeId)
            ->where('type_', 'entry')
            ->select('employee_id', DB::raw("DATE(timestamp_) as date"), DB::raw('COUNT(*) as cnt'))
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->having('cnt', '>', 1)
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'doublon_entree', 'Doublon d\'entrée', 'danger', 'bi-arrow-down-circle-fill',
            $row->employee_id, $row->date, $siegeId, 'entry'
        ));
    }

    // ─── DOUBLON SORTIE ───────────────────────────────────────────────────────

    private function detectDoublonsSortie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages')
            ->where('SiegeID', $siegeId)
            ->where('type_', 'exit')
            ->select('employee_id', DB::raw("DATE(timestamp_) as date"), DB::raw('COUNT(*) as cnt'))
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->having('cnt', '>', 1)
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'doublon_sortie', 'Doublon de sortie', 'warning', 'bi-arrow-up-circle-fill',
            $row->employee_id, $row->date, $siegeId, 'exit'
        ));
    }

    // ─── MANQUE SORTIE ────────────────────────────────────────────────────────

    private function detectManqueSortie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->where('p.type_', 'entry')
            ->whereDate('p.timestamp_', '<', Carbon::today())
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) use ($siegeId) {
                $q->from('Pointages as p2')
                  ->whereColumn('p2.employee_id', 'p.employee_id')
                  ->whereRaw('DATE(p2.timestamp_) = DATE(p.timestamp_)')
                  ->where('p2.type_', 'exit')
                  ->where('p2.SiegeID', $siegeId);
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'manque_sortie', 'Manque de sortie', 'warning', 'bi-box-arrow-right',
            $row->employee_id, $row->date, $siegeId, null, 'exit'
        ));
    }

    // ─── MANQUE ENTRÉE ────────────────────────────────────────────────────────

    private function detectManqueEntree(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->where('p.type_', 'exit')
            ->whereDate('p.timestamp_', '<', Carbon::today())
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) use ($siegeId) {
                $q->from('Pointages as p2')
                  ->whereColumn('p2.employee_id', 'p.employee_id')
                  ->whereRaw('DATE(p2.timestamp_) = DATE(p.timestamp_)')
                  ->where('p2.type_', 'entry')
                  ->where('p2.SiegeID', $siegeId);
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'manque_entree', 'Manque d\'entrée', 'info', 'bi-box-arrow-in-right',
            $row->employee_id, $row->date, $siegeId, null, 'entry'
        ));
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
            $row->employee_id, $row->date, $siegeId, null, null, "Jour : {$row->jour_nom}"
        ));
    }

    // ─── WEEKEND ──────────────────────────────────────────────────────────────

    private function detectWeekend(int $siegeId): Collection
    {
        // DAYOFWEEK MySQL : 1=Dimanche, 7=Samedi
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
            $date = Carbon::parse($row->date);
            $jourSemaine = $date->dayOfWeek === 0 ? 'Dimanche' : 'Samedi';
            return $this->buildError(
                'pointage_weekend', 'Pointage weekend', 'secondary', 'bi-calendar2-week-fill',
                $row->employee_id, $row->date, $siegeId, null, null, $jourSemaine
            );
        });
    }

    // ─── BUILDER ──────────────────────────────────────────────────────────────

    private function buildError(
        string  $type,
        string  $label,
        string  $color,
        string  $icon,
        int     $employeeId,
        string  $date,
        int     $siegeId,
        ?string $filterType = null,  // filtre type_ pour les pointages affichés
        ?string $manqueType = null,  // type à ajouter si manque (entry/exit)
        ?string $extra = null
    ): object {
        $employe = Employe::withoutGlobalScope(SiegeScope::class)->find($employeeId);

        $query = DB::table('Pointages')
            ->where('employee_id', $employeeId)
            ->where('SiegeID', $siegeId)
            ->whereDate('timestamp_', $date)
            ->orderBy('timestamp_');

        if ($filterType) {
            $query->where('type_', $filterType);
        }

        $pointages = $query->get();

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
