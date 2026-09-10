<?php

namespace App\Http\Controllers;

use App\Models\PlanningDetail;
use App\Models\EvenementPlanning;
use App\Models\HoraireType;
use App\Models\Employe;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Conge;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployePlanningController extends Controller
{
    public function index(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;

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

        $isManager = $this->isManager($employe);

        if ($isManager) {
            return $this->managerView($employe, $days, $start, $end, [
                'view' => $view,
                'periodLabel' => $periodLabel,
                'prevDate' => $prevDate,
                'nextDate' => $nextDate,
                'todayDate' => $todayDate,
                'pivotDate' => $pivot->format('Y-m-d'),
                'canGoNext' => $canGoNext,
            ]);
        } else {
            return $this->employeeView($employe, $days, $start, $end, [
                'view' => $view,
                'periodLabel' => $periodLabel,
                'prevDate' => $prevDate,
                'nextDate' => $nextDate,
                'todayDate' => $todayDate,
                'pivotDate' => $pivot->format('Y-m-d'),
                'canGoNext' => $canGoNext,
            ]);
        }
    }

    private function isManager($employe)
    {
        return Employe::where('manager_id', $employe->ID)
            ->where('ID', '!=', $employe->ID)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->exists();
    }

    private function managerView($employe, $days, $start, $end, $navData)
    {
        $allEmployes = $this->getAllSubordinates($employe->ID);

        if (!$allEmployes->contains('ID', $employe->ID)) {
            $allEmployes->push($employe);
        }

        $allEmployesIds = $allEmployes->pluck('ID')->unique()->toArray();

        $allPlanningDetails = PlanningDetail::whereIn('employe_id', $allEmployesIds)
            ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->with(['employe', 'employe.jobTitle'])
            ->get()
            ->groupBy('employe_id');

        $allConges = $this->getCongesForEmployes($allEmployesIds, $start, $end);
        $allEvenements = $this->getEvenementsForEmployes($allEmployesIds, $start, $end);

        $employeesData = [];
        foreach ($allEmployes as $emp) {
            $planningDetails = $allPlanningDetails->get($emp->ID, collect());
            $conges = $allConges[$emp->ID] ?? [];
            $evenements = $allEvenements[$emp->ID] ?? [];

            $schedule = $this->buildSchedule($emp, $days, $planningDetails, $conges, $evenements);

            $isManagerEmp = $this->isManager($emp);

            $employeesData[] = [
                'id' => $emp->ID,
                'name' => $emp->Nom,
                'role' => $emp->jobTitle?->name ?? 'N/A',
                'initiales' => strtoupper(substr($emp->Nom ?? '', 0, 1)),
                'color' => $emp->ID == $employe->ID ? '#3B82F6' : ($isManagerEmp ? '#8B5CF6' : '#22C55E'),
                'is_manager' => $isManagerEmp,
                'schedule' => $schedule,
            ];
        }

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

        return view('planning.manager', array_merge(
            ['servicesData' => $servicesData, 'days' => $days],
            $navData
        ));
    }

    private function employeeView($employe, $days, $start, $end, $navData)
    {
        $planningDetails = PlanningDetail::where('employe_id', $employe->ID)
            ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get();

        $conges = $this->getCongesForEmploye($employe->ID, $start, $end);
        $evenements = $this->getEvenementsForEmploye($employe->ID, $start, $end);

        $schedule = $this->buildSchedule($employe, $days, $planningDetails, $conges, $evenements);

        $employeeData = [
            'id' => $employe->ID,
            'name' => $employe->Nom,
            'role' => $employe->jobTitle?->name ?? 'N/A',
            'initiales' => strtoupper(substr($employe->Nom ?? '', 0, 1)),
            'color' => '#3B82F6',
            'is_manager' => false,
            'schedule' => $schedule,
        ];

        return view('planning.employe', array_merge(
            ['employeeData' => $employeeData, 'days' => $days],
            $navData
        ));
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
     * Construire le planning
     * PRIORITÉ : Événements > REPOS (si pas travaillé) > Congés > Planning > Horaires types
     */
    private function buildSchedule($employe, $days, $planningDetails, $conges, $evenements)
    {
        $schedule = [];

        foreach ($days as $day) {
            $dateKey = $day['date'];
            $dateFull = $day['full'];

            // ✅ 1. Vérifier si c'est un jour travaillé
            $horaireType = HoraireType::where('poste_id', $employe->job_title_id)->first();
            $isWorkDay = false;

            if ($horaireType && $horaireType->jours_travailles) {
                $jourFr = $this->getJourFrancais($dateFull);
                $joursTravailles = explode(',', $horaireType->jours_travailles);
                $isWorkDay = in_array($jourFr, $joursTravailles);
            }

            // ✅ 2. Événements (forcent l'affichage même jour non travaillé)
            if (isset($evenements[$dateKey]) && !empty($evenements[$dateKey])) {
                $schedule[$dateKey] = $evenements[$dateKey];
                continue;
            }

            // ✅ 3. Si PAS un jour travaillé → REPOS (prioritaire sur congés)
            if (!$isWorkDay) {
                $schedule[$dateKey] = [['type' => 'rest']];
                continue;
            }

            // ✅ 4. Congés (seulement si jour travaillé)
            if (isset($conges[$dateKey]) && !empty($conges[$dateKey])) {
                $schedule[$dateKey] = $conges[$dateKey];
                continue;
            }

            // ✅ 5. Planning généré
            $detail = $planningDetails->first(function($item) use ($day) {
                return Carbon::parse($item->date)->format('d/m') === $day['date'];
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

            // ✅ 6. Horaires types
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
                    'id' => $conge->id,
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
                    'id' => $conge->id,
                ];
                $current->addDay();
            }
        }

        return $conges;
    }

    private function getCongesForEmployes($employeIds, $start, $end)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getCongesForEmploye($id, $start, $end);
        }
        return $result;
    }

    private function getEvenementsForEmploye($employeId, $start, $end)
    {
        $evenements = [];

        $events = EvenementPlanning::whereHas('employes', function($q) use ($employeId) {
            $q->where('employe_id', $employeId);
        })
        ->whereBetween('debut', [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 23:59:59')])
        ->get();

        foreach ($events as $event) {
            $dateKey = Carbon::parse($event->debut)->format('d/m');
            $evenements[$dateKey][] = [
                'type' => $event->type,
                'title' => $event->titre,
                'sub' => $event->debut->format('H:i') . ' - ' . $event->fin->format('H:i'),
                'id' => $event->id,
            ];
        }

        return $evenements;
    }

    private function getEvenementsForEmployes($employeIds, $start, $end)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getEvenementsForEmploye($id, $start, $end);
        }
        return $result;
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
        $employeId = $employe->ID;
        $start = $request->input('start');
        $end = $request->input('end');

        $events = [];
        $isManager = $this->isManager($employe);

        if ($isManager) {
            $allEmployes = $this->getAllSubordinates($employeId);
            if (!$allEmployes->contains('ID', $employeId)) {
                $allEmployes->push($employe);
            }
            $employeIds = $allEmployes->pluck('ID')->toArray();
        } else {
            $employeIds = [$employeId];
        }

        $planningDetails = PlanningDetail::whereIn('employe_id', $employeIds)
            ->whereBetween('date', [$start, $end])
            ->with('employe')
            ->get();

        foreach ($planningDetails as $detail) {
            $initiales = strtoupper(substr($detail->employe?->Nom ?? '', 0, 1));
            $isCurrentUser = $detail->employe_id == $employeId;

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
                    'commentaire' => $detail->commentaire,
                    'initiales' => $initiales,
                ]
            ];
        }

        return response()->json($events);
    }
}
