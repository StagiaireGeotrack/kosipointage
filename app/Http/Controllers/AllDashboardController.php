<?php
// app/Http/Controllers/AllDashboardController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\Entreprise;
use App\Models\Pointage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AllDashboardController extends Controller
{
    /**
     * Dashboard pour les Vendeurs
     */
    public function dashboardSeller() 
    {
        $user = auth()->user();
        
        if (!$user->isSeller()) {
            abort(403, 'Accès réservé aux vendeurs');
        }
        
        $siegeIds = $user->getSiegeIdsAccessibles();
        
        // Statistiques générales
        $totalSieges = EntrepriseSiege::whereIn('ID', $siegeIds)->count();
        $totalEntreprises = Entreprise::whereIn('SiegeID', $siegeIds)->count();
        $totalEmployes = Employe::whereIn('SiegeID', $siegeIds)->count();
        
        // Employés actifs vs inactifs
        $employesActifs = Employe::whereIn('SiegeID', $siegeIds)->where('Actived', 1)->count();
        $employesInactifs = Employe::whereIn('SiegeID', $siegeIds)->where('Actived', 0)->count();
        
        // Entreprises actives vs inactives
        $entreprisesActives = Entreprise::whereIn('SiegeID', $siegeIds)->where('Actived', 1)->count();
        $entreprisesInactives = Entreprise::whereIn('SiegeID', $siegeIds)->where('Actived', 0)->count();
        
        // Employés ajoutés ce mois
        $startOfMonth = Carbon::now()->startOfMonth();
        $employesCeMois = Employe::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();
        
        // Entreprises ajoutées ce mois
        $entreprisesCeMois = Entreprise::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();
        
        // Employés ajoutés cette semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $employesCetteSemaine = Employe::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfWeek)
            ->count();
        
        // Tous les sièges
        $topSieges = EntrepriseSiege::whereIn('ID', $siegeIds)
            ->withCount('employes', 'entreprises')
            ->orderBy('employes_count', 'desc')
            ->get();
        
        // Évolution des employés sur 12 mois
        $employesEvolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Employe::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', '<=', $date->year)
                ->where(function($query) use ($date) {
                    $query->whereYear('CreatedAt', '<', $date->year)
                          ->orWhere(function($q) use ($date) {
                              $q->whereYear('CreatedAt', '=', $date->year)
                                ->whereMonth('CreatedAt', '<=', $date->month);
                          });
                })
                ->count();
            $employesEvolution[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Évolution des entreprises sur 12 mois
        $entreprisesEvolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Entreprise::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', '<=', $date->year)
                ->where(function($query) use ($date) {
                    $query->whereYear('CreatedAt', '<', $date->year)
                          ->orWhere(function($q) use ($date) {
                              $q->whereYear('CreatedAt', '=', $date->year)
                                ->whereMonth('CreatedAt', '<=', $date->month);
                          });
                })
                ->count();
            $entreprisesEvolution[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Répartition des employés par siège
        $employesBySiege = Employe::whereIn('SiegeID', $siegeIds)
            ->select('SiegeID', DB::raw('count(*) as total'))
            ->groupBy('SiegeID')
            ->get()
            ->map(function($item) {
                $siege = EntrepriseSiege::find($item->SiegeID);
                return [
                    'siege' => $siege ? $siege->Nom : 'Inconnu',
                    'total' => $item->total
                ];
            });
        
        // Répartition des entreprises par siège
        $entreprisesBySiege = Entreprise::whereIn('SiegeID', $siegeIds)
            ->select('SiegeID', DB::raw('count(*) as total'))
            ->groupBy('SiegeID')
            ->get()
            ->map(function($item) {
                $siege = EntrepriseSiege::find($item->SiegeID);
                return [
                    'siege' => $siege ? $siege->Nom : 'Inconnu',
                    'total' => $item->total
                ];
            });
        
        // Employés ajoutés par mois (6 derniers mois)
        $employesParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Employe::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', $date->year)
                ->whereMonth('CreatedAt', $date->month)
                ->count();
            $employesParMois[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Entreprises ajoutées par mois (6 derniers mois)
        $entreprisesParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Entreprise::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', $date->year)
                ->whereMonth('CreatedAt', $date->month)
                ->count();
            $entreprisesParMois[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        
        // Statistiques détaillées par siège
        $sieges = EntrepriseSiege::whereIn('ID', $siegeIds)
            ->withCount(['entreprises', 'employes'])
            ->get();
        
        return view('dashboards.seller', compact(
            'totalSieges',
            'totalEntreprises',
            'totalEmployes',
            'sieges',
            'employesActifs',
            'employesInactifs',
            'entreprisesActives',
            'entreprisesInactives',
            'employesCeMois',
            'entreprisesCeMois',
            'employesCetteSemaine',
            'topSieges',
            'employesBySiege',
            'employesEvolution',
            'entreprisesEvolution',
            'entreprisesBySiege',
            'employesParMois',
            'entreprisesParMois'
        ));
    }

    /**
     * Dashboard pour les Simple Admin
     */
    public function dashboardSimpleAdmin() 
    {
        $user = auth()->user();
        
        if (!$user->isSimpleAdmin()) {
            abort(403, 'Accès réservé aux administrateurs simples');
        }
        
        if (!$user->SiegeID) {
            abort(403, 'Aucun siège assigné');
        }
        
        $siege = EntrepriseSiege::find($user->SiegeID);
        
        // Dates pour les statistiques
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Statistiques générales
        $totalEntreprises = Entreprise::where('SiegeID', $user->SiegeID)->count();
        $totalEmployes = Employe::where('SiegeID', $user->SiegeID)->count();
        
        // Pointages
        $pointagesToday = Pointage::where('SiegeID', $user->SiegeID)->whereDate('timestamp_', $today)->count();
        $pointagesWeek = Pointage::where('SiegeID', $user->SiegeID)->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])->count();
        $pointagesMonth = Pointage::where('SiegeID', $user->SiegeID)->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])->count();
        
        // Employés et entreprises actifs
        $employesActifs = Employe::where('SiegeID', $user->SiegeID)->where('Actived', 1)->count();
        $employesInactifs = Employe::where('SiegeID', $user->SiegeID)->where('Actived', 0)->count();
        $entreprisesActives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 1)->count();
        $entreprisesInactives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 0)->count();
        
        // Taux de présence
        $employesPresentsToday = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'entry')
            ->whereDate('timestamp_', $today)
            ->distinct('employee_id')
            ->count('employee_id');
        $tauxPresence = $totalEmployes > 0 ? round(($employesPresentsToday / $totalEmployes) * 100, 1) : 0;
        
        // Moyenne pointages par jour
        $avgPointagesPerDay = $pointagesMonth > 0 ? round($pointagesMonth / Carbon::now()->day, 1) : 0;
        
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
        
        // Pointages par type
        $pointagesEntree = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'entry')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->count();
            
        $pointagesSortie = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'exit')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->count();
        
        // Pointages par méthode
        $pointagesByMethod = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->select('auth_method', DB::raw('COUNT(*) as count'))
            ->groupBy('auth_method')
            ->get();
        
        // Pointages par heure (aujourd'hui)
        $pointagesByHour = Pointage::where('SiegeID', $user->SiegeID)
            ->whereDate('timestamp_', $today)
            ->select(DB::raw('HOUR(timestamp_) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();
        
        $pointagesParHeure = [];
        for ($h = 6; $h <= 20; $h++) {
            $pointagesParHeure[] = [
                'hour' => $h . 'h',
                'count' => $pointagesByHour[$h] ?? 0
            ];
        }
        
        // Top 10 employés ce mois
        $topEmployes = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])
            ->select('employee_id', DB::raw('COUNT(*) as total_pointages'))
            ->groupBy('employee_id')
            ->orderBy('total_pointages', 'desc')
            ->take(10)
            ->get()
            ->map(function($item) {
                $employe = Employe::find($item->employee_id);
                return [
                    'name' => $employe ? ($employe->Nom . ' ' . $employe->Prenom) : 'Inconnu',
                    'total' => $item->total_pointages
                ];
            });
        
        // Évolution des pointages sur 30 jours
        $pointagesLast30Days = [];
        $datesLast30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $datesLast30Days[] = $date->format('d/m');
            $pointagesLast30Days[] = Pointage::where('SiegeID', $user->SiegeID)
                ->whereDate('timestamp_', $date)
                ->count();
        }
        
        // Employés ajoutés ce mois
        $employesCeMois = Employe::where('SiegeID', $user->SiegeID)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();
        
        // Entreprises ajoutées ce mois
        $entreprisesCeMois = Entreprise::where('SiegeID', $user->SiegeID)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();

        
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
            'pointagesByMethod',
            'pointagesParHeure',
            'topEmployes',
            'tauxPresence',
            'avgPointagesPerDay',
            'employesPresentsToday',
            'pointagesLast30Days',
            'datesLast30Days',
            'employesCeMois',
            'entreprisesCeMois'
        ));
    }
}