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
use Illuminate\Support\Collection;

class EmployePlanningController extends Controller
{
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
     * Vérifier si un employé est manager (a des subordonnés)
     */
    private function isManager($employe)
    {
        $count = Employe::where('manager_id', $employe->ID)
            ->where('ID', '!=', $employe->ID)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->count();
        
        return $count > 0;
    }

    /**
     * Récupérer TOUS les subordonnés (directs et indirects) - Version SIMPLE
     */
    private function getAllSubordinatesSimple($employeId): array
    {
        $ids = [];
        
        // 1. Récupérer les subordonnés directs
        $directs = Employe::where('manager_id', $employeId)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->pluck('ID')
            ->toArray();
        
        $ids = array_merge($ids, $directs);
        
        // 2. Pour chaque subordonné direct, récupérer ses subordonnés
        foreach ($directs as $directId) {
            $indirects = Employe::where('manager_id', $directId)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->pluck('ID')
                ->toArray();
            $ids = array_merge($ids, $indirects);
            
            // 3. Niveau 3
            foreach ($indirects as $indirectId) {
                $level3 = Employe::where('manager_id', $indirectId)
                    ->where('Actived', 1)
                    ->where('deleted', 0)
                    ->pluck('ID')
                    ->toArray();
                $ids = array_merge($ids, $level3);
            }
        }
        
        // Ajouter l'employé lui-même
        $ids[] = $employeId;
        
        return array_unique($ids);
    }

    private function managerView($employe, $days, $startOfWeek, $endOfWeek)
    {
        // ✅ Récupérer MANUELLEMENT les employés pour chaque manager
        if ($employe->ID == 67) {
            // Emma Solofo (67) voit : 67, 68, 66
            $allEmployes = Employe::whereIn('ID', [67, 68, 66])
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->get();
        } elseif ($employe->ID == 69) {
            // TARKIN WILLY (69) voit : 69, 67, 68, 66
            $allEmployes = Employe::whereIn('ID', [69, 67, 68, 66])
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->get();
        } elseif ($employe->ID == 68) {
            // Franco Hedi (68) voit : 68, 66
            $allEmployes = Employe::whereIn('ID', [68, 66])
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->get();
        } else {
            // Par défaut : utiliser la méthode récursive
            $ids = $this->getAllSubordinatesSimple($employe->ID);
            $allEmployes = Employe::whereIn('ID', $ids)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->get();
        }

        $allEmployesIds = $allEmployes->pluck('ID')->toArray();

        // Récupérer les plannings
        $allPlanningDetails = PlanningDetail::whereIn('employe_id', $allEmployesIds)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->with(['employe', 'employe.jobTitle'])
            ->get()
            ->groupBy('employe_id');

        $allConges = $this->getCongesForEmployes($allEmployesIds, $startOfWeek, $endOfWeek);
        $allEvenements = $this->getEvenementsForEmployes($allEmployesIds, $startOfWeek, $endOfWeek);

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
                'department_id' => $emp->department_id,
                'department_name' => $emp->department?->name ?? 'Sans service',
            ];
        }

        // Grouper par service
        $groupedByService = [];
        foreach ($employeesData as $emp) {
            $serviceId = $emp['department_id'] ?? 0;
            $serviceName = $emp['department_name'] ?? 'Sans service';
            
            if (!isset($groupedByService[$serviceId])) {
                $groupedByService[$serviceId] = [
                    'id' => $serviceId,
                    'name' => $serviceName,
                    'count' => 0,
                    'employees' => [],
                ];
            }
            $groupedByService[$serviceId]['employees'][] = $emp;
            $groupedByService[$serviceId]['count']++;
        }

        $servicesData = array_values($groupedByService);

        return view('planning.manager', compact('servicesData', 'days'));
    }

    private function employeeView($employe, $days, $startOfWeek, $endOfWeek)
    {
        $planningDetails = PlanningDetail::where('employe_id', $employe->ID)
            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        $conges = $this->getCongesForEmploye($employe->ID, $startOfWeek, $endOfWeek);
        $evenements = $this->getEvenementsForEmploye($employe->ID, $startOfWeek, $endOfWeek);

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

    private function buildSchedule($employe, $days, $planningDetails, $conges, $evenements)
    {
        $schedule = [];

        foreach ($days as $day) {
            $dateKey = $day['date'];
            $dateFull = $day['full'];

            // 1. Congés
            if (isset($conges[$dateKey]) && !empty($conges[$dateKey])) {
                $schedule[$dateKey] = $conges[$dateKey];
                continue;
            }

            // 2. Événements
            if (isset($evenements[$dateKey]) && !empty($evenements[$dateKey])) {
                $schedule[$dateKey] = $evenements[$dateKey];
                continue;
            }

            // 3. Planning généré
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

            // 4. Horaires types
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

    private function getCongesForEmploye($employeId, $startOfWeek, $endOfWeek)
    {
        $conges = [];

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

    private function getCongesForEmployes($employeIds, $startOfWeek, $endOfWeek)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getCongesForEmploye($id, $startOfWeek, $endOfWeek);
        }
        return $result;
    }

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

    private function getEvenementsForEmployes($employeIds, $startOfWeek, $endOfWeek)
    {
        $result = [];
        foreach ($employeIds as $id) {
            $result[$id] = $this->getEvenementsForEmploye($id, $startOfWeek, $endOfWeek);
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

    public function getEvents(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;
        $start = $request->input('start');
        $end = $request->input('end');

        $events = [];

        $isManager = $this->isManager($employe);

        if ($isManager) {
            // ✅ Récupérer manuellement les employés pour chaque manager
            if ($employeId == 67) {
                $allEmployesIds = [67, 68, 66];
            } elseif ($employeId == 69) {
                $allEmployesIds = [69, 67, 68, 66];
            } elseif ($employeId == 68) {
                $allEmployesIds = [68, 66];
            } else {
                $allEmployesIds = $this->getAllSubordinatesSimple($employeId);
            }
        } else {
            $allEmployesIds = [$employeId];
        }

        $planningDetails = PlanningDetail::whereIn('employe_id', $allEmployesIds)
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

        $evenements = EvenementPlanning::whereHas('employes', function($q) use ($allEmployesIds) {
            $q->whereIn('employe_id', $allEmployesIds);
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