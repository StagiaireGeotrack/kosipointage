<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $employeesByCompany = $this->getEmployeesByCompany();
        $employeesBySiege = $this->getEmployeesBySiege();
        $companiesByStatus = $this->getCompaniesByStatus();
        $pointagesByDay = $this->getPointagesByPeriod($startDate, $endDate, 'day');
        $sieges =  $this->getSieges($startDate, $endDate);
        $employeesByDate =  $this->getEmployeesByDate($startDate, $endDate);
        
        // KPIs
        $kpis = $this->getKPIs($startDate, $endDate);
        
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
            'employeesByDate'
        ));
    }

    private function getEmployeesByDate( $startDate, $endDate )
    {
        return DB::table("Employes")->whereBetween('CreatedAt', [$startDate, $endDate])->get() ;
    }

    private function getSieges( $startDate, $endDate )
    {
        return DB::table("Entreprises_sieges")->whereBetween('CreatedAt', [$startDate, $endDate])->get() ;
    }
    
    private function getEmployeesByCompany()
    {
        return DB::table('Employes')
            ->join('Entreprises', 'Employes.SiegeID', '=', 'Entreprises.SiegeID')
            ->select('Entreprises.Nom', DB::raw('COUNT(Employes.ID) as total'))
            ->groupBy('Entreprises.Nom')
            ->get();
    }
    
    private function getEmployeesBySiege()
    {
        return DB::table('Employes')
            ->join('Entreprises_sieges', 'Employes.SiegeID', '=', 'Entreprises_sieges.ID')
            ->select('Entreprises_sieges.Nom', DB::raw('COUNT(Employes.ID) as total'))
            ->groupBy('Entreprises_sieges.Nom')
            ->get();
    }
    
    private function getCompaniesByStatus()
    {
        return [
            'active' => DB::table('Entreprises')->where('Actived', 1)->count(),
            'inactive' => DB::table('Entreprises')->where('Actived', 0)->count(),
        ];
    }
    
    private function getPointagesByPeriod($startDate, $endDate, $groupBy = 'day')
    {
        $format = $groupBy === 'day' ? '%Y-%m-%d' : ($groupBy === 'week' ? '%Y-%u' : '%Y-%m');
        
        return DB::table('Pointages')
            ->whereBetween('timestamp_', [$startDate, $endDate])
            ->select(
                DB::raw("DATE_FORMAT(timestamp_, '{$format}') as period"),
                DB::raw("COUNT(CASE WHEN type_ = 'entry' THEN 1 END) as entries"),
                DB::raw("COUNT(CASE WHEN type_ = 'exit' THEN 1 END) as exits")
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }
    
    private function getKPIs($startDate, $endDate)
    {
        return [
            'total_employees' => DB::table('Employes')->count(),
            'total_companies' => DB::table('Entreprises')->count(),
            'total_sieges' => DB::table('Entreprises_sieges')->count(),
            'pointages_period' => DB::table('Pointages')
                ->whereBetween('timestamp_', [$startDate, $endDate])
                ->count(),
            'entries_period' => DB::table('Pointages')
                ->where('type_', 'entry')
                ->whereBetween('timestamp_', [$startDate, $endDate])
                ->count(),
            'exits_period' => DB::table('Pointages')
                ->where('type_', 'exit')
                ->whereBetween('timestamp_', [$startDate, $endDate])
                ->count(),
        ];
    }
}