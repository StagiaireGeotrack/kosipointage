<?php
// app/Http/Controllers/AllDashboardController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\Entreprise;
use App\Models\Pointage;
use App\Models\Department;
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

        $totalSieges = EntrepriseSiege::whereIn('ID', $siegeIds)->count();
        $totalEntreprises = Entreprise::whereIn('SiegeID', $siegeIds)->count();
        $totalEmployes = Employe::whereIn('SiegeID', $siegeIds)->count();

        $employesActifs = Employe::whereIn('SiegeID', $siegeIds)->where('Actived', 1)->count();
        $employesInactifs = Employe::whereIn('SiegeID', $siegeIds)->where('Actived', 0)->count();

        $entreprisesActives = Entreprise::whereIn('SiegeID', $siegeIds)->where('Actived', 1)->count();
        $entreprisesInactives = Entreprise::whereIn('SiegeID', $siegeIds)->where('Actived', 0)->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $employesCeMois = Employe::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();

        $entreprisesCeMois = Entreprise::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $employesCetteSemaine = Employe::whereIn('SiegeID', $siegeIds)
            ->where('CreatedAt', '>=', $startOfWeek)
            ->count();

        $topSieges = EntrepriseSiege::whereIn('ID', $siegeIds)
            ->withCount('employes', 'entreprises')
            ->orderBy('employes_count', 'desc')
            ->get();

        $employesEvolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Employe::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', '<=', $date->year)
                ->where(function ($query) use ($date) {
                    $query->whereYear('CreatedAt', '<', $date->year)
                          ->orWhere(function ($q) use ($date) {
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

        $entreprisesEvolution = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Entreprise::whereIn('SiegeID', $siegeIds)
                ->whereYear('CreatedAt', '<=', $date->year)
                ->where(function ($query) use ($date) {
                    $query->whereYear('CreatedAt', '<', $date->year)
                          ->orWhere(function ($q) use ($date) {
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

        $employesBySiege = Employe::whereIn('SiegeID', $siegeIds)
            ->select('SiegeID', DB::raw('count(*) as total'))
            ->groupBy('SiegeID')
            ->get()
            ->map(function ($item) {
                $siege = EntrepriseSiege::find($item->SiegeID);
                return [
                    'siege' => $siege ? $siege->Nom : 'Inconnu',
                    'total' => $item->total
                ];
            });

        $entreprisesBySiege = Entreprise::whereIn('SiegeID', $siegeIds)
            ->select('SiegeID', DB::raw('count(*) as total'))
            ->groupBy('SiegeID')
            ->get()
            ->map(function ($item) {
                $siege = EntrepriseSiege::find($item->SiegeID);
                return [
                    'siege' => $siege ? $siege->Nom : 'Inconnu',
                    'total' => $item->total
                ];
            });

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
     * Dashboard pour les Simple Admin ET Supervisor
     * — Supervisor : mêmes données mais filtrées à ses services
     */
    public function dashboardSimpleAdmin()
    {
        $user = auth()->user();

        if (!$user->isSimpleAdmin() && !$user->isSupervisor()) {
            abort(403, 'Accès réservé aux administrateurs');
        }

        if (!$user->SiegeID) {
            abort(403, 'Aucun siège assigné');
        }

        $siege = EntrepriseSiege::find($user->SiegeID);

        // ✅ Détermination du périmètre
        $isSupervisor = $user->isSupervisor();
        $serviceIds = $isSupervisor ? $user->getSupervisorServiceIds() : null;

        // Liste des IDs employés accessibles
        if ($isSupervisor) {
            $accessibleEmployeeIds = empty($serviceIds)
                ? [-1]
                : Employe::whereIn('department_id', $serviceIds)
                    ->where('SiegeID', $user->SiegeID)
                    ->pluck('ID')
                    ->toArray();
        } else {
            // Simple Admin : tous les employés du siège
            $accessibleEmployeeIds = null; // pas de filtre spécifique
        }

        // Dates
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // ============================================================
        // STATISTIQUES GÉNÉRALES
        // ============================================================

        // Entreprises : pas filtré pour Supervisor (le Supervisor n'a pas accès aux entreprises)
        // → on garde les entreprises du siège pour que la vue ne casse pas
        $totalEntreprises = Entreprise::where('SiegeID', $user->SiegeID)->count();
        $entreprisesActives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 1)->count();
        $entreprisesInactives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 0)->count();
        $entreprisesCeMois = Entreprise::where('SiegeID', $user->SiegeID)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();

        // Employés : filtrés pour Supervisor
        $employesQuery = Employe::where('SiegeID', $user->SiegeID);
        if ($isSupervisor) {
            $employesQuery->whereIn('ID', $accessibleEmployeeIds);
        }

        $totalEmployes = (clone $employesQuery)->count();
        $employesActifs = (clone $employesQuery)->where('Actived', 1)->count();
        $employesInactifs = (clone $employesQuery)->where('Actived', 0)->count();
        $employesCeMois = (clone $employesQuery)->where('CreatedAt', '>=', $startOfMonth)->count();

        // Pointages : filtrés pour Supervisor
        $pointagesBase = Pointage::where('SiegeID', $user->SiegeID);
        if ($isSupervisor) {
            $pointagesBase->whereIn('employee_id', $accessibleEmployeeIds);
        }

        $pointagesToday = (clone $pointagesBase)->whereDate('timestamp_', $today)->count();
        $pointagesWeek = (clone $pointagesBase)->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])->count();
        $pointagesMonth = (clone $pointagesBase)->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])->count();

        // Taux de présence
        $employesPresentsToday = (clone $pointagesBase)
            ->where('type_', 'entry')
            ->whereDate('timestamp_', $today)
            ->distinct('employee_id')
            ->count('employee_id');
        $tauxPresence = $totalEmployes > 0 ? round(($employesPresentsToday / $totalEmployes) * 100, 1) : 0;

        // Moyenne pointages / jour
        $avgPointagesPerDay = $pointagesMonth > 0 ? round($pointagesMonth / Carbon::now()->day, 1) : 0;

        // ============================================================
        // GRAPHIQUES
        // ============================================================

        // Pointages 7 derniers jours
        $last7Days = [];
        $pointagesLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $q = Pointage::where('SiegeID', $user->SiegeID)->whereDate('timestamp_', $date);
            if ($isSupervisor) {
                $q->whereIn('employee_id', $accessibleEmployeeIds);
            }
            $pointagesLast7Days[] = $q->count();
        }

        // Pointages par type
        $pointagesEntree = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'entry')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek]);
        if ($isSupervisor) {
            $pointagesEntree->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $pointagesEntree = $pointagesEntree->count();

        $pointagesSortie = Pointage::where('SiegeID', $user->SiegeID)
            ->where('type_', 'exit')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek]);
        if ($isSupervisor) {
            $pointagesSortie->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $pointagesSortie = $pointagesSortie->count();

        // Pointages par méthode
        $qMethod = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek]);
        if ($isSupervisor) {
            $qMethod->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $pointagesByMethod = $qMethod
            ->select('auth_method', DB::raw('COUNT(*) as count'))
            ->groupBy('auth_method')
            ->get();

        // Pointages par heure (aujourd'hui)
        $qHour = Pointage::where('SiegeID', $user->SiegeID)
            ->whereDate('timestamp_', $today);
        if ($isSupervisor) {
            $qHour->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $pointagesByHour = $qHour
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
        $qTop = Pointage::where('SiegeID', $user->SiegeID)
            ->whereBetween('timestamp_', [$startOfMonth, $endOfMonth]);
        if ($isSupervisor) {
            $qTop->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $topEmployes = $qTop
            ->select('employee_id', DB::raw('COUNT(*) as total_pointages'))
            ->groupBy('employee_id')
            ->orderBy('total_pointages', 'desc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                $employe = Employe::find($item->employee_id);
                return [
                    'name' => $employe->Nom ?? 'Inconnu',
                    'num_mat' => $employe->num_mat ?? '-',
                    'total' => $item->total_pointages
                ];
            });

        // Pointages 30 derniers jours
        $pointagesLast30Days = [];
        $datesLast30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $datesLast30Days[] = $date->format('d/m');
            $q = Pointage::where('SiegeID', $user->SiegeID)->whereDate('timestamp_', $date);
            if ($isSupervisor) {
                $q->whereIn('employee_id', $accessibleEmployeeIds);
            }
            $pointagesLast30Days[] = $q->count();
        }

        // ✅ Utilisation de la MÊME vue que le Simple Admin
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
