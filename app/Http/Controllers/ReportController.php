<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ExportService;
use Illuminate\Support\Facades\Gate;
use App\Models\EntrepriseSiege;
use App\Models\Employe;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }
    
    public function index()
    {
        // Récupérer les statistiques globales
        $stats = [
            'employees_count' => \App\Models\Employe::count(),
            'pointages_count' => \App\Models\Pointage::count(),
            'today_pointages' => \App\Models\Pointage::whereDate('timestamp_', now()->toDateString())->count(),
            'active_employees' => \App\Models\Employe::where('Actived', 1)->count(),
        ];
        
        // Vue principale des rapports
        return view('reports.index', compact('stats'));
    }
    
    public function daily(Request $request)
    {
        // Filtres
        $filters = $this->getFilters($request);
        
        // Sièges pour le filtre
        $sieges = $this->getSieges();
        
        // Employés pour le filtre
        $employes = $this->getEmployes($filters['SiegeID'] ?? null);
        
        // Requête principale des rapports
        $query = DB::table('rapports_details')
            ->select('*');
            
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Pagination
        $rapports = $query->paginate(10)
            ->appends($request->except('page'));
            
        return view('reports.daily', compact('rapports', 'sieges', 'employes', 'filters'));
    }
    
    public function dayNight(Request $request)
    {
        // Filtres
        $filters = $this->getFilters($request);
        $filters['type_travail'] = $request->input('type_travail');
        
        // Sièges pour le filtre
        $sieges = $this->getSieges();
        
        // Employés pour le filtre
        $employes = $this->getEmployes($filters['SiegeID'] ?? null);
        
        // Requête principale des rapports jour/nuit
        $query = DB::table('rapports_details_jour_nuit')
            ->select('*');
            
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Filtrer par type de travail (JOUR/NUIT) si spécifié
        if (!empty($filters['type_travail'])) {
            $query->where('type_travail', $filters['type_travail']);
        }
        
        // Pagination
        $rapports = $query->paginate(10)
            ->appends($request->except('page'));
            
        return view('reports.day_night', compact('rapports', 'sieges', 'employes', 'filters'));
    }
    
    public function exportExcel(Request $request, string $type)
    {
        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour/nuit et qu'un filtre de type est spécifié
        if ($type === 'day-night' && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer toutes les données pour l'export
        $data = $query->get();
        
        // Déterminer le titre du rapport
        $title = ($type === 'day-night') 
            ? __('Rapport jour/nuit') 
            : __('Rapport quotidien');
            
        // Exporter vers Excel
        return $this->exportService->exportToExcel($data, $title);
    }
    
    public function exportPdf(Request $request, string $type)
    {
        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour/nuit et qu'un filtre de type est spécifié
        if ($type === 'day-night' && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer toutes les données pour l'export
        $data = $query->get();
        
        // Déterminer le titre et la vue du rapport
        $title = ($type === 'day-night') 
            ? 'Rapport jour/nuit' 
            : 'Rapport quotidien';

            
        // Exporter vers PDF
        return $this->exportService->exportToPdf($data, $title, 'exports.generic', [
            'filters' => $filters,
        ]);
    }
    
    private function getFilters(Request $request)
    {
        return $request->only([
            'SiegeID',
            'employee_id',
            'date_from',
            'date_to',
            'sort_by',
            'sort_order',
        ]);
    }
    
    private function getSieges()
    {
        // Si l'utilisateur est SuperAdmin, tous les sièges
        if (Gate::allows('superadmin')) {
            return EntrepriseSiege::all();
        }
        
        // Sinon, uniquement le siège de l'administrateur
        return EntrepriseSiege::where('ID', auth()->user()->SiegeID)->get();
    }
    
    private function getEmployes($siegeId = null)
    {
        $query = Employe::query();
        
        // Si un SiegeID est spécifié dans les filtres
        if ($siegeId) {
            $query->where('SiegeID', $siegeId);
        }
        // Sinon, si l'utilisateur n'est pas SuperAdmin, limiter à son siège
        elseif (!Gate::allows('superadmin')) {
            $query->where('SiegeID', auth()->user()->SiegeID);
        }
        
        return $query->where('Actived', 1)->get();
    }
    
    private function applyFilters($query, array $filters)
    {
        // Filtre par siège
        if (!empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        // Si l'utilisateur n'est pas SuperAdmin, limiter à son siège
        elseif (!Gate::allows('superadmin')) {
            $query->where('SiegeID', auth()->user()->SiegeID);
        }
        
        // Filtre par employé
        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }
        
        // Filtre par plage de dates
        if (!empty($filters['date_from'])) {
            $dateFrom = Carbon::parse($filters['date_from'])->format('Y-m-d');
            $query->where('date_reel', '>=', $dateFrom);
        }
        
        if (!empty($filters['date_to'])) {
            $dateTo = Carbon::parse($filters['date_to'])->format('Y-m-d');
            $query->where('date_reel', '<=', $dateTo);
        }
        
        // Tri
        $sortBy = $filters['sort_by'] ?? 'date_reel';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        return $query;
    }
}