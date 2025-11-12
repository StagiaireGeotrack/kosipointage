<?php
// app/Repositories/JourNonTravailleRepository.php

namespace App\Repositories;

use App\Models\JourNonTravaille;
use Illuminate\Support\Facades\DB;

class JourNonTravailleRepository
{
    protected $model;
    
    public function __construct(JourNonTravaille $model)
    {
        $this->model = $model;
    }
    
    public function getFiltered(array $filters = [])
    {
        $query = $this->model->with('siege');
        
        // ✅ AJOUT : Filtrer automatiquement par siège pour les admins non-superadmin
        if (!auth()->user()->IsSuperAdmin) {
            $query->where(function($q) {
                $q->whereNull('SiegeID') // Jours nationaux
                  ->orWhere('SiegeID', auth()->user()->SiegeID); // Jours du siège
            });
        }
        
        // Filtre par recherche (nom)
        if (!empty($filters['search'])) {
            $query->where('Nom', 'like', '%' . $filters['search'] . '%');
        }
        
        // Filtre par siège (pour les superadmins)
        if (!empty($filters['SiegeID'])) {
            if ($filters['SiegeID'] === 'null') {
                $query->whereNull('SiegeID');
            } else {
                $query->where('SiegeID', $filters['SiegeID']);
            }
        }
        
        // Filtre par type
        if (!empty($filters['Type'])) {
            $query->where('Type', $filters['Type']);
        }
        
        // Filtre par année
        if (!empty($filters['annee'])) {
            $query->whereYear('Date', $filters['annee']);
        }
        
        // Filtre par récurrent
        if (isset($filters['Recurrent']) && $filters['Recurrent'] !== '') {
            $query->where('Recurrent', (bool)$filters['Recurrent']);
        }
        
        // Tri
        $sortBy = $filters['sort_by'] ?? 'Date';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        return $query->paginate(5);
    }
    
    public function getAllForExport(array $filters = [])
    {
        $query = $this->model->with('siege');
        
        // ✅ Même filtre pour les admins non-superadmin
        if (!auth()->user()->IsSuperAdmin) {
            $query->where(function($q) {
                $q->whereNull('SiegeID')
                  ->orWhere('SiegeID', auth()->user()->SiegeID);
            });
        }
        
        if (!empty($filters['search'])) {
            $query->where('Nom', 'like', '%' . $filters['search'] . '%');
        }
        
        if (!empty($filters['SiegeID'])) {
            if ($filters['SiegeID'] === 'null') {
                $query->whereNull('SiegeID');
            } else {
                $query->where('SiegeID', $filters['SiegeID']);
            }
        }
        
        if (!empty($filters['Type'])) {
            $query->where('Type', $filters['Type']);
        }
        
        if (!empty($filters['annee'])) {
            $query->whereYear('Date', $filters['annee']);
        }
        
        return $query->orderBy('Date', 'asc')->get()->map(function ($jour) {
            return [
                'ID' => $jour->ID,
                'Date' => ucfirst($jour->Date ? $jour->Date->isoFormat('dddd D MMMM YYYY') : ''),
                'Nom' => $jour->Nom ?? '',
                'Description' => $jour->Description ?? '',
                'Type' => $jour->Type ?? '',
                'Récurrent' => $jour->Recurrent ?? '',
                'Siège' => $jour->siege->Nom ?? '',
                'Date de création' => ucfirst($jour->created_at ? $jour->created_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
                'Dernière mise à jour' => ucfirst($jour->updated_at ? $jour->updated_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss') : ''),
                'Statut' => $jour->Actived ? __('Activé') : __('Désactivé'),
            ];
        });
    }
    
    public function findById($id)
    {
        $query = $this->model->with('siege');
        
        // ✅ Filtrer pour les admins non-superadmin
        if (!auth()->user()->IsSuperAdmin) {
            $query->where(function($q) {
                $q->whereNull('SiegeID')
                  ->orWhere('SiegeID', auth()->user()->SiegeID);
            });
        }
        
        return $query->findOrFail($id);
    }
    
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    public function update($id, array $data)
    {
        $jourNonTravaille = $this->findById($id);
        $jourNonTravaille->update($data);
        return $jourNonTravaille;
    }
    
    public function delete($id)
    {
        $jourNonTravaille = $this->findById($id);
        return $jourNonTravaille->delete();
    }
    
    /**
     * Obtenir les jours non travaillés pour une période et un siège
     */
    public function getJoursNonTravaillesPourPeriode($dateDebut, $dateFin, $siegeId = null)
    {
        $query = $this->model->actif()
            ->periode($dateDebut, $dateFin)
            ->where(function($q) use ($siegeId) {
                $q->whereNull('SiegeID') // Jours nationaux
                  ->orWhere('SiegeID', $siegeId); // Jours spécifiques au siège
            });
        
        return $query->get();
    }
}