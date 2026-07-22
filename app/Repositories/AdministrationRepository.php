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
        if (isset($filters['role']) && $filters['role'] !== '') {
            switch ($filters['role']) {
                case 'super_admin':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 0)->where('IsManager', 0);
                    break;
                case 'manager_super_admin':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 0)->where('IsManager', 1);
                    break;
                case 'seller':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 1)->where('IsManager', 0);
                    break;
                case 'manager_seller':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 1)->where('IsManager', 1);
                    break;
                case 'simple_admin':
                    $query->where('IsSuperAdmin', 0)->where('IsManager', 0);
                    break;
                case 'manager_simple_admin':
                    $query->where('IsSuperAdmin', 0)->where('IsManager', 1);
                    break;
            }
        } elseif (isset($filters['IsSuperAdmin']) && $filters['IsSuperAdmin'] !== '') {
            $query->where('IsSuperAdmin', $filters['IsSuperAdmin']);
        }
        
        $query->orderBy("created_at", "desc");
        
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
        
        if (isset($filters['role']) && $filters['role'] !== '') {
            switch ($filters['role']) {
                case 'super_admin':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 0)->where('IsManager', 0);
                    break;
                case 'manager_super_admin':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 0)->where('IsManager', 1);
                    break;
                case 'seller':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 1)->where('IsManager', 0);
                    break;
                case 'manager_seller':
                    $query->where('IsSuperAdmin', 1)->where('IsSeller', 1)->where('IsManager', 1);
                    break;
                case 'simple_admin':
                    $query->where('IsSuperAdmin', 0)->where('IsManager', 0);
                    break;
                case 'manager_simple_admin':
                    $query->where('IsSuperAdmin', 0)->where('IsManager', 1);
                    break;
            }
        } elseif (isset($filters['IsSuperAdmin']) && $filters['IsSuperAdmin'] !== '') {
            $query->where('IsSuperAdmin', $filters['IsSuperAdmin']);
        }
        
        // Inclure la relation siège
        $query->with('siege');
        
        // Sélectionner et formater les données pour l'export
        return $query->orderBy('created_at', 'asc')->get()->map(function ($admin) {
            $type = __('Administrateur simple');
            if ($admin->isTrueSuperAdmin() && !$admin->isManagerSuperAdmin()) $type = __('Super Administrateur');
            elseif ($admin->isManagerSuperAdmin()) $type = __('Manager Super Administrateur');
            elseif ($admin->isManagerSeller()) $type = __('Manager Vendeur');
            elseif ($admin->isSeller()) $type = __('Vendeur');
            elseif ($admin->isManagerSimpleAdmin()) $type = __('Manager Administrateur simple');

            return [
                'ID' => $admin->ID,
                'E-mail' => $admin->Identifiant_email,
                'Type' => $type,
                'Siège' => $admin->SiegeID ? $admin->siege->Nom : __(''),
                'Date de création' => ucfirst($admin->created_at ? $admin->created_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
                'Dernière mise à jour' => ucfirst($admin->updated_at ? $admin->updated_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
            ];
        });
    }
}