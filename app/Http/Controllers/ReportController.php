<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Conge;
use App\Models\Employe;
use App\Models\JourNonTravaille;
use App\Exports\ReportMultiSheetExport;
use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use App\Services\ActivityLogService;
use App\Services\EventDetectionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }
    
    public function index()
    {   
        $user = auth()->user();
        if ($user->isSupervisor()) {
            $employeeIds = \App\Services\AccessScopeService::getAccessibleEmployeeIds($user);
            $query->whereIn('employee_id', empty($employeeIds) ? [-1] : $employeeIds);
        }
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

        // ── Vérification des événements non résolus AVANT l'appel API ──
        $dateFrom = Carbon::createFromDate($request->annee, $request->mois, 1)->format('Y-m-d');
        $dateTo   = Carbon::createFromDate($request->annee, $request->mois, 1)->endOfMonth()->format('Y-m-d');

        $count = app(EventDetectionService::class)->countUnresolvedInRange(
            (int) $request->SiegeID,
            $dateFrom,
            $dateTo
        );

        if ($count > 0) {
            $nomMoisFormate = ucfirst($nomMois);
            return redirect()->back()->with('error',
                "Impossible d'envoyer le rapport.<br>" .
                "Vous avez <strong>{$count} événement(s) non résolu(s)</strong> sur <strong>{$siege->Nom}</strong> " .
                "pour le mois de <strong>{$nomMoisFormate} {$request->annee}</strong>.<br>" .
                "Corrigez-les sur la page des événements avant de générer ce rapport."
            );
        }

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

        // Bloquer l'export pour TOUS les utilisateurs : dates obligatoires + vérification des événements
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // Exiger obligatoirement une plage de dates
        if (empty($dateFrom) || empty($dateTo)) {
            return redirect()->back()
                ->with('error', 'Veuillez spécifier une date de début et une date de fin avant d\'exporter les rapports.');
        }

        // Déterminer le siège à vérifier : filtre de la requête en priorité, sinon siège de l'utilisateur
        // null = SuperAdmin sans filtre → vérification sur tous les sièges
        $checkSiegeId = $request->input('SiegeID') ? (int) $request->input('SiegeID')
                      : (auth()->user()->SiegeID ? (int) auth()->user()->SiegeID : null);

        $count = app(EventDetectionService::class)->countUnresolvedInRange(
            $checkSiegeId,
            $dateFrom,
            $dateTo
        );
        if ($count > 0) {
            return redirect()->route('evenements.index')
                ->with('error', "Vous avez {$count} événement(s) non résolu(s) entre le {$dateFrom} et le {$dateTo}. Corrigez-les avant d'exporter les rapports.");
        }

        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        $isDayNight = ($type === 'day-night');
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour et nuit et qu'un filtre de type est spécifié
        if ($isDayNight && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer les données
        $results = $query->get();
        
        // ✅ Trier par date_reel avec PHP (plus ancien en premier)
        $sortedResults = $results->sortBy(function ($rapport) {
            return strtotime($rapport->date_reel);
        });
        
        // Mapper les données
        $data = $sortedResults->map(function ($rapport) use ($isDayNight) {
            $row = [
                'N° Matricule' => $rapport->num_mat ?? "-",
                'Employé(e) Nom' => $rapport->employee_nom,
                'Siège Nom' => $rapport->siege_nom,
                'Date pointage' => ucfirst(Carbon::parse($rapport->date_reel)->isoFormat('dddd D MMMM YYYY')),
            ];
            
            // Ajouter le type de travail uniquement pour les rapports jour/nuit
            if ($isDayNight) {
                $row['Type travail'] = $rapport->type_travail;
            }
            
            $row += [
                'Heure Entrée' => $rapport->heure_entree,
                'Pause Déjeuner' => $rapport->pause_dejeuner,
                'Heure Sortie' => $rapport->heure_sortie,
                'Total Heure' => $rapport->total_heure_journee,
            ];
            
            return $row;
        })->values(); // ✅ Réindexer la collection après le tri
        
        // Déterminer le titre du rapport
        $title = $isDayNight ? __('Rapport jour et nuit') : __('Rapport quotidien');
        
        // Exporter
        ActivityLogService::log(action: 'export_excel', modelType: 'Report');

        // Si un employé est sélectionné → 1 XLSX multi-onglets
        if (!empty($filters['employee_id'])) {
            $sheets = $this->buildEmployeeSheets(
                $data, $sortedResults,
                (int) $filters['employee_id'], $checkSiegeId,
                $dateFrom, $dateTo, $isDayNight
            );
            $export = new ReportMultiSheetExport(...$sheets);
            return Excel::download($export, $title . '_' . Carbon::now()->format('Y-m-d') . '.xlsx');
        }

        // Sinon → ZIP : 1 XLSX multi-onglets par employé
        return $this->buildMultiSheetZip($sortedResults, $checkSiegeId, $dateFrom, $dateTo, $isDayNight, $title);
    }

    public function exportPdf(Request $request, string $type)
    {
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // Bloquer l'export pour TOUS les utilisateurs : dates obligatoires + vérification des événements
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        // Exiger obligatoirement une plage de dates
        if (empty($dateFrom) || empty($dateTo)) {
            return redirect()->back()
                ->with('error', 'Veuillez spécifier une date de début et une date de fin avant d\'exporter les rapports.');
        }

        // Déterminer le siège à vérifier : filtre de la requête en priorité, sinon siège de l'utilisateur
        // null = SuperAdmin sans filtre → vérification sur tous les sièges
        $checkSiegeId = $request->input('SiegeID') ? (int) $request->input('SiegeID')
                      : (auth()->user()->SiegeID ? (int) auth()->user()->SiegeID : null);

        $count = app(EventDetectionService::class)->countUnresolvedInRange(
            $checkSiegeId,
            $dateFrom,
            $dateTo
        );
        if ($count > 0) {
            return redirect()->route('evenements.index')
                ->with('error', "Vous avez {$count} événement(s) non résolu(s) entre le {$dateFrom} et le {$dateTo}. Corrigez-les avant d'exporter les rapports.");
        }

        // Filtres
        $filters = $this->getFilters($request);
        
        // Déterminer la table source en fonction du type de rapport
        $table = ($type === 'day-night') ? 'rapports_details_jour_nuit' : 'rapports_details';
        $isDayNight = ($type === 'day-night');
        
        // Requête d'export
        $query = DB::table($table)->select('*');
        
        // Application des filtres
        $query = $this->applyFilters($query, $filters);
        
        // Si c'est un rapport jour et nuit et qu'un filtre de type est spécifié
        if ($isDayNight && !empty($request->input('type_travail'))) {
            $query->where('type_travail', $request->input('type_travail'));
        }
        
        // Récupérer les données
        $results = $query->get();
        
        // ✅ Trier par date_reel avec PHP (plus ancien en premier)
        $sortedResults = $results->sortBy(function ($rapport) {
            return strtotime($rapport->date_reel);
        });
        
        // Mapper les données
        $data = $sortedResults->map(function ($rapport) use ($isDayNight) {
            $row = [
                'N° Matricule' => $rapport->num_mat,
                'Employé(e) Nom' => $rapport->employee_nom,
                'Siège Nom' => $rapport->siege_nom,
                'Date pointage' => ucfirst(Carbon::parse($rapport->date_reel)->isoFormat('dddd D MMMM YYYY')),
            ];
            
            // Ajouter le type de travail uniquement pour les rapports jour/nuit
            if ($isDayNight) {
                $row['Type travail'] = $rapport->type_travail;
            }
            
            $row += [
                'Heure Entrée' => $rapport->heure_entree,
                'Pause Déjeuner' => $rapport->pause_dejeuner,
                'Heure Sortie' => $rapport->heure_sortie,
                'Total Heure' => $rapport->total_heure_journee,
            ];
            
            return $row;
        })->values(); // ✅ Réindexer la collection après le tri
        
        // Déterminer le titre du rapport
        $title = $isDayNight ? 'Rapport jour et nuit' : 'Rapport quotidien';
        
        // Exporter
        ActivityLogService::log(action: 'export_pdf', modelType: 'Report');

        // Si un employé est sélectionné → 1 PDF multi-sections
        if (!empty($filters['employee_id'])) {
            $sheets = $this->buildEmployeeSheets(
                $data, $sortedResults,
                (int) $filters['employee_id'], $checkSiegeId,
                $dateFrom, $dateTo, $isDayNight
            );
            $viewData = [
                'title'    => $title,
                'date'     => Carbon::now()->isoFormat('dddd D MMMM YYYY - HH:mm:ss'),
                'user'     => auth()->user()->Identifiant_email,
                'filters'  => $filters,
                'rapport'  => $sheets[0],
                'conges'   => $sheets[1],
                'feries'   => $sheets[2],
                'absences' => $sheets[3],
            ];
            $pdf = Pdf::loadView('exports.rapport_complet', $viewData)->setPaper('a4', 'landscape');
            return $pdf->download($title . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf');
        }

        // Sinon → ZIP : 1 PDF multi-sections par employé
        return $this->buildMultiSectionPdfZip($sortedResults, $checkSiegeId, $dateFrom, $dateTo, $isDayNight, $title, $filters);
    }
    
    // ─────────────────────────────────────────────────────────────────
    // Méthodes privées : construction des onglets / sections
    // ─────────────────────────────────────────────────────────────────

    /**
     * Construit les 4 collections de données pour un employé donné.
     * Retourne [rapport, conges, feries, absences] (Collections).
     */
    private function buildEmployeeSheets(
        Collection $rapportData,
        Collection $rawResults,
        int $employeeId,
        ?int $siegeId,
        string $dateFrom,
        string $dateTo,
        bool $isDayNight
    ): array {
        // ── Congés ──────────────────────────────────────────────────
        $congesModels = Conge::withoutGlobalScopes()
            ->with(['employe', 'siege'])
            ->where('employee_id', $employeeId)
            ->where('date_debut', '<=', $dateTo)
            ->where('date_fin',   '>=', $dateFrom)
            ->orderBy('date_debut')
            ->get();

        $congesData = $congesModels->map(fn($c) => [
            'N° Matricule'  => $c->employe?->num_mat ?? '-',
            'Employé(e) Nom'=> $c->employe?->Nom,
            'Siège Nom'     => $c->siege?->Nom,
            'Date Début'    => ucfirst($c->date_debut->isoFormat('dddd D MMMM YYYY')),
            'Date Fin'      => ucfirst($c->date_fin->isoFormat('dddd D MMMM YYYY')),
            'Type Congé'    => $c->type_conge,
            'Commentaire'   => $c->commentaire ?? '',
        ]);

        // ── Jours fériés / non travaillés ──────────────────────────
        $feriesModels = JourNonTravaille::actif()
            ->periode($dateFrom, $dateTo)
            ->where(fn($q) => $q->whereNull('SiegeID')->orWhere('SiegeID', $siegeId))
            ->orderBy('Date')
            ->get();

        $feriesData = $feriesModels->map(fn($j) => [
            'Date'        => ucfirst($j->Date->isoFormat('dddd D MMMM YYYY')),
            'Nom'         => $j->Nom,
            'Type'        => $j->Type,
            'Description' => $j->Description ?? '',
            'Siège'       => $j->siege?->Nom ?? 'National',
        ]);

        // ── Absences ────────────────────────────────────────────────
        $absencesData = $this->calculateAbsences(
            $rawResults, $congesModels, $feriesModels, $dateFrom, $dateTo
        );

        return [$rapportData, $congesData, $feriesData, $absencesData];
    }

    /**
     * Calcule les jours ouvrables (lun-ven) d'absence pour un employé.
     */
    private function calculateAbsences(
        Collection $rawResults,
        Collection $congesModels,
        Collection $feriesModels,
        string $dateFrom,
        string $dateTo
    ): Collection {
        // Infos employé issues du premier résultat
        $first      = $rawResults->first();
        $nom        = $first?->employee_nom ?? '';
        $matricule  = $first?->num_mat ?? '-';
        $siegeNom   = $first?->siege_nom ?? '';

        // 1. Tous les jours ouvrables (lun-ven) de la période
        $workingDays = collect();
        $cursor = Carbon::parse($dateFrom);
        $end    = Carbon::parse($dateTo);
        while ($cursor->lte($end)) {
            if (!$cursor->isWeekend()) {
                $workingDays->push($cursor->format('Y-m-d'));
            }
            $cursor->addDay();
        }

        // 2. Jours avec pointage (date_reel brute depuis rawResults)
        $pointageDays = $rawResults
            ->pluck('date_reel')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique();

        // 3. Jours couverts par congés (expansion des plages)
        $congeDays = collect();
        foreach ($congesModels as $c) {
            $s = Carbon::parse($c->date_debut);
            $e = Carbon::parse($c->date_fin);
            while ($s->lte($e)) {
                $congeDays->push($s->format('Y-m-d'));
                $s->addDay();
            }
        }
        $congeDays = $congeDays->unique();

        // 4. Jours fériés
        $ferieDays = $feriesModels
            ->pluck('Date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique();

        // 5. Absences = jours ouvrables non couverts
        return $workingDays
            ->filter(fn($day) =>
                !$pointageDays->contains($day) &&
                !$congeDays->contains($day) &&
                !$ferieDays->contains($day)
            )
            ->map(fn($day) => [
                'N° Matricule'  => $matricule,
                'Employé(e) Nom'=> $nom,
                'Siège Nom'     => $siegeNom,
                'Date Absence'  => ucfirst(Carbon::parse($day)->isoFormat('dddd D MMMM YYYY')),
            ])
            ->values();
    }

    /**
     * Génère un ZIP de fichiers Excel multi-onglets (1 par employé).
     */
    private function buildMultiSheetZip(
        Collection $sortedResults,
        ?int $siegeId,
        string $dateFrom,
        string $dateTo,
        bool $isDayNight,
        string $title
    ): \Symfony\Component\HttpFoundation\BinaryFileResponse {
        $zipPath = sys_get_temp_dir() . '/report_' . time() . rand(100, 999) . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($sortedResults->groupBy('employee_id') as $empId => $empRaw) {
            // Reconstruire les données rapport pour cet employé
            $empData = $empRaw->map(fn($r) => $this->mapRapportRow($r, $isDayNight))->values();
            $empSiegeId = $empRaw->first()?->SiegeID ? (int)$empRaw->first()->SiegeID : $siegeId;

            $sheets  = $this->buildEmployeeSheets($empData, $empRaw, (int)$empId, $empSiegeId, $dateFrom, $dateTo, $isDayNight);
            $export  = new ReportMultiSheetExport(...$sheets);
            $content = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

            $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $empRaw->first()?->employee_nom ?? $empId);
            $zip->addFromString($safe . '.xlsx', $content);
        }

        $zip->close();
        return response()->download($zipPath, $title . '_' . Carbon::now()->format('Y-m-d') . '.zip')
            ->deleteFileAfterSend(true);
    }

    /**
     * Génère un ZIP de fichiers PDF multi-sections (1 par employé).
     */
    private function buildMultiSectionPdfZip(
        Collection $sortedResults,
        ?int $siegeId,
        string $dateFrom,
        string $dateTo,
        bool $isDayNight,
        string $title,
        array $filters
    ): \Symfony\Component\HttpFoundation\BinaryFileResponse {
        $zipPath = sys_get_temp_dir() . '/report_pdf_' . time() . rand(100, 999) . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($sortedResults->groupBy('employee_id') as $empId => $empRaw) {
            $empData    = $empRaw->map(fn($r) => $this->mapRapportRow($r, $isDayNight))->values();
            $empSiegeId = $empRaw->first()?->SiegeID ? (int)$empRaw->first()->SiegeID : $siegeId;

            $sheets = $this->buildEmployeeSheets($empData, $empRaw, (int)$empId, $empSiegeId, $dateFrom, $dateTo, $isDayNight);

            $viewData = [
                'title'    => $title,
                'date'     => Carbon::now()->isoFormat('dddd D MMMM YYYY - HH:mm:ss'),
                'user'     => auth()->user()->Identifiant_email,
                'filters'  => $filters,
                'rapport'  => $sheets[0],
                'conges'   => $sheets[1],
                'feries'   => $sheets[2],
                'absences' => $sheets[3],
            ];

            $pdf  = Pdf::loadView('exports.rapport_complet', $viewData)->setPaper('a4', 'landscape');
            $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $empRaw->first()?->employee_nom ?? $empId);
            $zip->addFromString($safe . '.pdf', $pdf->output());
        }

        $zip->close();
        return response()->download($zipPath, $title . '_' . Carbon::now()->format('Y-m-d') . '.zip')
            ->deleteFileAfterSend(true);
    }

    /**
     * Mapper un résultat DB brut en ligne de rapport (réutilisé dans ZIP).
     */
    private function mapRapportRow(object $rapport, bool $isDayNight): array
    {
        $row = [
            'N° Matricule'  => $rapport->num_mat ?? '-',
            'Employé(e) Nom'=> $rapport->employee_nom,
            'Siège Nom'     => $rapport->siege_nom,
            'Date pointage' => ucfirst(Carbon::parse($rapport->date_reel)->isoFormat('dddd D MMMM YYYY')),
        ];
        if ($isDayNight) {
            $row['Type travail'] = $rapport->type_travail;
        }
        $row += [
            'Heure Entrée'    => $rapport->heure_entree,
            'Pause Déjeuner'  => $rapport->pause_dejeuner,
            'Heure Sortie'    => $rapport->heure_sortie,
            'Total Heure'     => $rapport->total_heure_journee,
        ];
        return $row;
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