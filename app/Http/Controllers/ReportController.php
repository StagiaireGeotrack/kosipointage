<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    protected $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }
    
    public function index()
    {
        $sieges = EntrepriseSiege::all();
        $mois = collect(range(1, 12))->map(function($m) {
            return [
                'numero' => $m,
                'nom' => \Carbon\Carbon::create()->month($m)->locale('fr')->translatedFormat('F')
            ];
        });
        $annees = range( 2010 , now()->year );
        $stats = [
            'employees_count' => \App\Models\Employe::count(),
            'pointages_count' => \App\Models\Pointage::count(),
            'today_pointages' => \App\Models\Pointage::whereDate('timestamp_', now()->toDateString())->count(),
            'active_employees' => \App\Models\Employe::where('Actived', 1)->count(),
        ];
        
        return view('reports.index', compact('stats' , 'sieges' , 'mois' , 'annees'));
    }

    public function getRapportAuto(Request $request)
    {
        $request->validate([
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'email' => 'nullable|email',
            'mois'  => 'required|integer|min:1|max:12',
            'annee' => 'required|integer|min:2000|max:' . now()->year,
        ],[
            // Messages pour SiegeID
            'SiegeID.required' => 'Le siège est obligatoire.',
            'SiegeID.exists' => 'Veuillez bien sélectionner un siège.',
            
            // Messages pour email
            'email.email' => 'L\'adresse e-mail doit être valide.',
            
            // Messages pour mois
            'mois.required' => 'Le mois est obligatoire.',
            'mois.integer' => 'Veuillez bien sélectionner un mois.',
            'mois.min' => 'Veuillez bien sélectionner un mois.',
            'mois.max' => 'Veuillez bien sélectionner un mois.',
            
            // Messages pour annee
            'annee.required' => 'L\'année est obligatoire.',
            'annee.integer' => 'Veuillez bien sélectionner une année.',
            'annee.min' => 'Veuillez bien sélectionner une année.',
            'annee.max' => 'Veuillez bien sélectionner une année.'
        ]);

        $email = $request->email ?? auth()->user()->Identifiant_email;

        $siege = \App\Models\EntrepriseSiege::find($request->SiegeID);
        
        $nomMois = \Carbon\Carbon::createFromDate($request->annee, $request->mois, 1)
            ->locale('fr')
            ->translatedFormat('F');

        try {
            $response = Http::timeout(30)->withHeaders([
                'key' => 'eight_sharp_key_v1',
            ])->post('https://n8n.zul-annuaire.com/webhook/rapport-pointage-mensuel', [
                'entreprise_siege' => $request->SiegeID,
                'mois' => $request->mois,
                'annee' => $request->annee,
                'to_email' => $email,
            ]);

            $nomMoisFormate = ucfirst($nomMois);

            if ($response->successful()) {
                return redirect()->back()->with('success', 
                    "Rapport généré et envoyé avec succès !<br>" .
                    "<strong>Siège :  </strong> {$siege->Nom}<br>" .
                    "<strong>Période :  </strong> {$nomMoisFormate} {$request->annee}<br>" .
                    "<strong>Envoyé à :  </strong> {$email}"
                );
            } else {
                Log::error('Erreur API rapport', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return redirect()->back()->with('error', 
                    'Erreur lors de la génération du rapport (Code: ' . $response->status() . '). Veuillez réessayer.'
                );
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return redirect()->back()->with('error', 
                'Impossible de contacter le serveur. Vérifiez votre connexion internet.'
            );
        } catch (\Exception $e) {
            Log::error('Exception rapport auto', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 
                'Une erreur technique s\'est produite. Veuillez contacter l\'administrateur.'
            );
        }

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
        $rapports = $query->paginate(5)
            ->appends($request->except('page'));
            
        return view('reports.daily', compact('rapports', 'sieges', 'employes', 'filters'));
    }
    
    public function dayNight(Request $request)
    {
        // Désactiver temporairement ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        
        // Votre code original
        $filters = $this->getFilters($request);
        $filters['type_travail'] = $request->input('type_travail');
        
        $sieges = $this->getSieges();
        $employes = $this->getEmployes($filters['SiegeID'] ?? null);
        
        $query = DB::table('rapports_details_jour_nuit')
            ->select('*');
            
        $query = $this->applyFilters($query, $filters);
        
        if (!empty($filters['type_travail'])) {
            $query->where('type_travail', $filters['type_travail']);
        }
        
        $rapports = $query->paginate(5)
            ->appends($request->except('page'));
            
        // dd($query->toSql(), $query->getBindings());
        return view('reports.day_night', compact('rapports', 'sieges', 'employes', 'filters'));
    }
    
    public function exportExcel(Request $request, string $type)
    {
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour et nuit et qu'un filtre de type est spécifié
        if ($type === 'day-night' && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer toutes les données pour l'export
        $data = $query->get();
        
        // Déterminer le titre du rapport
        $title = ($type === 'day-night') 
            ? __('Rapport jour et nuit') 
            : __('Rapport quotidien');
            
        // Exporter vers Excel
        return $this->exportService->exportToExcel($data, $title);
    }
    
    public function exportPdf(Request $request, string $type)
    {
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour et nuit et qu'un filtre de type est spécifié
        if ($type === 'day-night' && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer toutes les données pour l'export
        $data = $query->get();
        
        // Déterminer le titre et la vue du rapport
        $title = ($type === 'day-night') 
            ? 'Rapport jour et nuit' 
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
        
        return $query->get();
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