<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\PlanningDetail;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\Employe;
use App\Models\HoraireType;
use App\Models\EvenementPlanning;
use App\Repositories\PlanningRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanningController extends Controller
{
    protected $planningRepository;

    public function __construct(PlanningRepository $planningRepository)
    {
        $this->planningRepository = $planningRepository;
    }

    /**
     * Page principale du planning
     */
    public function index(Request $request)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $view = $request->get('view', 'week');
        if (!in_array($view, ['day', 'week', 'month'])) {
            $view = 'week';
        }

        $pivotDate = $request->get('date', Carbon::now()->format('Y-m-d'));
        try {
            $pivot = Carbon::parse($pivotDate);
        } catch (\Exception $e) {
            $pivot = Carbon::now();
        }

        switch ($view) {
            case 'day':
                $start = $pivot->copy()->startOfDay();
                $end = $pivot->copy()->endOfDay();
                break;
            case 'month':
                $start = $pivot->copy()->startOfMonth();
                $end = $pivot->copy()->endOfMonth();
                break;
            case 'week':
            default:
                $start = $pivot->copy()->startOfWeek(Carbon::MONDAY);
                $end = $pivot->copy()->endOfWeek(Carbon::SUNDAY);
                break;
        }

        $days = $this->getDaysForView($view, $start, $end);

        $prevDate = $this->getNavigationDate($view, $pivot, -1);
        $nextDate = $this->getNavigationDate($view, $pivot, 1);
        $todayDate = Carbon::now()->format('Y-m-d');
        $periodLabel = $this->getPeriodLabel($view, $start, $end, $pivot);

        // Plannings générés
        $plannings = Planning::where('siege_id', $siegeId)
            ->where('date_debut_semaine', '<=', $end->format('Y-m-d'))
            ->where('date_fin_semaine', '>=', $start->format('Y-m-d'))
            ->where('statut', '!=', 'archive')
            ->with(['details.employe', 'details.employe.jobTitle'])
            ->get();

        // Détails plannings
        $planningDetails = PlanningDetail::whereHas('planning', function($q) use ($siegeId, $start, $end) {
            $q->where('siege_id', $siegeId)
              ->where('date_debut_semaine', '<=', $end->format('Y-m-d'))
              ->where('date_fin_semaine', '>=', $start->format('Y-m-d'))
              ->where('statut', '!=', 'archive');
        })
        ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
        ->with(['employe', 'employe.jobTitle'])
        ->get();

        // Congés approuvés
        $conges = \App\Models\LeaveRequest::where('status', 'approved')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start->format('Y-m-d'))
                         ->where('end_date', '>=', $end->format('Y-m-d'));
                  });
            })
            ->with(['employee', 'leaveType'])
            ->get();

        // Congés anciens
        $congesAnciens = \App\Models\Conge::where(function($q) use ($start, $end) {
            $q->whereBetween('date_debut', [$start->format('Y-m-d'), $end->format('Y-m-d')])
              ->orWhereBetween('date_fin', [$start->format('Y-m-d'), $end->format('Y-m-d')])
              ->orWhere(function($q2) use ($start, $end) {
                  $q2->where('date_debut', '<=', $start->format('Y-m-d'))
                     ->where('date_fin', '>=', $end->format('Y-m-d'));
              });
        })->get();

        // Grouper les détails par employé et par date (clé: Y-m-d)
        $scheduleByEmployee = [];
        foreach ($planningDetails as $detail) {
            $employeId = $detail->employe_id;
            $dateFull = Carbon::parse($detail->date)->format('Y-m-d');

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

            $scheduleByEmployee[$employeId][$dateFull] = $items;
        }

        // Index des congés par employé et par date (clé: Y-m-d)
        $congesByEmployee = [];
        foreach ($conges as $conge) {
            $employeId = $conge->employee_id;
            $cStart = Carbon::parse($conge->start_date);
            $cEnd = Carbon::parse($conge->end_date);

            $current = $cStart->copy();
            while ($current <= $cEnd) {
                $dateFull = $current->format('Y-m-d');
                if (!isset($congesByEmployee[$employeId][$dateFull])) {
                    $congesByEmployee[$employeId][$dateFull] = [];
                }
                $congesByEmployee[$employeId][$dateFull][] = [
                    'type' => $this->getCongeType($conge->leaveType?->name ?? 'Congé'),
                    'title' => $conge->leaveType?->name ?? 'Congé',
                    'sub' => 'Toute la journée',
                ];
                $current->addDay();
            }
        }

        foreach ($congesAnciens as $conge) {
            $employeId = $conge->employee_id;
            $cStart = Carbon::parse($conge->date_debut);
            $cEnd = Carbon::parse($conge->date_fin);

            $current = $cStart->copy();
            while ($current <= $cEnd) {
                $dateFull = $current->format('Y-m-d');
                if (!isset($congesByEmployee[$employeId][$dateFull])) {
                    $congesByEmployee[$employeId][$dateFull] = [];
                }
                $congesByEmployee[$employeId][$dateFull][] = [
                    'type' => $this->getCongeType($conge->type_conge ?? 'Congé'),
                    'title' => $conge->type_conge ?? 'Congé',
                    'sub' => 'Toute la journée',
                ];
                $current->addDay();
            }
        }

        // Événements exceptionnels groupés par employé et par date
        $evenementsByEmployee = [];
        $allEvenements = EvenementPlanning::where('siege_id', $siegeId)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('debut', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])
                  ->orWhereBetween('fin', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('debut', '<=', $start->format('Y-m-d 00:00:00'))
                         ->where('fin', '>=', $end->format('Y-m-d 23:59:59'));
                  });
            })
            ->with('employes')
            ->get();

        foreach ($allEvenements as $event) {
            foreach ($event->employes as $emp) {
                $empId = $emp->ID;
                if (!isset($evenementsByEmployee[$empId])) {
                    $evenementsByEmployee[$empId] = [];
                }
                $evStart = Carbon::parse($event->debut)->startOfDay();
                $evEnd = Carbon::parse($event->fin)->endOfDay();
                $cur = $evStart->copy();
                while ($cur <= $evEnd) {
                    $dateFull = $cur->format('Y-m-d');
                    if (!isset($evenementsByEmployee[$empId][$dateFull])) {
                        $evenementsByEmployee[$empId][$dateFull] = [];
                    }
                    $evenementsByEmployee[$empId][$dateFull][] = [
                        'type' => $event->type,
                        'title' => $event->titre,
                        'sub' => $event->toute_la_journee ? 'Toute la journée' : $event->debut->format('H:i') . ' - ' . $event->fin->format('H:i'),
                        'id' => $event->id,
                    ];
                    $cur->addDay();
                }
            }
        }

        // Services + employés
        $services = Department::where('site_id', $siegeId)
            ->with(['employes' => function($q) {
                $q->where('Actived', 1)->where('deleted', 0);
            }, 'employes.jobTitle'])
            ->get();

        $servicesData = [];
        foreach ($services as $service) {
            $employeesData = [];
            foreach ($service->employes as $employe) {
                $initiales = strtoupper(substr($employe->Nom ?? '', 0, 1));

                $schedule = [];
                foreach ($days as $day) {
                    $dateFull = $day['full'];
                    $dateKey = $day['date'];

                    // ✅ 1. Vérifier si c'est un jour travaillé
                    $horaireType = HoraireType::where('poste_id', $employe->job_title_id)->first();
                    $isWorkDay = false;

                    if ($horaireType && $horaireType->jours_travailles) {
                        $jourFr = $this->getJourFrancais($dateFull);
                        $joursTravailles = explode(',', $horaireType->jours_travailles);
                        $isWorkDay = in_array($jourFr, $joursTravailles);
                    }

                    // ✅ 2. Si PAS un jour travaillé → REPOS (prioritaire absolu)
                    if (!$isWorkDay) {
                        $schedule[$dateKey] = [['type' => 'rest']];
                        continue;
                    }

                    // ✅ 3. Événements (seulement si jour travaillé)
                    if (isset($evenementsByEmployee[$employe->ID][$dateFull]) && !empty($evenementsByEmployee[$employe->ID][$dateFull])) {
                        $schedule[$dateKey] = $evenementsByEmployee[$employe->ID][$dateFull];
                        continue;
                    }

                    // ✅ 4. Congés (seulement si jour travaillé)
                    if (isset($congesByEmployee[$employe->ID][$dateFull]) && !empty($congesByEmployee[$employe->ID][$dateFull])) {
                        $schedule[$dateKey] = $congesByEmployee[$employe->ID][$dateFull];
                        continue;
                    }

                    // ✅ 5. Planning généré (seulement si jour travaillé)
                    if (isset($scheduleByEmployee[$employe->ID][$dateFull])) {
                        $schedule[$dateKey] = $scheduleByEmployee[$employe->ID][$dateFull];
                        continue;
                    }

                    // ✅ 6. Horaires types (seulement si jour travaillé)
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

        return view('planning.index', compact(
            'servicesData',
            'days',
            'view',
            'periodLabel',
            'prevDate',
            'nextDate',
            'todayDate',
            'pivotDate'
        ));
    }

    /**
     * Convertir une date en jour français (minuscule)
     */
    private function getJourFrancais($dateFull)
    {
        $jourAnglais = strtolower(Carbon::parse($dateFull)->format('l'));
        $map = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche',
        ];
        return $map[$jourAnglais] ?? $jourAnglais;
    }

    /**
     * Convertir en jour français abrégé (Lun, Mar, ...)
     */
    private function getJourFrancaisCourt($date)
    {
        $jourAnglais = $date->format('l');
        $map = [
            'Monday' => 'Lun',
            'Tuesday' => 'Mar',
            'Wednesday' => 'Mer',
            'Thursday' => 'Jeu',
            'Friday' => 'Ven',
            'Saturday' => 'Sam',
            'Sunday' => 'Dim',
        ];
        return $map[$jourAnglais] ?? $date->format('D');
    }

    private function getDaysForView($view, $start, $end)
    {
        $days = [];

        if ($view === 'day') {
            $days[] = [
                'short' => $this->getJourFrancaisCourt($start) . '.',
                'date' => $start->format('d/m'),
                'full' => $start->format('Y-m-d'),
                'label' => $start->format('d/m/Y'),
            ];
        } elseif ($view === 'month') {
            $current = $start->copy();
            while ($current <= $end) {
                $days[] = [
                    'short' => $this->getJourFrancaisCourt($current),
                    'date' => $current->format('d/m'),
                    'full' => $current->format('Y-m-d'),
                    'label' => $current->format('d'),
                    'day_num' => (int) $current->format('d'),
                ];
                $current->addDay();
            }
        } else {
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $days[] = [
                    'short' => $this->getJourFrancaisCourt($date) . '.',
                    'date' => $date->format('d/m'),
                    'full' => $date->format('Y-m-d'),
                    'label' => $date->format('d/m'),
                ];
            }
        }

        return $days;
    }

    private function getNavigationDate($view, $pivot, $direction)
    {
        $date = $pivot->copy();

        switch ($view) {
            case 'day': $date->addDays($direction); break;
            case 'month': $date->addMonths($direction); break;
            case 'week':
            default: $date->addWeeks($direction); break;
        }

        return $date->format('Y-m-d');
    }

    private function getPeriodLabel($view, $start, $end, $pivot)
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        if ($view === 'day') {
            return $start->format('d') . ' ' . $mois[(int)$start->format('n')] . ' ' . $start->format('Y');
        }

        if ($view === 'month') {
            return $mois[(int)$pivot->format('n')] . ' ' . $pivot->format('Y');
        }

        $startLabel = $start->format('d') . ' ' . substr($mois[(int)$start->format('n')], 0, 3);
        $endLabel = $end->format('d') . ' ' . substr($mois[(int)$end->format('n')], 0, 3);
        return $startLabel . ' - ' . $endLabel . ' ' . $end->format('Y');
    }

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

    public function create()
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $services = Department::where('site_id', $siegeId)->orderBy('name')->get();
        return view('planning.create', compact('services'));
    }

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
                $jourKey = $this->getJourFrancais($current->format('Y-m-d'));

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

    public function show($id)
    {
        $planning = Planning::with(['siege', 'service', 'poste', 'createur', 'details.employe'])->findOrFail($id);
        return view('planning.show', compact('planning'));
    }

    /**
     * Récupérer les événements du calendrier (AJAX)
     * ✅ FILTRE : Les événements ne s'affichent PAS les jours non travaillés
     */
    public function getEvents(Request $request)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;
        $start = $request->input('start');
        $end = $request->input('end');
        $serviceId = $request->input('service_id');
        $posteId = $request->input('poste_id');

        $events = [];

        // ✅ Mapping jour anglais → français
        $jourMapping = [
            'monday' => 'lundi',
            'tuesday' => 'mardi',
            'wednesday' => 'mercredi',
            'thursday' => 'jeudi',
            'friday' => 'vendredi',
            'saturday' => 'samedi',
            'sunday' => 'dimanche',
        ];

        $planningDetails = PlanningDetail::with(['employe', 'planning'])
            ->whereHas('planning', function ($q) use ($siegeId, $serviceId, $posteId) {
                $q->where('siege_id', $siegeId)->where('statut', '!=', 'archive');
                if ($serviceId) $q->where('service_id', $serviceId);
                if ($posteId) $q->where('poste_id', $posteId);
            })
            ->whereBetween('date', [$start, $end])
            ->get();

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

        // ✅ Événements : FILTRER selon les jours travaillés
        $evenements = EvenementPlanning::where('siege_id', $siegeId)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('debut', [$start, $end])
                  ->orWhereBetween('fin', [$start, $end]);
            })
            ->with('employes')
            ->get();

        foreach ($evenements as $evenement) {
            $employesConcernes = $evenement->employes;

            if ($employesConcernes->isEmpty()) {
                // Pas d'employé spécifique → afficher quand même (événement global)
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
                continue;
            }

            // ✅ Vérifier si AU MOINS UN employé travaille ce jour-là
            $afficher = false;

            foreach ($employesConcernes as $emp) {
                $horaireType = HoraireType::where('poste_id', $emp->job_title_id)->first();

                if (!$horaireType || !$horaireType->jours_travailles) {
                    $afficher = true;
                    break;
                }

                $joursTravailles = explode(',', $horaireType->jours_travailles);
                $jourAnglais = strtolower($evenement->debut->format('l'));
                $jourFr = $jourMapping[$jourAnglais] ?? $jourAnglais;

                if (in_array($jourFr, $joursTravailles)) {
                    $afficher = true;
                    break;
                }
            }

            if ($afficher) {
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
        }

        return response()->json($events);
    }

    public function getEventDetail($id, $type)
    {
        $data = null;

        if ($type === 'planning') {
            $detail = PlanningDetail::with(['employe', 'planning'])->find($id);
            if ($detail) {
                $data = [
                    'type' => 'planning',
                    'employe' => $detail->employe?->Nom ?? 'N/A',
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
                $data = [
                    'type' => 'evenement',
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'type_event' => $evenement->type,
                    'debut' => $evenement->debut->format('d/m/Y H:i'),
                    'fin' => $evenement->fin->format('d/m/Y H:i'),
                    'employes' => $evenement->employes->map(function($e) {
                        return $e->Nom;
                    })->implode(', '),
                    'evenement_id' => $evenement->id,
                ];
            }
        }

        return response()->json($data);
    }

    public function getJobTitlesByDepartment($departmentId)
    {
        $departmentId = (int) $departmentId;
        $postes = JobTitle::where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($postes);
    }

    public function getEmployeesByService($serviceId)
    {
        try {
            $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

            if (!$siegeId || !$serviceId) {
                return response()->json([]);
            }

            $employes = Employe::where('SiegeID', $siegeId)
                ->where('department_id', $serviceId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get(['ID', 'Nom', 'job_title_id']);

            return response()->json($employes);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWorkSchedulesByJobTitle($jobTitleId)
    {
        try {
            $horaires = HoraireType::where('poste_id', $jobTitleId)
                ->orderBy('id')
                ->get();

            $result = $horaires->map(function($horaire) {
                $horaireLabel = $horaire->heure_debut . ' - ' . $horaire->heure_fin;
                if ($horaire->deuxieme_debut && $horaire->deuxieme_fin) {
                    $horaireLabel .= ' / ' . $horaire->deuxieme_debut . ' - ' . $horaire->deuxieme_fin;
                }

                $pauseLabel = 'Aucune pause';
                if ($horaire->pause_debut && $horaire->pause_fin) {
                    $pauseLabel = $horaire->pause_debut . ' - ' . $horaire->pause_fin;
                }

                $jours = $horaire->jours_travailles ? explode(',', $horaire->jours_travailles) : [];
                $joursLibelles = array_map(function($j) {
                    return ucfirst($j);
                }, $jours);

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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function formatEventTime($evenement)
    {
        if ($evenement->toute_la_journee) {
            return 'Toute la journée';
        }
        return $evenement->debut->format('H:i') . ' - ' . $evenement->fin->format('H:i');
    }

    private function getAvatarUrl($employe)
    {
        if ($employe && $employe->FaceEncodingPath) {
            return route('employes.face', $employe->ID);
        }
        return null;
    }

    private function getColorForEmployee($id)
    {
        $colors = ['#3B82F6', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#84CC16'];
        return $colors[$id % count($colors)];
    }

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

        $user = auth()->user();
        if (!($user->isTrueSuperAdmin() || $user->isSimpleAdmin())) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas l\'autorisation d\'ajouter des événements.'
            ], 403);
        }

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

    public function destroyEvent($id)
    {
        try {
            $evenement = EvenementPlanning::findOrFail($id);

            $user = auth()->user();
            if (!($user->isTrueSuperAdmin() || $user->isSimpleAdmin())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas l\'autorisation d\'annuler cet événement.'
                ], 403);
            }

            $evenement->employes()->detach();
            $evenement->delete();

            return response()->json([
                'success' => true,
                'message' => 'Événement annulé avec succès. Le créneau normal réapparaîtra.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Exportation Excel en cours de traitement.');
    }

    public function exportPdf(Request $request)
    {
        return back()->with('info', 'Exportation PDF en cours de traitement.');
    }

    public function calendar(Request $request)
    {
        return $this->index($request);
    }
}
