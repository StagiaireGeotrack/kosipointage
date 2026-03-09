<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Super Admin et Vendeur peuvent éditer des sièges
        if ( !$user->isTrueSuperAdmin() ) {
            return redirect()->route('sieges.index')
                ->with('error', __('Vous n\'avez pas accès à cette page'));
        }
        
        // Période par défaut (aujourd'hui)
        $period = $request->input('period', 'day');
        $startDate = null;
        $endDate = null;
        $siege_filter = $request->input('siege');
        
        // Déterminer les dates de début et fin en fonction de la période
        switch ($period) {
            case 'day':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::today()->startOfWeek();
                $endDate = Carbon::today()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::today()->startOfMonth();
                $endDate = Carbon::today()->endOfMonth();
                break;
            case 'custom':
                $startDate = Carbon::parse($request->input('start_date', Carbon::today()));
                $endDate = Carbon::parse($request->input('end_date', Carbon::today()))->endOfDay();
                break;
        }
        
        // Récupérer les données pour les graphiques
        $employeesByCompany = $this->getEmployeesByCompany( $siege_filter );
        $employeesBySiege = $this->getEmployeesBySiege( $siege_filter );
        $companiesByStatus = $this->getCompaniesByStatus( $siege_filter );
        $pointagesByDay = $this->getPointagesByPeriod( $startDate, $endDate, 'day' , $siege_filter );
        $sieges =  $this->getSieges($startDate, $endDate , $siege_filter );
        $employeesByDate =  $this->getEmployeesByDate($startDate, $endDate , $siege_filter );
        $kpis = $this->getKPIs($startDate, $endDate , $siege_filter );
        
        
        $sieges_ = EntrepriseSiege::all();

        return view('dashboard.index', compact(
            'employeesByCompany',
            'employeesBySiege',
            'companiesByStatus',
            'pointagesByDay',
            'kpis',
            'period',
            'startDate',
            'endDate',
            'sieges',
            'sieges_',
            'siege_filter',
            'employeesByDate'
        ));
    }

    private function getEmployeesByDate($startDate, $endDate, $siegeFilter = null)
    {
        $query = DB::table("Employes")
            ->whereBetween('CreatedAt', [ $startDate , $endDate ] );

        if ($siegeFilter) {
            $query->where('SiegeID', $siegeFilter);
        }

        return $query->get();
    }

    private function getSieges($startDate, $endDate, $siegeFilter = null)
    {
        $query = DB::table("Entreprises_sieges")
            ->whereBetween('CreatedAt', [$startDate, $endDate]);

        if ($siegeFilter) {
            $query->where('ID', $siegeFilter);
        }

        return $query->get();
    }
    
    private function getEmployeesByCompany($siegeFilter = null)
    {
        $query = DB::table('Employes')
            ->join('Entreprises', 'Employes.SiegeID', '=', 'Entreprises.SiegeID')
            ->select('Entreprises.Nom', DB::raw('COUNT(Employes.ID) as total'))
            ->groupBy('Entreprises.Nom');

        if ($siegeFilter) {
            $query->where('Employes.SiegeID', $siegeFilter);
        }

        return $query->get();
    }
    
    private function getEmployeesBySiege($siegeFilter = null)
    {
        $query = DB::table('Employes')
            ->join('Entreprises_sieges', 'Employes.SiegeID', '=', 'Entreprises_sieges.ID')
            ->select('Entreprises_sieges.Nom', DB::raw('COUNT(Employes.ID) as total'))
            ->groupBy('Entreprises_sieges.Nom');

        if ($siegeFilter) {
            $query->where('Employes.SiegeID', $siegeFilter);
        }

        return $query->get();
    }
    
    private function getCompaniesByStatus($siegeFilter = null)
    {
        $activeQuery   = DB::table('Entreprises')->where('Actived', 1);
        $inactiveQuery = DB::table('Entreprises')->where('Actived', 0);

        if ($siegeFilter) {
            $activeQuery->where('SiegeID', $siegeFilter);
            $inactiveQuery->where('SiegeID', $siegeFilter);
        }

        return [
            'active'   => $activeQuery->count(),
            'inactive' => $inactiveQuery->count(),
        ];
    }
    
    private function getPointagesByPeriod($startDate, $endDate, $groupBy = 'day', $siegeFilter = null)
    {
        $format = $groupBy === 'day' ? '%Y-%m-%d' : ($groupBy === 'week' ? '%Y-%u' : '%Y-%m');

        $query = DB::table('Pointages')
            ->whereBetween('timestamp_', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(timestamp_, '{$format}') as period"),
                DB::raw("COUNT(CASE WHEN type_ = 'entry' THEN 1 END) as entries"),
                DB::raw("COUNT(CASE WHEN type_ = 'exit' THEN 1 END) as exits")
            )
            ->groupBy('period')
            ->orderBy('period');

        if ($siegeFilter) {
            $query->join('Employes', 'Pointages.employee_id', '=', 'Employes.ID')
                ->where('Employes.SiegeID', $siegeFilter);
        }

        return $query->get();
    }
    
    private function getKPIs($startDate, $endDate, $siegeFilter = null)
    {
        $employesQuery  = DB::table('Employes');
        $entreprisesQuery = DB::table('Entreprises');
        $siegesQuery    = DB::table('Entreprises_sieges');
        $pointagesQuery = DB::table('Pointages')->whereBetween('timestamp_', [$startDate, $endDate]);
        $entriesQuery   = DB::table('Pointages')->where('type_', 'entry')->whereBetween('timestamp_', [$startDate, $endDate]);
        $exitsQuery     = DB::table('Pointages')->where('type_', 'exit')->whereBetween('timestamp_', [$startDate, $endDate]);
        $activeCompaniesQuery   = DB::table('Entreprises')->where('Actived', 1);
        $inactiveCompaniesQuery = DB::table('Entreprises')->where('Actived', 0);
        $activeEmployesQuery    = DB::table('Employes')->where('Actived', 1);
        $inactiveEmployesQuery  = DB::table('Employes')->where('Actived', 0);

        if ($siegeFilter) {
            $activeCompaniesQuery->where('SiegeID', $siegeFilter);
            $inactiveCompaniesQuery->where('SiegeID', $siegeFilter);
            $activeEmployesQuery->where('SiegeID', $siegeFilter);
            $inactiveEmployesQuery->where('SiegeID', $siegeFilter);
            $employesQuery->where('SiegeID', $siegeFilter);
            $entreprisesQuery->where('SiegeID', $siegeFilter);
            $siegesQuery->where('ID', $siegeFilter);

            // Jointure pour filtrer les pointages par siège via l'employé
            foreach ([$pointagesQuery, $entriesQuery, $exitsQuery] as $q) {
                $q->join('Employes', 'Pointages.employee_id', '=', 'Employes.ID')
                ->where('Employes.SiegeID', $siegeFilter);
            }
        }

        return [
            'total_employees'  => $employesQuery->count(),
            'total_companies'  => $entreprisesQuery->count(),
            'total_sieges'     => $siegesQuery->count(),
            'pointages_period' => $pointagesQuery->count(),
            'entries_period'   => $entriesQuery->count(),
            'exits_period'     => $exitsQuery->count(),
            'active_companies'   => $activeCompaniesQuery->count(),
            'inactive_companies' => $inactiveCompaniesQuery->count(),
            'active_employees'   => $activeEmployesQuery->count(),
            'inactive_employees' => $inactiveEmployesQuery->count(),
        ];
    }
}