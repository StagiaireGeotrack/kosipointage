<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\HoraireType;
use App\Models\EvenementPlanning;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Repositories\PlanningRepository;
use App\Services\PlanningService;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanningController extends Controller
{
    protected $planningRepository;
    protected $planningService;
    protected $exportService;

    public function __construct(
        PlanningRepository $planningRepository,
        PlanningService $planningService,
        ExportService $exportService
    ) {
        $this->planningRepository = $planningRepository;
        $this->planningService = $planningService;
        $this->exportService = $exportService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'siege_id', 'service_id', 'poste_id', 'statut', 'semaine_debut', 'semaine_fin'
        ]);

        if (!auth()->user()->IsSuperAdmin) {
            $filters['siege_id'] = auth()->user()->SiegeID;
        }

        $plannings = $this->planningRepository->getFiltered($filters, 10);

        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $sieges = EntrepriseSiege::all();
        $services = Department::where('site_id', $siegeId)->get();
        $postes = JobTitle::where('company_id', $siegeId)->get();
        
        // ✅ Récupérer les employés pour le modal
        $employes = Employe::where('SiegeID', $siegeId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        return view('planning.index', compact('plannings', 'sieges', 'services', 'postes', 'employes', 'filters'));
    }

    public function calendar(Request $request)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $serviceId = $request->input('service_id');
        $posteId = $request->input('poste_id');

        $services = Department::where('site_id', $siegeId)->get();
        $postes = JobTitle::where('company_id', $siegeId)->get();

        // ✅ AJOUT : Récupérer les employés pour le modal
        $employes = Employe::where('SiegeID', $siegeId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        return view('planning.calendar', compact('services', 'postes', 'employes', 'siegeId'));
    }

    public function create()
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $services = Department::where('site_id', $siegeId)->orderBy('name')->get();
        $postes = JobTitle::where('company_id', $siegeId)->orderBy('name')->get();
        $employes = Employe::where('SiegeID', $siegeId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        return view('planning.create', compact('services', 'postes', 'employes', 'siegeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:departments,id',
            'poste_id' => 'required|exists:job_titles,id',
            'employe_ids' => 'required|array|min:1',
            'employe_ids.*' => 'exists:Employes,ID',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'jours_travail' => 'required|array|min:1',
        ]);

        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $dateDebut = Carbon::parse($request->date_debut)->startOfWeek();
        $dateFin = Carbon::parse($request->date_fin)->endOfWeek();

        $planning = $this->planningService->genererPlanning(
            $siegeId,
            $request->service_id,
            $request->poste_id,
            $request->employe_ids,
            $dateDebut->format('Y-m-d'),
            $dateFin->format('Y-m-d'),
            $request->jours_travail,
            auth()->id()
        );

        return redirect()->route('planning.show', $planning->id)
            ->with('success', 'Planning généré avec succès !');
    }

    public function show($id)
    {
        $planning = Planning::with([
            'details.employe',
            'details.comparaison',
            'siege',
            'service',
            'poste',
            'createur',
            'validateur'
        ])->findOrFail($id);

        return view('planning.show', compact('planning'));
    }

    public function getEvents(Request $request)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $start = $request->input('start');
        $end = $request->input('end');
        $serviceId = $request->input('service_id');
        $posteId = $request->input('poste_id');

        $events = [];

        // Plannings
        $planningDetails = PlanningDetail::with(['employe', 'planning'])
            ->whereHas('planning', function ($q) use ($siegeId, $serviceId, $posteId) {
                $q->where('siege_id', $siegeId)
                  ->where('statut', '!=', 'archive');
                if ($serviceId) $q->where('service_id', $serviceId);
                if ($posteId) $q->where('poste_id', $posteId);
            })
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($planningDetails as $detail) {
            $employe = $detail->employe;
            $initiales = $employe ? strtoupper(substr($employe->Nom ?? '', 0, 1) . substr($employe->Prenom ?? '', 0, 1)) : '?';

            $events[] = [
                'id' => 'detail_' . $detail->id,
                'title' => $initiales . ' ' . $detail->heure_debut . '-' . $detail->heure_fin,
                'start' => $detail->date . 'T' . $detail->heure_debut,
                'end' => $detail->date . 'T' . $detail->heure_fin,
                'backgroundColor' => $detail->couleur_statut,
                'borderColor' => $detail->couleur_statut,
                'extendedProps' => [
                    'type' => 'planning',
                    'employe' => $employe ? $employe->Nom . ' ' . ($employe->Prenom ?? '') : 'N/A',
                    'employe_id' => $detail->employe_id,
                    'initiales' => $initiales,
                    'statut' => $detail->statut,
                    'commentaire' => $detail->commentaire,
                    'detail_id' => $detail->id,
                    'planning_id' => $detail->planning_id,
                ]
            ];
        }

        // Événements exceptionnels
        $evenements = EvenementPlanning::where('siege_id', $siegeId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('debut', [$start, $end])
                  ->orWhereBetween('fin', [$start, $end]);
            })
            ->get();

        foreach ($evenements as $evenement) {
            $events[] = [
                'id' => 'event_' . $evenement->id,
                'title' => '📌 ' . $evenement->titre,
                'start' => $evenement->debut->format('Y-m-d\TH:i:s'),
                'end' => $evenement->fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $evenement->couleur_evenement,
                'borderColor' => $evenement->couleur_evenement,
                'extendedProps' => [
                    'type' => 'event',
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'type_event' => $evenement->type,
                    'evenement_id' => $evenement->id,
                ]
            ];
        }

        return response()->json($events);
    }

    public function getEmployeesByService($serviceId)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $employes = Employe::where('SiegeID', $siegeId)
            ->where('department_id', $serviceId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get(['ID', 'Nom', 'Prenom']);

        return response()->json($employes);
    }

    public function getJobTitlesByDepartment($departmentId)
    {
        $postes = JobTitle::where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($postes);
    }

    public function getWorkSchedulesByJobTitle($jobTitleId)
    {
        $horaires = HoraireType::where('poste_id', $jobTitleId)->get();

        return response()->json($horaires);
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'type' => 'required|in:formation,deplacement,reunion,conges_exceptionnel,autre',
            'debut' => 'required|date',
            'fin' => 'required|date|after:debut',
            'employe_ids' => 'nullable|array',
            'employe_ids.*' => 'exists:Employes,ID',
            'service_id' => 'nullable|exists:departments,id',
            'poste_id' => 'nullable|exists:job_titles,id',
        ]);

        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $evenement = EvenementPlanning::create([
            'siege_id' => $siegeId,
            'service_id' => $request->service_id,
            'poste_id' => $request->poste_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'type' => $request->type,
            'debut' => $request->debut,
            'fin' => $request->fin,
            'toute_la_journee' => $request->toute_la_journee ?? false,
            'couleur' => $request->couleur,
            'cree_par' => auth()->id(),
        ]);

        if ($request->filled('employe_ids')) {
            $evenement->employes()->attach($request->employe_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Événement ajouté avec succès',
            'event' => $evenement
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['siege_id', 'service_id', 'poste_id', 'statut']);
        $plannings = $this->planningRepository->getFiltered($filters, 1000)->get();

        return $this->exportService->exportToExcel($plannings, 'Plannings');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['siege_id', 'service_id', 'poste_id', 'statut']);
        $plannings = $this->planningRepository->getFiltered($filters, 1000)->get();

        return $this->exportService->exportToPdf($plannings, 'Liste des plannings', 'exports.generic');
    }
}
