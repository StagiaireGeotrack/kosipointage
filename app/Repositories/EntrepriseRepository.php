<?php
// app/Repositories/EntrepriseRepository.php

namespace App\Repositories;

use App\Models\Entreprise;
use Carbon\Carbon;

class EntrepriseRepository extends BaseRepository
{
    public function __construct(Entreprise $entreprise)
    {
        parent::__construct($entreprise);
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
        
        // Filtre par siège
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        // Filtre par statut actif/inactif
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        // Filtre par date de création (plage)
        if (isset($filters['created_from']) && !empty($filters['created_from'])) {
            $query->where('CreatedAt', '>=', Carbon::parse($filters['created_from'])->startOfDay());
        }
        
        if (isset($filters['created_to']) && !empty($filters['created_to'])) {
            $query->where('CreatedAt', '<=', Carbon::parse($filters['created_to'])->endOfDay());
        }
        
        // Appliquer le tri
        $sortBy = $filters['sort_by'] ?? 'ID';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        // Charger les relations pour optimiser les performances
        $query->with('siege');
        
        return $query->paginate($perPage);
    }
    
    public function getAllForExport($filters = [])
    {
        // Similaire à getFiltered, mais sans pagination
        $query = $this->model->newQuery();
        
        // Appliquer les filtres comme dans getFiltered
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('Nom', 'LIKE', "%{$filters['search']}%")
                  ->orWhere('Nom_Lieu_Ville', 'LIKE', "%{$filters['search']}%");
            });
        }
        
        if (isset($filters['SiegeID']) && !empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        
        if (isset($filters['Actived']) && $filters['Actived'] !== '') {
            $query->where('Actived', $filters['Actived']);
        }
        
        // Charger les relations nécessaires
        $query->with('siege');
        
        // Sélectionner et formater les données pour l'export
        return $query->orderBy('CreatedAt', 'asc')->get()->map(function ($entreprise) {
            return [
                'ID' => $entreprise->ID,
                'Nom' => $entreprise->Nom,
                'Nom Lieu ou Ville' => $entreprise->Nom_Lieu_Ville,
                'Latitude' => $entreprise->Latitude,
                'Longitude' => $entreprise->Longitude,
                'Rayon' => $entreprise->RadiusInMeters,
                'Date de création' => ucfirst($entreprise->CreatedAt->isoFormat('dddd D MMMM YYYY - HH:mm:ss')),
                'Statut' => $entreprise->Actived ? __('Oui') : __('Non'),
                'Siège' => $entreprise->siege->Nom,
            ];
        });
    }
    
    public function getStatistics()
    {
        // Exemple de méthode pour obtenir des statistiques
        $stats = [
            'total' => $this->model->count(),
            'active' => $this->model->where('Actived', 1)->count(),
            'inactive' => $this->model->where('Actived', 0)->count(),
            'by_siege' => $this->model->selectRaw('SiegeID, COUNT(*) as count')
                ->groupBy('SiegeID')
                ->with('siege:ID,Nom')
                ->get()
                ->map(function ($item) {
                    return [
                        'siege_name' => $item->siege->Nom,
                        'count' => $item->count,
                    ];
                }),
        ];
        
        return $stats;
    }
}