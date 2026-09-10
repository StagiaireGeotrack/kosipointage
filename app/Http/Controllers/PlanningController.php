<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\Employe;
use App\Models\HoraireType;
use App\Models\EvenementPlanning;
use App\Models\SupervisorService;
use App\Repositories\PlanningRepository;
use App\Traits\EmployeeAccessTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanningController extends Controller
{
    use EmployeeAccessTrait;

    protected $planningRepository;

    public function __construct(PlanningRepository $planningRepository)
    {
        $this->planningRepository = $planningRepository;
    }

    /* =========================================================
       HELPERS SUPERVISOR
       ========================================================= */

    /**
     * Retourne les IDs des services supervisés si l'utilisateur est Supervisor.
     * Retourne null si l'utilisateur n'est PAS Supervisor (= pas de restriction).
     */
    private function getAllowedServiceIds(): ?array
    {
        $user = auth()->user();
        if ($user && $user->isSupervisor()) {
            return SupervisorService::where('admin_id', $user->ID)
                ->pluck('service_id')
                ->map(fn($v) => (int) $v)
                ->toArray();
        }
        return null;
    }

    /**
     * Vérifie qu'un service est accessible par le Supervisor connecté.
     * Ne fait rien pour les autres rôles.
     */
    private function ensureServiceAccessible($serviceId): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSupervisor()) {
            return;
        }
        $allowed = $this->getAllowedServiceIds() ?? [];
        if (!in_array((int) $serviceId, $allowed, true)) {
            abort(403, 'Accès non autorisé à ce service.');
        }
    }

    /**
     * Vérifie qu'un planning est accessible par le Supervisor connecté.
     * Ne fait rien pour les autres rôles.
     */
    private function ensurePlanningAccessible(Planning $planning): void
    {
        $user = auth()->user();
        if (!$user || !$user->isSupervisor()) {
            return;
        }
        $allowed = $this->getAllowedServiceIds() ?? [];
        if (!in_array((int) $planning->service_id, $allowed, true)) {
            abort(403, 'Accès non autorisé à ce planning.');
        }
    }

    /* =========================================================
       INDEX
       ========================================================= */

    /**
     * Page principale du planning (vue table avec services groupés)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $siegeId = session('admin_selected_siege_id') ?? $user->SiegeID;
        $weekOffset = $request->get('week_offset', 0);
        $allowedServiceIds = $this->getAllowedServiceIds();

        // Jours de la semaine
        $days = $this->getWeekDays($weekOffset);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
        $endOfWeek = Carbon::now()->endOfWeek()->addWeeks($weekOffset);

        // Plannings générés pour la semaine
        $planningsQuery = Planning::where('siege_id', $siegeId)
            ->where('date_debut_semaine', '<=', $startOfWeek->format('Y-m-d'))
            ->where('date_fin_semaine', '>=', $endOfWeek->format('Y-m-d'))
            ->where('statut', '!=', 'archive')
            ->with(['details.employe', 'details.employe.jobTitle']);

        if ($allowedServiceIds !== null) {
            $planningsQuery->whereIn('service_id', $allowedServiceIds);
        }
        $plannings = $planningsQuery->get();

        // Détails de planning pour la semaine
        $planningDetails = PlanningDetail::whereHas('planning', function ($q) use ($siegeId, $startOfWeek, $endOfWeek, $allowedServiceIds) {
            $q->where('siege_id', $siegeId)
              ->where('date_debut_semaine', '<=', $startOfWeek->format('Y-m-d'))
              ->where('date_fin_semaine', '>=', $endOfWeek->format('Y-m-d'))
              ->where('statut', '!=', 'archive');

            if ($allowedServiceIds !== null) {
                $q->whereIn('service_id', $allowedServiceIds);
            }
        })
        ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
        ->with(['employe', 'employe.jobTitle'])
        ->get();

        // Filtre employés accessibles pour Supervisor
        if ($allowedServiceIds !== null) {
            $accessibleEmployeeIds = $this->getAccessibleEmployeeIds($user);
            $planningDetails = $planningDetails->filter(function ($detail) use ($accessibleEmployeeIds) {
                return in_array($detail->employe_id, $accessibleEmployeeIds, true);
            })->values();
        }

        // Congés approuvés (nouveau système)
        $congesQuery = \App\Models\LeaveRequest::where('status', 'approved')
            ->where(function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('start_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhere(function ($q2) use ($startOfWeek, $endOfWeek) {
                      $q2->where('start_date', '<=', $startOfWeek->format('Y-m-d'))
                         ->where('end_date', '>=', $endOfWeek->format('Y-m-d'));
                  });
            })
            ->with(['employee', 'leaveType']);

        if ($allowedServiceIds !== null) {
            $accessibleEmployeeIds = $this->getAccessibleEmployeeIds($user);
            $congesQuery->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $conges = $congesQuery->get();

        // Congés ancien système
        $congesAnciensQuery = \App\Models\Conge::where(function ($q) use ($startOfWeek, $endOfWeek) {
            $q->whereBetween('date_debut', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
              ->orWhereBetween('date_fin', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
              ->orWhere(function ($q2) use ($startOfWeek, $endOfWeek) {
                  $q2->where('date_debut', '<=', $startOfWeek->format('Y-m-d'))
                     ->where('date_fin', '>=', $endOfWeek->format('Y-m-d'));
              });
        });

        if ($allowedServiceIds !== null) {
            $accessibleEmployeeIds = $this->getAccessibleEmployeeIds($user);
            $congesAnciensQuery->whereIn('employee_id', $accessibleEmployeeIds);
        }
        $congesAnciens = $congesAnciensQuery->get();

        // Grouper les détails par employé et par date
        $scheduleByEmployee = [];
        foreach ($planningDetails as $detail) {
            $employeId = $detail->employe_id;
            $dateKey = Carbon::parse($detail->date)->format('d/m');

            if (!isset($scheduleByEmployee[$employeId])) {
                $scheduleByEmployee[$employeId] = [];
            }

            $items = [];
            if ($detail->heure_debut && $detail->heure_fin) {
                $items[] = ['type' => 'work', 'time' => $detail->heure_debut . ' - ' . $detail->heure_fin, 'id' => $detail->id];
            }
            if ($detail->pause_debut && $detail->pause_fin) {
                $items[] = ['type' => 'pause', 'time' => $detail->pause_debut . ' - ' . $detail->pause_fin, 'id' => $detail->id];
            }
            if ($detail->deuxieme_debut && $detail->deuxieme_fin) {
                $items[] = ['type' => 'work', 'time' => $detail->deuxieme_debut . ' - ' . $detail->deuxieme_fin, 'id' => $detail->id];
            }

            $scheduleByEmployee[$employeId][$dateKey] = $items;
        }

        // Index des congés
        $congesByEmployee = [];
        foreach ($conges as $conge) {
            $employeId = $conge->employee_id;
            $start = Carbon::parse($conge->start_date);
            $end = Carbon::parse($conge->end_date);
            $current = $start->copy();
            while ($current <= $end) {
                $dateKey = $current->format('d/m');
                if (!isset($congesByEmployee[$employeId][$dateKey])) {
                    $congesByEmployee[$employeId][$dateKey] = [];
                }
                $congesByEmployee[$employeId][$dateKey][] = [
                    'type' => $this->getCongeType($conge->leaveType?->name ?? 'Congé'),
                    'title' => $conge->leaveType?->name ?? 'Congé',
                    'sub' => 'Toute la journée',
                ];
                $current->addDay();
            }
        }
        foreach ($congesAnciens as $conge) {
            $employeId = $conge->employee_id;
            $start = Carbon::parse($conge->date_debut);
            $end = Carbon::parse($conge->date_fin);
            $current = $start->copy();
            while ($current <= $end) {
                $dateKey = $current->format('d/m');
                if (!isset($congesByEmployee[$employeId][$dateKey])) {
                    $congesByEmployee[$employeId][$dateKey] = [];
                }
                $congesByEmployee[$employeId][$dateKey][] = [
                    'type' => $this->getCongeType($conge->type_conge ?? 'Congé'),
                    'title' => $conge->type_conge ?? 'Congé',
                    'sub' => 'Toute la journée',
                ];
                $current->addDay();
            }
        }

        // Services du siège avec employés
        $servicesQuery = Department::where('site_id', $siegeId);

        if ($allowedServiceIds !== null) {
            $servicesQuery->whereIn('id', $allowedServiceIds);
        }

        $services = $servicesQuery
            ->with([
                'employes' => function ($q) {
                    $q->where('Actived', 1)->where('deleted', 0);
                },
                'employes.jobTitle',
            ])
            ->get();

        // Structure des données pour la vue
        $servicesData = [];
        foreach ($services as $service) {
            $employeesData = [];
            foreach ($service->employes as $employe) {
                $initiales = strtoupper(substr($employe->Nom ?? '', 0, 1));
                $schedule = [];

                foreach ($days as $day) {
                    $dateKey = $day['date'];
                    $dateFull = $day['full'] ?? null;

                    if (isset($congesByEmployee[$employe->ID][$dateKey])) {
                        $schedule[$dateKey] = $congesByEmployee[$employe->ID][$dateKey];
                        continue;
                    }

                    $evenement = EvenementPlanning::whereHas('employes', function ($q) use ($employe) {
                        $q->where('employe_id', $employe->ID);
                    })
                    ->whereDate('debut', '<=', Carbon::parse($dateFull))
                    ->whereDate('fin', '>=', Carbon::parse($dateFull))
                    ->first();

                    if ($evenement) {
                        $schedule[$dateKey] = [[
                            'type' => $evenement->type,
                            'title' => $evenement->titre,
                            'sub' => $evenement->debut->format('H:i') . ' - ' . $evenement->fin->format('H:i'),
                            'id' => $evenement->id,
                        ]];
                        continue;
                    }

                    if (isset($scheduleByEmployee[$employe->ID][$dateKey])) {
                        $schedule[$dateKey] = $scheduleByEmployee[$employe->ID][$dateKey];
                    } else {
                        $horaireType = HoraireType::where('poste_id', $employe->job_title_id)->first();
                        $isWorkDay = false;

                        if ($horaireType && $horaireType->jours_travailles && $dateFull) {
                            $jourFrancais = strtolower(Carbon::parse($dateFull)->format('l'));
                            $joursTravailles = explode(',', $horaireType->jours_travailles);
                            $isWorkDay = in_array($jourFrancais, $joursTravailles);
                        }

                        if (!$isWorkDay) {
                            $schedule[$dateKey] = [['type' => 'rest']];
                        } else {
                            if ($horaireType) {
                                $items = [];
                                if ($horaireType->heure_debut && $horaireType->heure_fin) {
                                    $items[] = ['type' => 'work', 'time' => $horaireType->heure_debut . ' - ' . $horaireType->heure_fin];
                                }
                                if ($horaireType->pause_debut && $horaireType->pause_fin) {
                                    $items[] = ['type' => 'pause', 'time' => $horaireType->pause_debut . ' - ' . $horaireType->pause_fin];
                                }
                                if ($horaireType->deuxieme_debut && $horaireType->deuxieme_fin) {
                                    $items[] = ['type' => 'work', 'time' => $horaireType->deuxieme_debut . ' - ' . $horaireType->deuxieme_fin];
                                }
                                $schedule[$dateKey] = $items;
                            } else {
                                $schedule[$dateKey] = [['type' => 'rest']];
                            }
                        }
                    }
                }

                $employeesData[] = [
                    'id' => $employe->ID,
                    'name' => $employe->Nom,
                    'role' => $employe->jobTitle?->name ?? 'N/A',
                    'avatar' => $this->getAvatarUrl($employe),
                    'initiales' => $initiales,
                    'color' => $this->getColorForEmployee($employe->ID),
                    'schedule' => $schedule,
                ];
            }

            if (count($employeesData) > 0) {
                $servicesData[] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'count' => count($employeesData),
                    'employees' => $employeesData,
                ];
            }
        }

        return view('planning.index', compact('servicesData', 'days'));
    }

    /* =========================================================
       CONGÉ TYPE
       ========================================================= */

    private function getCongeType($type)
    {
        $types = [
            'RTT' => 'rtt',
            'Maladie' => 'maladie',
            'Absence autorisée' => 'absence',
            'Congé exceptionnel' => 'conge',
            'Congé payé' => 'conge',
        ];
        return $types[$type] ?? 'conge';
    }

    /* =========================================================
       CREATE
       ========================================================= */

    public function create()
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $services = Department::where('site_id', $siegeId)->orderBy('name')->get();
        return view('planning.create', compact('services'));
    }

    /* =========================================================
       STORE
       ========================================================= */

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:departments,id',
            'poste_id' => 'required|exists:job_titles,id',
            'horaire_type_id' => 'required|exists:horaires_types,id',
            'employe_ids' => 'required|array|min:1',
            'employe_ids.*' => 'exists:Employes,ID',
            'date_debut' => 'required|date',
        ]);

        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = $dateDebut->copy()->endOfWeek();
        $horaireType = HoraireType::findOrFail($request->horaire_type_id);
        $joursTravailles = explode(',', $horaireType->jours_travailles ?? 'lundi,mardi,mercredi,jeudi,vendredi');

        $planning = Planning::create([
            'siege_id' => $siegeId,
            'service_id' => $request->service_id,
            'poste_id' => $request->poste_id,
            'date_debut_semaine' => $dateDebut->format('Y-m-d'),
            'date_fin_semaine' => $dateFin->format('Y-m-d'),
            'nom' => 'Planning ' . $dateDebut->format('d/m/Y'),
            'statut' => 'genere',
            'cree_par' => auth()->id(),
        ]);

        $totalCreneaux = 0;
        foreach ($request->employe_ids as $employeId) {
            $current = $dateDebut->copy();
            while ($current <= $dateFin) {
                $jourFrancais = strtolower($current->format('l'));
                $jourMapping = [
                    'monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi',
                    'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi', 'sunday' => 'dimanche',
                ];
                $jourKey = $jourMapping[$jourFrancais] ?? $jourFrancais;

                if (in_array($jourKey, $joursTravailles)) {
                    PlanningDetail::create([
                        'planning_id' => $planning->id,
                        'employe_id' => $employeId,
                        'date' => $current->format('Y-m-d'),
                        'heure_debut' => $horaireType->heure_debut,
                        'heure_fin' => $horaireType->heure_fin,
                        'pause_debut' => $horaireType->pause_debut,
                        'pause_fin' => $horaireType->pause_fin,
                        'deuxieme_debut' => $horaireType->deuxieme_debut,
                        'deuxieme_fin' => $horaireType->deuxieme_fin,
                        'statut' => 'planifie',
                    ]);
                    $totalCreneaux++;
                }
                $current->addDay();
            }
        }

        return redirect()->route('planning.index')
            ->with('success', 'Planning généré avec succès pour ' . count($request->employe_ids) . ' employé(s) et ' . $totalCreneaux . ' créneaux !');
    }

    /* =========================================================
       SHOW
       ========================================================= */

    public function show($id)
    {
        $planning = Planning::with(['siege', 'service', 'poste', 'createur', 'details.employe'])->findOrFail($id);
        $this->ensurePlanningAccessible($planning);
        return view('planning.show', compact('planning'));
    }

    /* =========================================================
       GET EVENTS (AJAX)
       ========================================================= */

    public function getEvents(Request $request)
    {
        $user = auth()->user();
        $siegeId = session('admin_selected_siege_id') ?? $user->SiegeID;
        $start = $request->input('start');
        $end = $request->input('end');
        $serviceId = $request->input('service_id');
        $posteId = $request->input('poste_id');
        $allowedServiceIds = $this->getAllowedServiceIds();

        $events = [];

        // 1. Plannings
        $planningDetails = PlanningDetail::with(['employe', 'planning'])
            ->whereHas('planning', function ($q) use ($siegeId, $serviceId, $posteId, $allowedServiceIds) {
                $q->where('siege_id', $siegeId)->where('statut', '!=', 'archive');
                if ($serviceId) $q->where('service_id', $serviceId);
                if ($posteId) $q->where('poste_id', $posteId);
                if ($allowedServiceIds !== null) {
                    $q->whereIn('service_id', $allowedServiceIds);
                }
            })
            ->whereBetween('date', [$start, $end])
            ->get();

        if ($allowedServiceIds !== null) {
            $accessibleEmployeeIds = $this->getAccessibleEmployeeIds($user);
            $planningDetails = $planningDetails->filter(function ($detail) use ($accessibleEmployeeIds) {
                return in_array($detail->employe_id, $accessibleEmployeeIds, true);
            })->values();
        }

        foreach ($planningDetails as $detail) {
            $employe = $detail->employe;
            $initiales = $employe ? strtoupper(substr($employe->Nom ?? '', 0, 1)) : '?';

            $events[] = [
                'id' => 'detail_' . $detail->id,
                'title' => $initiales . ' ' . $detail->heure_debut . '-' . $detail->heure_fin,
                'start' => $detail->date . 'T' . $detail->heure_debut,
                'end' => $detail->date . 'T' . $detail->heure_fin,
                'backgroundColor' => $this->getStatutColor($detail->statut),
                'borderColor' => $this->getStatutColor($detail->statut),
                'extendedProps' => [
                    'type' => 'planning',
                    'employe' => $employe ? $employe->Nom : 'N/A',
                    'employe_id' => $detail->employe_id,
                    'initiales' => $initiales,
                    'statut' => $detail->statut,
                    'commentaire' => $detail->commentaire,
                    'detail_id' => $detail->id,
                    'planning_id' => $detail->planning_id,
                ]
            ];
        }

        // 2. Événements exceptionnels
        $evenementsQuery = EvenementPlanning::where('siege_id', $siegeId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('debut', [$start, $end])
                  ->orWhereBetween('fin', [$start, $end]);
            });

        if ($allowedServiceIds !== null) {
            $evenementsQuery->where(function ($q) use ($allowedServiceIds) {
                $q->whereIn('service_id', $allowedServiceIds)
                  ->orWhereNull('service_id');
            });
        }

        $evenements = $evenementsQuery->get();

        foreach ($evenements as $evenement) {
            $events[] = [
                'id' => 'event_' . $evenement->id,
                'title' => '📌 ' . $evenement->titre,
                'start' => $evenement->debut->format('Y-m-d\TH:i:s'),
                'end' => $evenement->fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $this->getEventColor($evenement->type),
                'borderColor' => $this->getEventColor($evenement->type),
                'extendedProps' => [
                    'type' => 'evenement',
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'type_event' => $evenement->type,
                    'evenement_id' => $evenement->id,
                ]
            ];
        }

        return response()->json($events);
    }

    /* =========================================================
       GET EVENT DETAIL (AJAX)
       ========================================================= */

    public function getEventDetail($id, $type)
    {
        $user = auth()->user();
        $data = null;

        if ($type === 'planning') {
            $detail = PlanningDetail::with(['employe', 'planning'])->find($id);
            if ($detail) {
                if ($user->isSupervisor()) {
                    $accessibleIds = $this->getAccessibleEmployeeIds($user);
                    if (!in_array($detail->employe_id, $accessibleIds, true)) {
                        abort(403, 'Accès non autorisé.');
                    }
                }

                $data = [
                    'type' => 'planning',
                    'employe' => $detail->employe?->Nom,
                    'role' => $detail->employe?->jobTitle?->name ?? 'N/A',
                    'date' => $detail->date->format('d/m/Y'),
                    'heure_debut' => $detail->heure_debut,
                    'heure_fin' => $detail->heure_fin,
                    'pause_debut' => $detail->pause_debut,
                    'pause_fin' => $detail->pause_fin,
                    'commentaire' => $detail->commentaire,
                    'statut' => $detail->statut,
                    'avatar' => $this->getAvatarUrl($detail->employe),
                ];
            }
        } elseif ($type === 'evenement') {
            $evenement = EvenementPlanning::with('employes')->find($id);
            if ($evenement) {
                if ($user->isSupervisor()) {
                    $allowed = $this->getAllowedServiceIds() ?? [];
                    if ($evenement->service_id !== null && !in_array((int) $evenement->service_id, $allowed, true)) {
                        abort(403, 'Accès non autorisé.');
                    }
                }

                $data = [
                    'type' => 'evenement',
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'type_event' => $evenement->type,
                    'debut' => $evenement->debut->format('d/m/Y H:i'),
                    'fin' => $evenement->fin->format('d/m/Y H:i'),
                    'employes' => $evenement->employes->map(function ($e) {
                        return $e->Nom;
                    })->implode(', '),
                ];
            }
        }

        return response()->json($data);
    }

    /* =========================================================
       GET JOB TITLES BY DEPARTMENT (AJAX)
       ========================================================= */

    public function getJobTitlesByDepartment($departmentId)
    {
        $departmentId = (int) $departmentId;
        $this->ensureServiceAccessible($departmentId);

        $postes = JobTitle::where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($postes);
    }

    /* =========================================================
       GET EMPLOYEES BY SERVICE (AJAX)
       ========================================================= */

    public function getEmployeesByService($serviceId)
    {
        try {
            $user = auth()->user();
            $siegeId = session('admin_selected_siege_id') ?? $user->SiegeID;

            if (!$siegeId || !$serviceId) {
                return response()->json([]);
            }

            $this->ensureServiceAccessible($serviceId);

            $query = Employe::where('SiegeID', $siegeId)
                ->where('department_id', $serviceId)
                ->where('Actived', 1)
                ->where('deleted', 0);

            if ($user->isSupervisor()) {
                $accessibleIds = $this->getAccessibleEmployeeIds($user);
                $query->whereIn('ID', $accessibleIds);
            }

            $employes = $query->orderBy('Nom')->get(['ID', 'Nom', 'job_title_id']);

            return response()->json($employes);
        } catch (\Exception $e) {
            \Log::error('getEmployeesByService - Erreur:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================================================
       GET WORK SCHEDULES BY JOB TITLE (AJAX)
       ========================================================= */

    public function getWorkSchedulesByJobTitle($jobTitleId)
    {
        try {
            $horaires = HoraireType::where('poste_id', $jobTitleId)->orderBy('id')->get();

            \Illuminate\Support\Facades\Log::info('getWorkSchedulesByJobTitle - poste_id: ' . $jobTitleId . ', count: ' . $horaires->count());

            $result = $horaires->map(function ($horaire) {
                $horaireLabel = $horaire->heure_debut . ' - ' . $horaire->heure_fin;
                if ($horaire->deuxieme_debut && $horaire->deuxieme_fin) {
                    $horaireLabel .= ' / ' . $horaire->deuxieme_debut . ' - ' . $horaire->deuxieme_fin;
                }
                $pauseLabel = 'Aucune pause';
                if ($horaire->pause_debut && $horaire->pause_fin) {
                    $pauseLabel = $horaire->pause_debut . ' - ' . $horaire->pause_fin;
                }
                $jours = $horaire->jours_travailles ? explode(',', $horaire->jours_travailles) : [];
                $joursLibelles = array_map(fn($j) => ucfirst($j), $jours);

                return [
                    'id' => $horaire->id,
                    'poste_id' => $horaire->poste_id,
                    'jours_travailles' => $horaire->jours_travailles,
                    'heure_debut' => $horaire->heure_debut,
                    'heure_fin' => $horaire->heure_fin,
                    'pause_debut' => $horaire->pause_debut,
                    'pause_fin' => $horaire->pause_fin,
                    'deuxieme_debut' => $horaire->deuxieme_debut,
                    'deuxieme_fin' => $horaire->deuxieme_fin,
                    'par_defaut' => (bool) $horaire->par_defaut,
                    'jour_libelle' => implode(', ', $joursLibelles),
                    'libelle_horaire' => $horaireLabel,
                    'libelle_pause' => $pauseLabel,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Erreur getWorkSchedulesByJobTitle: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* =========================================================
       ✅ FONCTION OUBLIÉE — generateEmployeeSchedule
       ========================================================= */

    /**
     * Générer le planning d'un employé pour la semaine
     */
    private function generateEmployeeSchedule($employe, $horaireType)
    {
        $schedule = [];
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $current = $startOfWeek->copy();
        while ($current <= $endOfWeek) {
            $dateKey = $current->format('d/m');
            $dayOfWeek = strtolower($current->format('l'));

            // Vérifier si le jour est travaillé
            $isWorkDay = false;
            $jourTravailles = [];

            if ($horaireType && $horaireType->jours_travailles) {
                $jourTravailles = explode(',', $horaireType->jours_travailles);
                $isWorkDay = in_array($dayOfWeek, $jourTravailles);
            }

            // Vérifier les congés (nouveau système)
            $leaveRequest = null;
            if (method_exists($employe, 'leaveRequests')) {
                $leaveRequest = $employe->leaveRequests()
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $current)
                    ->whereDate('end_date', '>=', $current)
                    ->first();
            }

            // Vérifier les congés (ancien système) si pas trouvé
            if (!$leaveRequest && method_exists($employe, 'conges')) {
                $leaveRequest = $employe->conges()
                    ->whereDate('date_debut', '<=', $current)
                    ->whereDate('date_fin', '>=', $current)
                    ->first();
            }

            // Vérifier les événements exceptionnels
            $evenement = null;
            if (class_exists(EvenementPlanning::class)) {
                $evenement = EvenementPlanning::whereHas('employes', function ($q) use ($employe) {
                        $q->where('employe_id', $employe->ID);
                    })
                    ->whereDate('debut', '<=', $current)
                    ->whereDate('fin', '>=', $current)
                    ->first();
            }

            if ($leaveRequest) {
                // Congé
                $type = 'conge';
                $title = 'Congé';
                if (method_exists($leaveRequest, 'leaveType') && $leaveRequest->leaveType) {
                    $title = $leaveRequest->leaveType->name ?? 'Congé';
                    if ($title === 'RTT') $type = 'rtt';
                    elseif ($title === 'Maladie') $type = 'maladie';
                }
                $schedule[$dateKey] = [[
                    'type' => $type,
                    'title' => $title,
                    'sub' => 'Toute la journée',
                ]];
            } elseif ($evenement) {
                // Événement exceptionnel
                $schedule[$dateKey] = [[
                    'type' => $evenement->type,
                    'title' => $evenement->titre,
                    'sub' => $this->formatEventTime($evenement),
                ]];
            } elseif ($isWorkDay && $horaireType) {
                // Jour travaillé normal
                $items = [];
                $items[] = [
                    'type' => 'work',
                    'time' => $horaireType->heure_debut . ' - ' . $horaireType->heure_fin,
                ];
                if ($horaireType->pause_debut && $horaireType->pause_fin) {
                    $items[] = [
                        'type' => 'pause',
                        'time' => $horaireType->pause_debut . ' - ' . $horaireType->pause_fin,
                    ];
                }
                if ($horaireType->deuxieme_debut && $horaireType->deuxieme_fin) {
                    $items[] = [
                        'type' => 'work',
                        'time' => $horaireType->deuxieme_debut . ' - ' . $horaireType->deuxieme_fin,
                    ];
                }
                $schedule[$dateKey] = $items;
            } else {
                // Repos
                $schedule[$dateKey] = [[
                    'type' => 'rest',
                ]];
            }

            $current->addDay();
        }

        return $schedule;
    }

    /* =========================================================
       HELPERS PRIVÉS (existants)
       ========================================================= */

    /**
     * Récupérer les jours de la semaine
     */
    private function getWeekDays($offset = 0)
    {
        $days = [];
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($offset);

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $days[] = [
                'short' => substr($date->format('D'), 0, 3) . '.',
                'date' => $date->format('d/m'),
                'full' => $date->format('Y-m-d'),
            ];
        }

        return $days;
    }

    /**
     * Formater l'heure d'un événement
     */
    private function formatEventTime($evenement)
    {
        if ($evenement->toute_la_journee) {
            return 'Toute la journée';
        }
        return $evenement->debut->format('H:i') . ' - ' . $evenement->fin->format('H:i');
    }

    /**
     * Obtenir l'URL de l'avatar
     */
    private function getAvatarUrl($employe)
    {
        if ($employe && $employe->FaceEncodingPath) {
            return route('employes.face', $employe->ID);
        }
        return null;
    }

    /**
     * Obtenir une couleur pour un employé
     */
    private function getColorForEmployee($id)
    {
        $colors = ['#3B82F6', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'];
        return $colors[$id % count($colors)];
    }

    /**
     * Obtenir la couleur du statut
     */
    private function getStatutColor($statut)
    {
        $colors = [
            'planifie' => '#3B82F6',
            'confirme' => '#22C55E',
            'effectue' => '#8B5CF6',
            'annule' => '#EF4444',
            'absent' => '#F59E0B',
        ];
        return $colors[$statut] ?? '#6B7280';
    }

    /**
     * Obtenir la couleur du type d'événement
     */
    private function getEventColor($type)
    {
        $colors = [
            'formation' => '#8B5CF6',
            'deplacement' => '#3B82F6',
            'reunion' => '#F59E0B',
            'conges_exceptionnel' => '#EF4444',
            'autre' => '#6B7280',
        ];
        return $colors[$type] ?? '#6B7280';
    }

    /* =========================================================
       STORE EVENT
       ========================================================= */

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

    /* =========================================================
       EXPORTS
       ========================================================= */

    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Exportation Excel en cours de traitement.');
    }

    public function exportPdf(Request $request)
    {
        return back()->with('info', 'Exportation PDF en cours de traitement.');
    }

    /* =========================================================
       CALENDAR
       ========================================================= */

    public function calendar(Request $request)
    {
        return $this->index($request);
    }
}