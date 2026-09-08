<?php

namespace App\Services;

use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\ComparaisonPlanning;
use App\Models\HoraireType;
use App\Models\Employe;
use App\Models\Pointage;
use App\Repositories\PlanningRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlanningService
{
    protected $planningRepository;

    public function __construct(PlanningRepository $planningRepository)
    {
        $this->planningRepository = $planningRepository;
    }

    public function genererPlanning(
        int $siegeId,
        int $serviceId,
        int $posteId,
        array $employeIds,
        string $dateDebutSemaine,
        string $dateFinSemaine,
        array $joursTravail,
        int $creePar
    ): Planning {
        return DB::transaction(function () use (
            $siegeId, $serviceId, $posteId, $employeIds,
            $dateDebutSemaine, $dateFinSemaine, $joursTravail, $creePar
        ) {
            $service = \App\Models\Department::find($serviceId);
            $poste = \App\Models\JobTitle::find($posteId);

            $planning = Planning::create([
                'siege_id' => $siegeId,
                'service_id' => $serviceId,
                'poste_id' => $posteId,
                'date_debut_semaine' => $dateDebutSemaine,
                'date_fin_semaine' => $dateFinSemaine,
                'nom' => 'Planning ' . ($service?->name ?? '') . ' - ' . ($poste?->name ?? ''),
                'statut' => 'genere',
                'cree_par' => $creePar,
            ]);

            $horaires = HoraireType::byPoste($posteId)
                ->whereIn('jour_semaine', $joursTravail)
                ->get()
                ->keyBy('jour_semaine');

            $debut = Carbon::parse($dateDebutSemaine);
            $fin = Carbon::parse($dateFinSemaine);

            $jourMapping = [
                'lundi' => 'Monday',
                'mardi' => 'Tuesday',
                'mercredi' => 'Wednesday',
                'jeudi' => 'Thursday',
                'vendredi' => 'Friday',
                'samedi' => 'Saturday',
                'dimanche' => 'Sunday',
            ];

            foreach ($employeIds as $employeId) {
                $current = $debut->copy();
                while ($current <= $fin) {
                    $jourAnglais = $current->format('l');
                    $jourFrancais = array_search($jourAnglais, $jourMapping);

                    if (in_array($jourFrancais, $joursTravail) && isset($horaires[$jourFrancais])) {
                        $horaire = $horaires[$jourFrancais];
                        PlanningDetail::create([
                            'planning_id' => $planning->id,
                            'employe_id' => $employeId,
                            'date' => $current->format('Y-m-d'),
                            'heure_debut' => $horaire->heure_debut,
                            'heure_fin' => $horaire->heure_fin,
                            'pause_debut' => $horaire->pause_debut,
                            'pause_fin' => $horaire->pause_fin,
                            'deuxieme_debut' => $horaire->deuxieme_debut,
                            'deuxieme_fin' => $horaire->deuxieme_fin,
                            'statut' => 'planifie',
                        ]);
                    }
                    $current->addDay();
                }
            }

            return $planning;
        });
    }

    public function validerPlanning(int $planningId, int $validePar): Planning
    {
        $planning = Planning::findOrFail($planningId);
        $planning->statut = 'valide';
        $planning->valide_par = $validePar;
        $planning->valide_le = now();
        $planning->save();

        return $planning;
    }

    public function publierPlanning(int $planningId): Planning
    {
        $planning = Planning::findOrFail($planningId);
        $planning->statut = 'publie';
        $planning->publie_le = now();
        $planning->save();

        return $planning;
    }
}
