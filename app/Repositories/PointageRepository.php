<?php
// app/Repositories/PointageRepository.php

namespace App\Repositories;

use App\Models\Pointage;
use Carbon\Carbon;

class PointageRepository extends BaseRepository
{
    public function __construct(Pointage $pointage)
    {
        parent::__construct($pointage);
    }
    
    public function getFiltered($filters = [], $perPage = 5)
    {
        $query = $this->model->newQuery();
        
        // Filtre par recherche (employé)
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->whereHas('employe', function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('BadgeID', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        // Filtre par employé
        if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        // Filtre par siège
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        // Filtre par type (entrée/sortie)
        if (isset($filters['type_']) && !empty($filters['type_'])) {
            $query->where('type_', $filters['type_']);
        }
        
        // Filtre par méthode d'authentification
        if (isset($filters['auth_method']) && !empty($filters['auth_method'])) {
            $query->where('auth_method', $filters['auth_method']);
        }
        
        // Filtre par plage de dates
        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $query->where('timestamp_', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }
        
        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $query->where('timestamp_', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }
        
        // Tri
        $sortBy = $filters['sort_by'] ?? 'timestamp_';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Inclure les relations
        $query->with(['employe', 'siege']);
        
        return $query->paginate($perPage);
    }
    
    public function getAllForExport($filters = [])
    {
        $query = $this->model->newQuery();
        
        // Appliquer les filtres comme dans getFiltered
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->whereHas('employe', function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('BadgeID', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        if (isset($filters['employee_id']) && !empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        if (isset($filters['type_']) && !empty($filters['type_'])) {
            $query->where('type_', $filters['type_']);
        }
        
        if (isset($filters['auth_method']) && !empty($filters['auth_method'])) {
            $query->where('auth_method', $filters['auth_method']);
        }
        
        if (isset($filters['date_from']) && !empty($filters['date_from'])) {
            $query->where('timestamp_', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }
        
        if (isset($filters['date_to']) && !empty($filters['date_to'])) {
            $query->where('timestamp_', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }
        
        // Inclure les relations nécessaires
        $query->with(['employe', 'siege']);
        
        // Sélectionner et formater les données pour l'export
        return $query->get()->map(function ($pointage) {
            return [
                'ID' => $pointage->ID,
                'Employe' => $pointage->employe->Nom,
                'BadgeID' => $pointage->employe->BadgeID,
                'Type' => $pointage->type_ === 'entry' ? __('app.entry') : __('app.exit'),
                'Methode' => $this->formatAuthMethod($pointage->auth_method),
                'Date' => $pointage->timestamp_->format('d/m/Y'),
                'Heure' => $pointage->timestamp_->format('H:i'),
                'Siege' => $pointage->siege->Nom,
                'Latitude' => $pointage->latitude,
                'Longitude' => $pointage->longitude,
                'Synced' => $pointage->synced ? __('app.yes') : __('app.no'),
            ];
        });
    }
    
    protected function formatAuthMethod($method)
    {
        switch ($method) {
            case 'badge': return __('app.badge');
            case 'face': return __('app.face_recognition');
            case 'pin': return __('app.pin_code');
            case 'admin': return __('app.admin_manual');
            default: return $method;
        }
    }
}