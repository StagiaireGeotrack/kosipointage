<?php

namespace App\Repositories;

use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\HoraireType;
use App\Models\ComparaisonPlanning;
use App\Models\EvenementPlanning;
use Carbon\Carbon;

class PlanningRepository extends BaseRepository
{
    public function __construct(Planning $planning)
    {
        parent::__construct($planning);
    }

    public function getFiltered($filters = [], $perPage = 10)
    {
        $query = $this->model->newQuery();

        if (isset($filters['siege_id']) && !empty($filters['siege_id'])) {
            $query->where('siege_id', $filters['siege_id']);
        }

        if (isset($filters['service_id']) && !empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        if (isset($filters['poste_id']) && !empty($filters['poste_id'])) {
            $query->where('poste_id', $filters['poste_id']);
        }

        if (isset($filters['statut']) && !empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['semaine_debut']) && !empty($filters['semaine_debut'])) {
            $query->where('date_debut_semaine', '>=', $filters['semaine_debut']);
        }
        if (isset($filters['semaine_fin']) && !empty($filters['semaine_fin'])) {
            $query->where('date_fin_semaine', '<=', $filters['semaine_fin']);
        }

        if (isset($filters['semaine_courante']) && $filters['semaine_courante']) {
            $debut = Carbon::now()->startOfWeek();
            $fin = Carbon::now()->endOfWeek();
            $query->where('date_debut_semaine', $debut->format('Y-m-d'))
                  ->where('date_fin_semaine', $fin->format('Y-m-d'));
        }

        if (isset($filters['employee_ids']) && is_array($filters['employee_ids'])) {
            if (empty($filters['employee_ids'])) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('employee_id', $filters['employee_ids']);
            }
        }

        $sortBy = $filters['tri_par'] ?? 'date_debut_semaine';
        $sortOrder = $filters['ordre_tri'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $query->with(['siege', 'service', 'poste', 'createur']);

        return $query->paginate($perPage);
    }

    public function getPlanningsEmploye($employeId, $dateDebut = null, $dateFin = null)
    {
        $query = PlanningDetail::where('employe_id', $employeId)
            ->with(['planning', 'planning.siege', 'planning.service']);

        if ($dateDebut) {
            $query->where('date', '>=', $dateDebut);
        }
        if ($dateFin) {
            $query->where('date', '<=', $dateFin);
        }

        return $query->orderBy('date')->get();
    }

    public function getPlanningsService($serviceId, $dateDebut = null, $dateFin = null)
    {
        $query = $this->model->where('service_id', $serviceId);

        if ($dateDebut) {
            $query->where('date_debut_semaine', '>=', $dateDebut);
        }
        if ($dateFin) {
            $query->where('date_fin_semaine', '<=', $dateFin);
        }

        return $query->with(['details.employe'])->get();
    }

    public function getHorairesTypes($posteId)
    {
        return HoraireType::where('poste_id', $posteId)->get();
    }

    public function getComparaisons($planningId)
    {
        return ComparaisonPlanning::whereHas('planningDetail', function ($q) use ($planningId) {
            $q->where('planning_id', $planningId);
        })
        ->with(['planningDetail.employe', 'pointage'])
        ->get();
    }

    public function getEvenementsCalendrier($siegeId, $dateDebut, $dateFin, $serviceId = null, $posteId = null)
    {
        $query = EvenementPlanning::where('siege_id', $siegeId)
            ->where(function ($q) use ($dateDebut, $dateFin) {
                $q->whereBetween('debut', [$dateDebut, $dateFin])
                  ->orWhereBetween('fin', [$dateDebut, $dateFin])
                  ->orWhere(function ($q2) use ($dateDebut, $dateFin) {
                      $q2->where('debut', '<=', $dateDebut)
                         ->where('fin', '>=', $dateFin);
                  });
            });

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }
        if ($posteId) {
            $query->where('poste_id', $posteId);
        }

        return $query->with('employes')->get();
    }
}
