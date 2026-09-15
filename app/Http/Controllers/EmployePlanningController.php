<?php

namespace App\Http\Controllers;

use App\Models\PlanningDetail;
use App\Models\EvenementPlanning;
use App\Models\HoraireType;
use App\Models\Employe;
use App\Models\Department;
use App\Models\Administration;
use App\Models\LeaveRequest;
use App\Models\Conge;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployePlanningController extends Controller
{
    public function index(Request $request)
    {
        $employe = auth()->guard('employe')->user();

        $view = $request->get('view', 'week');
        if (!in_array($view, ['day', 'week'])) {
            $view = 'week';
        }

        $pivotDate = $request->get('date', Carbon::now()->format('Y-m-d'));
        try {
            $pivot = Carbon::parse($pivotDate);
        } catch (\Exception $e) {
            $pivot = Carbon::now();
        }

        $endOfCurrentWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        if ($pivot->greaterThan($endOfCurrentWeek)) {
            $pivot = Carbon::now();
        }

        if ($view === 'day') {
            $start = $pivot->copy()->startOfDay();
            $end = $pivot->copy()->endOfDay();
        } else {
            $start = $pivot->copy()->startOfWeek(Carbon::MONDAY);
            $end = $pivot->copy()->endOfWeek(Carbon::SUNDAY);
        }

        if ($end->greaterThan($endOfCurrentWeek)) {
            $end = $endOfCurrentWeek->copy();
        }

        $prevDate = $this->getNavigationDate($view, $pivot, -1);
        $nextPivot = $this->getNavigationDate($view, $pivot, 1);
        $canGoNext = Carbon::parse($nextPivot)->startOfDay()->lessThanOrEqualTo($endOfCurrentWeek->copy()->startOfDay());
        $nextDate = $canGoNext ? $nextPivot : $pivot->format('Y-m-d');

        $todayDate = Carbon::now()->format('Y-m-d');
        $periodLabel = $this->getPeriodLabel($view, $start, $end, $pivot);
        $days = $this->getDaysForView($view, $start, $end);

        // ============================================================
        // Déterminer la liste des employés à afficher
        // ============================================================
        $employeesToShow = $this->getEmployeesToDisplay($employe);
        $isManagerView = $employeesToShow->count() > 1;
        $employeIds = $employeesToShow->pluck('ID')->unique()->toArray();

        // ============================================================
        // Récupérer les données (planning, congés, événements)
        // ============================================================
        $allPlanningDetails = PlanningDetail::whereIn('employe_id', $employeIds)
            ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->with(['employe', 'employe.jobTitle'])
            ->get()
            ->groupBy('employe_id');

        $allConges = $this->getCongesForEmployes($employeIds, $start, $end);
        $allEvenements = $this->getEvenementsForEmployes($employeIds, $start, $end);

        // ============================================================
        // Construire les données par employé
        // ============================================================
        $employeesData = [];
        foreach ($employeesToShow as $emp) {
            $planningDetails = $allPlanningDetails->get($emp->ID, collect());
            $conges = $allConges[$emp->ID] ?? [];
            $evenements = $allEvenements[$emp->ID] ?? [];

            $schedule = $this->buildSchedule($emp, $days, $planningDetails, $conges, $evenements);

            $isCurrentUser = $emp->ID == $employe->ID;
            $isManagerEmp = $this->isManager($emp);

            $employeesData[] = [
                'id' => $emp->ID,
                'name' => $emp->Nom,
                'role' => $emp->jobTitle?->name ?? 'N/A',
                'initiales' => strtoupper(substr($emp->Nom ?? '', 0, 1)),
                'color' => $isCurrentUser ? '#3B82F6' : ($isManagerEmp ? '#8B5CF6' : '#22C55E'),
                'is_manager' => $isManagerEmp,
                'is_me' => $isCurrentUser,
                'schedule' => $schedule,
            ];
        }

        // ============================================================
        // Grouper par service
        // ============================================================
        $groupedByService = [];
        foreach ($employeesData as $empData) {
            $empModel = Employe::find($empData['id']);
            $serviceId = $empModel->department_id ?? 0;
            $serviceName = $empModel->department?->name ?? 'Sans service';

            if (!isset($groupedByService[$serviceId])) {
                $groupedByService[$serviceId] = [
                    'id' => $serviceId,
                    'name' => $serviceName,
                    'count' => 0,
                    'employees' => [],
                ];
            }
            $groupedByService[$serviceId]['employees'][] = $empData;
            $groupedByService[$serviceId]['count']++;
        }

        $servicesData = array_values($groupedByService);

        return view('planning.employe', [
            'servicesData' => $servicesData,
            'days' => $days,
            'isManagerView' => $isManagerView,
            'view' => $view,
            'periodLabel' => $periodLabel,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'todayDate' => $todayDate,
            'pivotDate' => $pivot->format('Y-m-d'),
            'canGoNext' => $canGoNext,
        ]);
    }

    /**
     * Retourne la liste des employés à afficher selon le rôle de l'utilisateur connecté
     */
    private function getEmployeesToDisplay($employe)
    {
        // 1. Vérifier si l'employé est lié à un compte Supervisor
        $admin = $this->getLinkedAdministration($employe);

        if ($admin && $admin->isSupervisor()) {
            // Supervisor : tous les employés de ses services affectés
            $serviceIds = $admin->getSupervisorServiceIds();

            if (empty($serviceIds)) {
                return collect([$employe]);
            }

            $employees = Employe::whereIn('department_id', $serviceIds)
                ->where('SiegeID', $employe->SiegeID)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();

            if (!$employees->contains('ID', $employe->ID)) {
                $employees->push($employe);
            }

            return $employees->unique('ID');
        }

        // 2. Vérifier si l'employé est manager (a des subordonnés ou gère un département)
        $isManager = $this->isManager($employe);

        if (!$isManager) {
            return collect([$employe]);
        }

        // Manager : subordonnés + employés du même service
        $result = collect();

        if ($employe->department_id) {
            $sameDept = Employe::where('department_id', $employe->department_id)
                ->where('SiegeID', $employe->SiegeID)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->get();
            $result = $result->merge($sameDept);
        }

        $subordinates = $this->getAllSubordinates($employe->ID);
        $result = $result->merge($subordinates);

        if (!$result->contains('ID', $employe->ID)) {
            $result->push($employe);
        }

        return $result->unique('ID');
    }

    /**
     * Trouve le compte Administration lié à cet Employe (par email)
     */
    private function getLinkedAdministration($employe)
    {
        if (!$employe->email) {
            return null;
        }

        return Administration::where('Identifiant_email', $employe->email)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->first();
    }

    private function isManager($employe)
    {
        $hasSubordinates = Employe::where('manager_id', $employe->ID)
            ->where('ID', '!=', $employe->ID)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->exists();

        if ($hasSubordinates) {
            return true;
        }

        return Department::where('manager_employee_id', $employe->ID)->exists();
    }

    private function getAllSubordinates($managerId)
    {
        $result = collect();
        $directs = Employe::where('manager_id', $managerId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->get();

        foreach ($directs as $direct) {
            $result->push($direct);
            $result = $result->merge($this->getAllSubordinates($direct->ID));
        }

        return $result;
    }

    /**
     * Construire le planning d'un employé
     */
    private function buildSchedule($employe, $days, $planningDetails, $conges, $evenements)
    {
        $schedule = [];

        foreach ($days as $day) {
            $dateKey = $day['date'];
            $dateFull = $day['full'];

            $horaireType = HoraireType::where('poste_id', $employe->job_title_id)->first();
            $isWorkDay = false;

            if ($horaireType && $horaireType->jours_travailles) {
                $jourFr = $this->getJourFrancais($dateFull);
                $joursTravailles = explode(',', $horaireType->jours_travailles);
                $isWorkDay = in_array($jourFr, $joursTravailles);
            }

            // 1. Jour non travaillé → Repos (priorité absolue)
            if (!$isWorkDay) {
                $schedule[$dateKey] = [['type' => 'rest']];
                continue;
            }

            // 2. Événements (uniquement les jours travaillés)
            if (isset($evenements[$dateKey]) && !empty($evenements[$dateKey])) {
                $schedule[$dateKey] = $evenements[$dateKey];
                continue;
            }


            // 3. Congés
            if (isset($conges[$dateKey]) && !empty($conges[$dateKey])) {
                $schedule[$dateKey] = $conges[$dateKey];
                continue;
            }

            // 4. Planning généré
            $detail = $planningDetails->first(function($item) use ($dateFull) {
                return Carbon::parse($item->date)->format('Y-m-d') === $dateFull;
            });

            if ($detail) {
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
                $schedule[$dateKey] = $items;
                continue;
            }

            // 5. Horaires types
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

        return $schedule;
    }

    private function getJourFrancais($dateFull)
    {
        $jourAnglais = strtolower(Carbon::parse($dateFull)->format('l'));
        $map = [
            'monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi',
            'thursday' => 'jeudi', 'friday' => 'vendredi', 'saturday' => 'samedi', 'sunday' => 'dimanche',
        ];
        return $map[$jourAnglais] ?? $jourAnglais;
    }

    private function getJourFrancaisCourt($date)
    {
        $jourAnglais = $date->format('l');
        $map = [
            'Monday' => 'Lun', 'Tuesday' => 'Mar', 'Wednesday' => 'Mer',
            'Thursday' => 'Jeu', 'Friday' => 'Ven', 'Saturday' => 'Sam', 'Sunday' => 'Dim',
        ];
        return $map[$jourAnglais] ?? $date->format('D');
    }

    private function getCongesForEmployes($employeIds, $start, $end)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getCongesForEmploye($id, $start, $end);
        }
        return $result;
    }

    private function getCongesForEmploye($employeId, $start, $end)
    {
        $conges = [];

        $leaveRequests = LeaveRequest::where('employee_id', $employeId)
            ->where('status', 'approved')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start->format('Y-m-d'))
                         ->where('end_date', '>=', $end->format('Y-m-d'));
                  });
            })
            ->with('leaveType')
            ->get();

        foreach ($leaveRequests as $conge) {
            $cStart = Carbon::parse($conge->start_date);
            $cEnd = Carbon::parse($conge->end_date);
            $current = $cStart->copy();

            while ($current <= $cEnd) {
                $dateKey = $current->format('d/m');
                $type = $this->getCongeType($conge->leaveType?->name ?? 'Congé');
                $conges[$dateKey][] = [
                    'type' => $type,
                    'title' => $conge->leaveType?->name ?? 'Congé',
                    'sub' => 'Toute la journée',
                    'id' => 'lr_' . $conge->id,   // ✅ Préfixe LeaveRequest
                ];
                $current->addDay();
            }
        }

        $congesAnciens = Conge::where('employee_id', $employeId)
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('date_debut', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhereBetween('date_fin', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('date_debut', '<=', $start->format('Y-m-d'))
                         ->where('date_fin', '>=', $end->format('Y-m-d'));
                  });
            })
            ->get();

        foreach ($congesAnciens as $conge) {
            $cStart = Carbon::parse($conge->date_debut);
            $cEnd = Carbon::parse($conge->date_fin);
            $current = $cStart->copy();

            while ($current <= $cEnd) {
                $dateKey = $current->format('d/m');
                $type = $this->getCongeType($conge->type_conge ?? 'Congé');
                $conges[$dateKey][] = [
                    'type' => $type,
                    'title' => $conge->type_conge ?? 'Congé',
                    'sub' => 'Toute la journée',
                    'id' => 'c_' . $conge->id,   // ✅ Préfixe Conge
                ];
                $current->addDay();
            }
        }

        return $conges;
    }

    private function getEvenementsForEmployes($employeIds, $start, $end)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getEvenementsForEmploye($id, $start, $end);
        }
        return $result;
    }

    private function getEvenementsForEmploye($employeId, $start, $end)
    {
        $evenements = [];

        // ✅ FIX J-1 : utiliser la MÊME logique que l'admin (multi-jours + toute_la_journee)
        $events = EvenementPlanning::whereHas('employes', function($q) use ($employeId) {
            $q->where('employe_id', $employeId);
        })
        ->where(function($q) use ($start, $end) {
            $q->whereBetween('debut', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])
              ->orWhereBetween('fin', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])
              ->orWhere(function($q2) use ($start, $end) {
                  $q2->where('debut', '<=', $start->format('Y-m-d 00:00:00'))
                     ->where('fin', '>=', $end->format('Y-m-d 23:59:59'));
              });
        })
        ->get();

        foreach ($events as $event) {
            // Étendre l'événement sur tous les jours de sa durée
            $evStart = Carbon::parse($event->debut)->startOfDay();
            $evEnd = Carbon::parse($event->fin)->endOfDay();
            $cur = $evStart->copy();

            while ($cur <= $evEnd) {
                $dateKey = $cur->format('d/m');

                // ✅ FIX J-1 : même condition que l'admin pour toute_la_journee
                $sub = $event->toute_la_journee
                    ? 'Toute la journée'
                    : $event->debut->format('H:i') . ' - ' . $event->fin->format('H:i');

                $evenements[$dateKey][] = [
                    'type' => $event->type,
                    'title' => $event->titre,
                    'sub' => $sub,
                    'id' => $event->id,
                ];
                $cur->addDay();
            }
        }

        return $evenements;
    }

    private function getCongeType($type)
    {
        $types = [
            'RTT' => 'rtt', 'Maladie' => 'maladie', 'Absence autorisée' => 'absence',
            'Congé exceptionnel' => 'conge', 'Congé payé' => 'conge',
        ];
        return $types[$type] ?? 'conge';
    }

    private function getDaysForView($view, $start, $end)
    {
        $days = [];

        if ($view === 'day') {
            $days[] = [
                'short' => $this->getJourFrancaisCourt($start) . '.',
                'date' => $start->format('d/m'),
                'full' => $start->format('Y-m-d'),
            ];
        } else {
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                if ($date->greaterThan($end)) break;
                $days[] = [
                    'short' => $this->getJourFrancaisCourt($date) . '.',
                    'date' => $date->format('d/m'),
                    'full' => $date->format('Y-m-d'),
                ];
            }
        }

        return $days;
    }

    private function getNavigationDate($view, $pivot, $direction)
    {
        $date = $pivot->copy();
        if ($view === 'day') {
            $date->addDays($direction);
        } else {
            $date->addWeeks($direction);
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

        $startLabel = $start->format('d') . ' ' . substr($mois[(int)$start->format('n')], 0, 3);
        $endLabel = $end->format('d') . ' ' . substr($mois[(int)$end->format('n')], 0, 3);
        return $startLabel . ' - ' . $endLabel . ' ' . $end->format('Y');
    }

    public function getEvents(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $start = $request->input('start');
        $end = $request->input('end');

        $employeesToShow = $this->getEmployeesToDisplay($employe);
        $employeIds = $employeesToShow->pluck('ID')->toArray();

        $events = [];

        $planningDetails = PlanningDetail::whereIn('employe_id', $employeIds)
            ->whereBetween('date', [$start, $end])
            ->with('employe')
            ->get();

        foreach ($planningDetails as $detail) {
            $initiales = strtoupper(substr($detail->employe?->Nom ?? '', 0, 1));
            $isCurrentUser = $detail->employe_id == $employe->ID;

            $events[] = [
                'id' => 'detail_' . $detail->id,
                'title' => $initiales . ' - ' . $detail->heure_debut . '-' . $detail->heure_fin,
                'start' => $detail->date . 'T' . $detail->heure_debut,
                'end' => $detail->date . 'T' . $detail->heure_fin,
                'backgroundColor' => $isCurrentUser ? '#3B82F6' : '#22C55E',
                'borderColor' => $isCurrentUser ? '#3B82F6' : '#22C55E',
                'extendedProps' => [
                    'type' => 'planning',
                    'employe' => $detail->employe?->Nom ?? 'N/A',
                    'employe_id' => $detail->employe_id,
                    'statut' => $detail->statut,
                    'initiales' => $initiales,
                ]
            ];
        }

        return response()->json($events);
    }

        /**
     * Charger le détail d'un créneau (AJAX)
     * Supporte : planning, evenement, conge
     */
    public function getEventDetail($id, $type)
    {
        $employe = auth()->guard('employe')->user();
        $employeesToShow = $this->getEmployeesToDisplay($employe);
        $employeIds = $employeesToShow->pluck('ID')->toArray();

        $data = null;

        // ============================================================
        // TYPE : planning (PlanningDetail)
        // ============================================================
        if ($type === 'planning') {
            $detail = PlanningDetail::with(['employe', 'employe.jobTitle'])->find($id);

            if ($detail && in_array($detail->employe_id, $employeIds)) {
                $data = [
                    'type'         => 'planning',
                    'employe'      => $detail->employe?->Nom ?? 'N/A',
                    'role'         => $detail->employe?->jobTitle?->name ?? 'N/A',
                    'date'         => $detail->date ? Carbon::parse($detail->date)->format('d/m/Y') : '-',
                    'heure_debut'  => $detail->heure_debut,
                    'heure_fin'    => $detail->heure_fin,
                    'pause_debut'  => $detail->pause_debut,
                    'pause_fin'    => $detail->pause_fin,
                    'commentaire'  => $detail->commentaire,
                    'statut'       => $detail->statut,
                    'avatar'       => null,
                ];
            }
        }

        // ============================================================
        // TYPE : evenement (EvenementPlanning)
        // ============================================================
        elseif ($type === 'evenement') {
            $evenement = EvenementPlanning::with('employes')->find($id);

            if ($evenement) {
                // Vérifier que l'événement concerne au moins un employé dans le périmètre
                $eventEmployeeIds = $evenement->employes->pluck('ID')->toArray();
                $hasAccess = !empty(array_intersect($eventEmployeeIds, $employeIds));

                if ($hasAccess) {
                    $data = [
                        'type'         => 'evenement',
                        'titre'        => $evenement->titre,
                        'description'  => $evenement->description,
                        'type_event'   => $evenement->type,
                        'debut'        => $evenement->debut->format('d/m/Y H:i'),
                        'fin'          => $evenement->fin->format('d/m/Y H:i'),
                        'employes'     => $evenement->employes->map(fn($e) => $e->Nom)->implode(', '),
                        'evenement_id' => $evenement->id,
                    ];
                }
            }
        }

        // ============================================================
        // TYPE : conge (LeaveRequest ou Conge)
        // ID préfixé par "lr_" ou "cg_"
        // ============================================================
        elseif ($type === 'conge') {
            if (strpos($id, 'lr_') === 0) {
                // LeaveRequest (nouveau système)
                $lrId = (int) substr($id, 3);
                $lr = LeaveRequest::with(['employee', 'leaveType'])->find($lrId);

                if ($lr && in_array($lr->employee_id, $employeIds)) {
                    $data = [
                        'type'         => 'conge',
                        'employe'      => $lr->employee?->Nom ?? 'N/A',
                        'type_conge'   => $lr->leaveType?->name ?? 'Congé',
                        'date_debut'   => Carbon::parse($lr->start_date)->format('d/m/Y'),
                        'date_fin'     => Carbon::parse($lr->end_date)->format('d/m/Y'),
                        'duration'     => $lr->duration,
                        'statut'       => $lr->status,
                        'commentaire'  => $lr->reason ?? $lr->comment,
                        'source'       => 'leaverequest',
                    ];
                }
            } elseif (strpos($id, 'cg_') === 0) {
                // Conge (ancien système)
                $cgId = (int) substr($id, 3);
                $cg = Conge::with('employe')->find($cgId);

                if ($cg && in_array($cg->employee_id, $employeIds)) {
                    $data = [
                        'type'         => 'conge',
                        'employe'      => $cg->employe?->Nom ?? 'N/A',
                        'type_conge'   => $cg->type_conge ?? 'Congé',
                        'date_debut'   => Carbon::parse($cg->date_debut)->format('d/m/Y'),
                        'date_fin'     => Carbon::parse($cg->date_fin)->format('d/m/Y'),
                        'duration'     => null,
                        'statut'       => 'approuvé',
                        'commentaire'  => $cg->commentaire,
                        'source'       => 'conge',
                    ];
                }
            }
        }

        return response()->json($data);
    }
}
