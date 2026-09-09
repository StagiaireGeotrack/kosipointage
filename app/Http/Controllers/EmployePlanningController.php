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
    /**
     * Page du planning pour l'employé (ou manager)
     */
    public function index(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;
        $weekOffset = $request->get('week_offset', 0);

        $days = $this->getWeekDays($weekOffset);
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
        $endOfWeek = Carbon::now()->endOfWeek()->addWeeks($weekOffset);

        $isManager = $this->isManager($employe);

        if ($isManager) {
            return $this->managerView($employe, $days, $startOfWeek, $endOfWeek);
        } else {
            return $this->employeeView($employe, $days, $startOfWeek, $endOfWeek);
        }
    }

    /**
     * Vérifier si un employé est manager
     */
    private function isManager($employe)
    {
         $hasSubordinates = Employe::where('manager_id', $employe->ID)
            ->where('ID', '!=', $employe->ID)
            ->exists();
    
        return $hasSubordinates;
    }

    /**
     * Vue Manager : planning du manager + ses employés
     */
    private function managerView($employe, $days, $startOfWeek, $endOfWeek)
    {
        $siegeId = $employe->SiegeID;
        $departmentId = $employe->department_id;

        $employes = Employe::where('SiegeID', $siegeId)
            ->where('department_id', $departmentId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->with('jobTitle')
            ->orderBy('Nom')
            ->get();

        // Récupérer les plannings des employés
        $allPlanningDetails = PlanningDetail::whereIn('employe_id', $employes->pluck('ID'))
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->with(['employe', 'employe.jobTitle'])
            ->get()
            ->groupBy('employe_id');

        // Récupérer les congés et événements
        $employeIds = $employes->pluck('ID')->toArray();
        $allConges = $this->getCongesForEmployes($employeIds, $startOfWeek, $endOfWeek);
        $allEvenements = $this->getEvenementsForEmployes($employeIds, $startOfWeek, $endOfWeek);

        $employeesData = [];
        foreach ($employes as $emp) {
            $planningDetails = $allPlanningDetails->get($emp->ID, collect());
            $conges = $allConges[$emp->ID] ?? [];
            $evenements = $allEvenements[$emp->ID] ?? [];

            $schedule = $this->buildSchedule($emp, $days, $planningDetails, $conges, $evenements);

            $isManagerEmp = $emp->ID == $employe->ID;

            $employeesData[] = [
                'id' => $emp->ID,
                'name' => $emp->Nom,
                'role' => $emp->jobTitle?->name ?? 'N/A',
                'initiales' => strtoupper(substr($emp->Nom ?? '', 0, 1)),
                'color' => $isManagerEmp ? '#3B82F6' : '#22C55E',
                'is_manager' => $isManagerEmp,
                'schedule' => $schedule,
            ];
        }

        $serviceName = $employe->department?->name ?? 'Mon service';

        $servicesData = [[
            'id' => $departmentId,
            'name' => $serviceName,
            'count' => count($employeesData),
            'employees' => $employeesData,
        ]];

        return view('planning.manager', compact('servicesData', 'days'));
    }

    /**
     * Vue Employé : planning de l'employé uniquement
     */
    private function employeeView($employe, $days, $startOfWeek, $endOfWeek)
    {
        // Récupérer les plannings de l'employé
        $planningDetails = PlanningDetail::where('employe_id', $employe->ID)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        // Récupérer les congés
        $conges = $this->getCongesForEmploye($employe->ID, $startOfWeek, $endOfWeek);

        // Récupérer les événements
        $evenements = $this->getEvenementsForEmploye($employe->ID, $startOfWeek, $endOfWeek);

        // Construire le planning
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

        return view('planning.employe', compact('employeeData', 'days'));
    }

    /**
     * Construire le planning d'un employé
     * PRIORITÉ : Congés > Événements > Planning généré > Horaires types > Repos
     */
    private function buildSchedule($employe, $days, $planningDetails, $conges, $evenements)
    {
        $schedule = [];

        foreach ($days as $day) {
            $dateKey = $day['date']; // format: d/m
            $dateFull = $day['full']; // format: Y-m-d

            // ✅ 1. PRIORITÉ MAX : VÉRIFIER LES CONGÉS
            if (isset($conges[$dateKey]) && !empty($conges[$dateKey])) {
                $schedule[$dateKey] = $conges[$dateKey];
                continue;
            }

            // ✅ 2. VÉRIFIER LES ÉVÉNEMENTS EXCEPTIONNELS
            if (isset($evenements[$dateKey]) && !empty($evenements[$dateKey])) {
                $schedule[$dateKey] = $evenements[$dateKey];
                continue;
            }

            // ✅ 3. VÉRIFIER LE PLANNING GÉNÉRÉ
            $detail = $planningDetails->first(function($item) use ($day) {
                return Carbon::parse($item->date)->format('d/m') === $day['date'];
            });

            if ($detail) {
                $items = [];
                if ($detail->heure_debut && $detail->heure_fin) {
                    $items[] = [
                        'type' => 'work',
                        'time' => $detail->heure_debut . ' - ' . $detail->heure_fin,
                        'id' => $detail->id,
                    ];
                }
                if ($detail->pause_debut && $detail->pause_fin) {
                    $items[] = [
                        'type' => 'pause',
                        'time' => $detail->pause_debut . ' - ' . $detail->pause_fin,
                        'id' => $detail->id,
                    ];
                }
                if ($detail->deuxieme_debut && $detail->deuxieme_fin) {
                    $items[] = [
                        'type' => 'work',
                        'time' => $detail->deuxieme_debut . ' - ' . $detail->deuxieme_fin,
                        'id' => $detail->id,
                    ];
                }
                $schedule[$dateKey] = $items;
                continue;
            }

            // ✅ 4. SINON, UTILISER LES HORAIRES TYPES
            $horaireType = HoraireType::where('poste_id', $employe->job_title_id)->first();
            $isWorkDay = false;

            if ($horaireType && $horaireType->jours_travailles) {
                $jourFrancais = strtolower(Carbon::parse($dateFull)->format('l'));
                $joursTravailles = explode(',', $horaireType->jours_travailles);
                $isWorkDay = in_array($jourFrancais, $joursTravailles);
            }

            if (!$isWorkDay) {
                $schedule[$dateKey] = [['type' => 'rest']];
            } elseif ($horaireType) {
                $items = [];
                if ($horaireType->heure_debut && $horaireType->heure_fin) {
                    $items[] = [
                        'type' => 'work',
                        'time' => $horaireType->heure_debut . ' - ' . $horaireType->heure_fin,
                    ];
                }
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
                $schedule[$dateKey] = [['type' => 'rest']];
            }
        }

        return $schedule;
    }

    /**
     * Récupérer les congés approuvés d'un employé
     */
    private function getCongesForEmploye($employeId, $startOfWeek, $endOfWeek)
    {
        $conges = [];

        // Nouveau système LeaveRequest
        $leaveRequests = LeaveRequest::where('employee_id', $employeId)
            ->where('status', 'approved')
            ->where(function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('start_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhere(function($q2) use ($startOfWeek, $endOfWeek) {
                      $q2->where('start_date', '<=', $startOfWeek->format('Y-m-d'))
                         ->where('end_date', '>=', $endOfWeek->format('Y-m-d'));
                  });
            })
            ->with('leaveType')
            ->get();

        foreach ($leaveRequests as $conge) {
            $start = Carbon::parse($conge->start_date);
            $end = Carbon::parse($conge->end_date);
            $current = $start->copy();

            while ($current <= $end) {
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

        // Ancien système Conge
        $congesAnciens = Conge::where('employee_id', $employeId)
            ->where(function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('date_debut', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhereBetween('date_fin', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                  ->orWhere(function($q2) use ($startOfWeek, $endOfWeek) {
                      $q2->where('date_debut', '<=', $startOfWeek->format('Y-m-d'))
                         ->where('date_fin', '>=', $endOfWeek->format('Y-m-d'));
                  });
            })
            ->get();

        foreach ($congesAnciens as $conge) {
            $start = Carbon::parse($conge->date_debut);
            $end = Carbon::parse($conge->date_fin);
            $current = $start->copy();

            while ($current <= $end) {
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

    /**
     * Récupérer les congés pour plusieurs employés
     */
    private function getCongesForEmployes($employeIds, $startOfWeek, $endOfWeek)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getCongesForEmploye($id, $startOfWeek, $endOfWeek);
        }
        return $result;
    }

    /**
     * Récupérer les événements exceptionnels d'un employé
     */
    private function getEvenementsForEmploye($employeId, $startOfWeek, $endOfWeek)
    {
        $evenements = [];

        $events = EvenementPlanning::whereHas('employes', function($q) use ($employeId) {
            $q->where('employe_id', $employeId);
        })
        ->whereBetween('debut', [$startOfWeek->format('Y-m-d 00:00:00'), $endOfWeek->format('Y-m-d 23:59:59')])
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

    /**
     * Récupérer les événements pour plusieurs employés
     */
    private function getEvenementsForEmployes($employeIds, $startOfWeek, $endOfWeek)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getEvenementsForEmploye($id, $startOfWeek, $endOfWeek);
        }
        return $result;
    }

    /**
     * Obtenir le type de congé
     */
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

    /**
     * Récupérer les événements pour FullCalendar (AJAX)
     */
    public function getEvents(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;
        $start = $request->input('start');
        $end = $request->input('end');

        $events = [];

        $isManager = $this->isManager($employe);

        if ($isManager) {
            $departmentId = $employe->department_id;
            $employeIds = Employe::where('department_id', $departmentId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->pluck('ID')
                ->toArray();
        } else {
            $employeIds = [$employeId];
        }

        // Plannings
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

        // Événements exceptionnels
        $evenements = EvenementPlanning::whereHas('employes', function($q) use ($employeIds) {
            $q->whereIn('employe_id', $employeIds);
        })
        ->whereBetween('debut', [$start, $end])
        ->get();

        foreach ($evenements as $evenement) {
            $events[] = [
                'id' => 'event_' . $evenement->id,
                'title' => '📌 ' . $evenement->titre,
                'start' => $evenement->debut->format('Y-m-d\TH:i:s'),
                'end' => $evenement->fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#8B5CF6',
                'borderColor' => '#8B5CF6',
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
}