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
use App\Traits\EmployeeAccessTrait;

class AllDashboardController extends Controller
{
    use EmployeeAccessTrait;

    /**
     * Cache des IDs accessibles pour éviter N requêtes.
     */
    private ?array $cachedAccessibleIds = null;

    private function getAccessibleIdsCached($user): array
    {
        if ($this->cachedAccessibleIds === null) {
            $this->cachedAccessibleIds = $this->getAccessibleEmployeeIds($user);
        }
        return $this->cachedAccessibleIds;
    }

    /**
     * Query Employes filtrée : Supervisor → ses services, sinon → son siège.
     */
    private function employesQuery($user)
    {
        $q = Employe::query();
        if ($user->isSupervisor()) {
            $q->whereIn('ID', $this->getAccessibleIdsCached($user));
        } else {
            $q->where('SiegeID', $user->SiegeID);
        }
        return $q;
    }

    /**
     * Query Pointages filtrée : Supervisor → ses employés, sinon → son siège.
     */
    private function pointagesQuery($user)
    {
        $q = Pointage::query();
        if ($user->isSupervisor()) {
            $q->whereIn('employee_id', $this->getAccessibleIdsCached($user));
        } else {
            $q->where('SiegeID', $user->SiegeID);
        }
        return $q;
    }

    /**
     * Dashboard pour les Vendeurs (INCHANGÉ)
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
     * Dashboard pour les Simple Admin ET Responsables de service.
     * Le filtre s'applique uniquement au Supervisor.
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
        
        $isSupervisor = $user->isSupervisor();
        $siege = EntrepriseSiege::find($user->SiegeID);
        
        // Dates pour les statistiques
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // =====================================================
        // STATISTIQUES GÉNÉRALES
        // =====================================================
        // Entreprises : inchangé (dans le siège)
        $totalEntreprises = Entreprise::where('SiegeID', $user->SiegeID)->count();
        
        // Employés : filtré pour Supervisor
        $totalEmployes = $this->employesQuery($user)->count();
        
        // =====================================================
        // POINTAGES : filtrés pour Supervisor
        // =====================================================
        $pointagesToday = $this->pointagesQuery($user)
            ->whereDate('timestamp_', $today)->count();
        
        $pointagesWeek = $this->pointagesQuery($user)
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])->count();
        
        $pointagesMonth = $this->pointagesQuery($user)
            ->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])->count();
        
        // =====================================================
        // EMPLOYÉS ACTIFS/INACTIFS : filtrés pour Supervisor
        // =====================================================
        $employesActifs = $this->employesQuery($user)->where('Actived', 1)->count();
        $employesInactifs = $this->employesQuery($user)->where('Actived', 0)->count();
        
        // Entreprises : inchangé
        $entreprisesActives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 1)->count();
        $entreprisesInactives = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 0)->count();
        
        // =====================================================
        // TAUX DE PRÉSENCE
        // =====================================================
        $employesPresentsToday = $this->pointagesQuery($user)
            ->where('type_', 'entry')
            ->whereDate('timestamp_', $today)
            ->distinct('employee_id')
            ->count('employee_id');
        
        $tauxPresence = $totalEmployes > 0 ? round(($employesPresentsToday / $totalEmployes) * 100, 1) : 0;
        
        $avgPointagesPerDay = $pointagesMonth > 0 ? round($pointagesMonth / Carbon::now()->day, 1) : 0;
        
        // =====================================================
        // POINTAGES 7 DERNIERS JOURS
        // =====================================================
        $last7Days = [];
        $pointagesLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('d/m');
            $pointagesLast7Days[] = $this->pointagesQuery($user)
                ->whereDate('timestamp_', $date)
                ->count();
        }
        
        // =====================================================
        // POINTAGES PAR TYPE
        // =====================================================
        $pointagesEntree = $this->pointagesQuery($user)
            ->where('type_', 'entry')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->count();
            
        $pointagesSortie = $this->pointagesQuery($user)
            ->where('type_', 'exit')
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->count();
        
        // =====================================================
        // POINTAGES PAR MÉTHODE
        // =====================================================
        $pointagesByMethod = $this->pointagesQuery($user)
            ->whereBetween('timestamp_', [$startOfWeek, $endOfWeek])
            ->select('auth_method', DB::raw('COUNT(*) as count'))
            ->groupBy('auth_method')
            ->get();
        
        // =====================================================
        // POINTAGES PAR HEURE (aujourd'hui)
        // =====================================================
        $pointagesByHour = $this->pointagesQuery($user)
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
        
        // =====================================================
        // TOP 10 EMPLOYÉS (ce mois) : filtrés pour Supervisor
        // =====================================================
        $topEmployesQuery = $this->pointagesQuery($user)
            ->whereBetween('timestamp_', [$startOfMonth, $endOfMonth])
            ->select('employee_id', DB::raw('COUNT(*) as total_pointages'))
            ->groupBy('employee_id')
            ->orderBy('total_pointages', 'desc')
            ->take(10)
            ->get();
        
        $topEmployes = $topEmployesQuery->map(function($item) {
            $employe = Employe::find($item->employee_id);
            return [
                'name' => $employe->Nom ?? 'Inconnu',
                'num_mat' => $employe->num_mat ?? '-',
                'total' => $item->total_pointages
            ];
        });
        
        // =====================================================
        // POINTAGES 30 DERNIERS JOURS
        // =====================================================
        $pointagesLast30Days = [];
        $datesLast30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $datesLast30Days[] = $date->format('d/m');
            $pointagesLast30Days[] = $this->pointagesQuery($user)
                ->whereDate('timestamp_', $date)
                ->count();
        }
        
        // =====================================================
        // EMPLOYÉS / ENTREPRISES AJOUTÉS CE MOIS
        // =====================================================
        $employesCeMois = $this->employesQuery($user)
            ->where('CreatedAt', '>=', $startOfMonth)
            ->count();
        
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
            'entreprisesCeMois',
            'isSupervisor'
        ));
    }
}