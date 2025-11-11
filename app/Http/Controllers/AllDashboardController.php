<?php
// app/Http/Controllers/AllDashboardController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\Entreprise;
use App\Models\Pointage;
use Carbon\Carbon;

class AllDashboardController extends Controller
{
    /**
     * Dashboard pour les Vendeurs
     */
    public function dashboardSeller() 
    {
        $user = auth()->user();
        
        // Vérifier que c'est bien un vendeur
        if (!$user->isSeller()) {
            abort(403, 'Accès réservé aux vendeurs');
        }
        
        $siegeIds = $user->getSiegeIdsAccessibles();
        
        // Statistiques générales
        $totalSieges = EntrepriseSiege::whereIn('ID', $siegeIds)->count();
        $totalEntreprises = Entreprise::whereIn('SiegeID', $siegeIds)->count();
        $totalEmployes = Employe::whereIn('SiegeID', $siegeIds)->count();
        
        // Statistiques par siège
        $sieges = EntrepriseSiege::whereIn('ID', $siegeIds)
            ->withCount(['entreprises', 'employes'])
            ->get();
        
        // Employés actifs vs inactifs
        $employesActifs = Employe::whereIn('SiegeID', $siegeIds)
            ->where('Actived', 1)
            ->count();
        $employesInactifs = Employe::whereIn('SiegeID', $siegeIds)
            ->where('Actived', 0)
            ->count();
        
        // Entreprises actives vs inactives
        $entreprisesActives = Entreprise::whereIn('SiegeID', $siegeIds)
            ->where('Actived', 1)
            ->count();
        $entreprisesInactives = Entreprise::whereIn('SiegeID', $siegeIds)
            ->where('Actived', 0)
            ->count();
        
        // Données pour les graphiques
        $siegesData = [
            'labels' => $sieges->pluck('Nom')->toArray(),
            'entreprises' => $sieges->pluck('entreprises_count')->toArray(),
            'employes' => $sieges->pluck('employes_count')->toArray(),
        ];
        
        return view('dashboards.seller', compact(
            'totalSieges',
            'totalEntreprises',
            'totalEmployes',
            'sieges',
            'employesActifs',
            'employesInactifs',
            'entreprisesActives',
            'entreprisesInactives',
            'siegesData'
        ));
    }

    /**
     * Dashboard pour les Simple Admin
     */
    public function dashboardSimpleAdmin() 
    {
        $user = auth()->user();
        
        // Vérifier que c'est bien un simple admin
        if (!$user->isSimpleAdmin()) {
            abort(403, 'Accès réservé aux administrateurs simples');
        }
        
        // Vérifier qu'il a un siège
        if (!$user->SiegeID) {
            abort(403, 'Aucun siège assigné');
        }
        
        $siege = EntrepriseSiege::find($user->SiegeID);
        
        // Statistiques générales
        $totalEntreprises = Entreprise::where('SiegeID', $user->SiegeID)->count();
        $totalEmployes = Employe::where('SiegeID', $user->SiegeID)->count();
        
        // Pointages du jour
        $today = Carbon::today();
        $pointagesToday = Pointage::where('SiegeID', $user->SiegeID)
            ->whereDate('timestamp_', $today)
            ->count();
        
        // Pointages de la semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $pointagesWeek = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->count();
        
        // Pointages du mois
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $pointagesMonth = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])
            ->count();
        
        // Employés actifs vs inactifs
        $employesActifs = Employe::where('SiegeID', $user->SiegeID)
            ->where('Actived', 1)
            ->count();
        $employesInactifs = Employe::where('SiegeID', $user->SiegeID)
            ->where('Actived', 0)
            ->count();
        
        // Entreprises actives vs inactives
        $entreprisesActives = Entreprise::where('SiegeID', $user->SiegeID)
            ->where('Actived', 1)
            ->count();
        $entreprisesInactives = Entreprise::where('SiegeID', $user->SiegeID)
            ->where('Actived', 0)
            ->count();
        
        // Pointages des 7 derniers jours
        $last7Days = [];
        $pointagesLast7Days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $pointagesLast7Days[] = Pointage::where('SiegeID', $user->SiegeID)
                ->whereDate('timestamp_', $date)
                ->count();
        }
        
        // Pointages par type (entrée/sortie)
        $pointagesEntree = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'entry')
            ->whereDate('timestamp_', $today)
            ->count();
        $pointagesSortie = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'exit')
            ->whereDate('timestamp_', $today)
            ->count();
        
        // Pointages par méthode d'authentification
        $pointagesByMethod = Pointage::where('SiegeID', $user->SiegeID)
            ->whereDate('timestamp_', $today)
            ->selectRaw('auth_method, COUNT(*) as count')
            ->groupBy('auth_method')
            ->get();
        
        return view('dashboards.simple-admin', compact(
            'siege',
            'totalEntreprises',
            'totalEmployes',
            'pointagesToday',
            'pointagesWeek',
            'pointagesMonth',
            'employesActifs',
            'employesInactifs',
            'entreprisesActives',
            'entreprisesInactives',
            'last7Days',
            'pointagesLast7Days',
            'pointagesEntree',
            'pointagesSortie',
            'pointagesByMethod'
        ));
    }
}