<?php
// app/Repositories/EntrepriseSiegeRepository.php

namespace App\Repositories;

use App\Models\EntrepriseSiege;

class EntrepriseSiegeRepository extends BaseRepository
{
    public function __construct(EntrepriseSiege $siege)
    {
        parent::__construct($siege);
    }
    
    public function getFiltered($filters = [], $perPage = 5)
    {
        $query = $this->model->newQuery();
        
        // Appliquer le filtre de recherche
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('Nom_Lieu_Ville', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        // Filtre par statut actif/inactif
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        // Appliquer le tri
        $sortBy = $filters['sort_by'] ?? 'ID';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        return $query->paginate($perPage);
    }
    
    public function getAllForExport($filters = [])
    {
        $query = $this->model->newQuery();
        
        // Appliquer les filtres
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('Nom_Lieu_Ville', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        // Formater les données pour l'export
        return $query->orderBy('CreatedAt', 'asc')->get()->map(function ($siege) {
            $entreprisesCount = $siege->entreprises()->count();
            $employesCount = $siege->employes()->count();
            
            return [
                'ID' => $siege->ID,
                'Nom' => $siege->Nom,
                'Nom Lieu ou Ville' => $siege->Nom_Lieu_Ville,
                'Statut' => $siege->Actived ? __('Oui') : __('Non'),
                'Date de création' => ucfirst($siege->CreatedAt->isoFormat('dddd D MMMM YYYY - HH:mm:ss')),
                'Nb sites ou établissements' => $entreprisesCount,
                'Nb employés' => $employesCount,
            ];
        });
    }
}