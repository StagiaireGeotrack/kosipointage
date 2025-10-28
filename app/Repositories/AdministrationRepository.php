<?php
// app/Repositories/AdministrationRepository.php

namespace App\Repositories;

use App\Models\Administration;
use Illuminate\Support\Facades\Gate;

class AdministrationRepository extends BaseRepository
{
    public function __construct(Administration $administration)
    {
        parent::__construct($administration);
    }
    
    public function getFiltered($filters = [], $perPage = 5)
    {
        $query = $this->model->newQuery();
        
        // Filtre par recherche (email)
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('Identifiant_email', 'LIKE', "%{$filters['search']}%");
        }
        
        // Filtre par siège
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        // Filtre par type d'administrateur
        if (isset($filters['IsSuperAdmin']) && $filters['IsSuperAdmin'] !== '') {
            $query->where('IsSuperAdmin', $filters['IsSuperAdmin']);
        }
        
        // Tri
        $sortBy = $filters['sort_by'] ?? 'Identifiant_email';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Inclure la relation siège
        $query->with('siege');
        
        return $query->paginate($perPage);
    }
    
    public function getAllForExport($filters = [])
    {
        $query = $this->model->newQuery();
        
        // Appliquer les mêmes filtres que pour getFiltered
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('Identifiant_email', 'LIKE', "%{$filters['search']}%");
        }
        
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        if (isset($filters['IsSuperAdmin']) && $filters['IsSuperAdmin'] !== '') {
            $query->where('IsSuperAdmin', $filters['IsSuperAdmin']);
        }
        
        // Inclure la relation siège
        $query->with('siege');
        
        // Sélectionner et formater les données pour l'export
        return $query->get()->map(function ($admin) {
            return [
                'ID' => $admin->ID,
                'Email' => $admin->Identifiant_email,
                'Type' => $admin->IsSuperAdmin ? __('app.super_admin') : __('app.standard_admin'),
                'Siege' => $admin->SiegeID ? $admin->siege->Nom : __('app.not_applicable'),
                'Créé le' => $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '',
                'Dernière mise à jour' => $admin->updated_at ? $admin->updated_at->format('d/m/Y H:i') : '',
            ];
        });
    }
}