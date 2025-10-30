<?php
// app/Repositories/EmployeRepository.php

namespace App\Repositories;

use App\Models\Employe;

class EmployeRepository extends BaseRepository
{
    public function __construct(Employe $employe)
    {
        parent::__construct($employe);
    }
    
    public function getFiltered($filters = [], $perPage = 5)
    {
        $query = $this->model->newQuery();
        
        // Filtre par recherche (nom ou badgeID)
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('BadgeID', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        // Filtre par siège
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        // Filtre par statut actif/inactif
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        // Filtre par configuration biométrique
        if (isset($filters['HasBiometricSetup']) && $filters['HasBiometricSetup'] !== '') {
            $query->where('HasBiometricSetup', $filters['HasBiometricSetup']);
        }
        
        // Filtre par configuration faciale
        if (isset($filters['HasFaceSetup']) && $filters['HasFaceSetup'] !== '') {
            $query->where('HasFaceSetup', $filters['HasFaceSetup']);
        }
        
        // Tri
        $sortBy = $filters['sort_by'] ?? 'Nom';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Inclure les relations
        $query->with('siege');
        
        return $query->paginate($perPage);
    }
    
    public function getAllForExport($filters = [])
    {
        $query = $this->model->newQuery();
        
        // Appliquer les mêmes filtres que pour getFiltered
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('BadgeID', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        if (isset($filters['HasBiometricSetup']) && $filters['HasBiometricSetup'] !== '') {
            $query->where('HasBiometricSetup', $filters['HasBiometricSetup']);
        }
        
        if (isset($filters['HasFaceSetup']) && $filters['HasFaceSetup'] !== '') {
            $query->where('HasFaceSetup', $filters['HasFaceSetup']);
        }
        
        // Inclure les relations nécessaires
        $query->with('siege');
        
        // Sélectionner et formater les données pour l'export
        return $query->get()->map(function ($employe) {
            return [
                'ID' => $employe->ID,
                'Nom' => $employe->Nom,
                'BadgeID' => $employe->BadgeID,
                'HasBiometricSetup' => $employe->HasBiometricSetup ? __('Oui') : __('Non'),
                'HasFaceSetup' => $employe->HasFaceSetup ? __('Oui') : __('Non'),
                'CreatedAt' => $employe->CreatedAt->format('d/m/Y H:i'),
                'Actived' => $employe->Actived ? __('Oui') : __('Non'),
                'Siege' => $employe->siege->Nom,
            ];
        });
    }
    
    public function getLatestPointages($employeId, $limit = 10)
    {
        return $this->model->findOrFail($employeId)
            ->pointages()
            ->with('employe')
            ->orderBy('timestamp_', 'desc')
            ->limit($limit)
            ->get();
    }
}