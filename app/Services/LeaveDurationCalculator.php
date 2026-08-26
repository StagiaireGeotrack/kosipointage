<?php
// app/Services/LeaveDurationCalculator.php

namespace App\Services;

use App\Models\LeaveType;
use App\Models\LeavePolicy;
use App\Models\LeavePolicyAssignment;
use App\Models\CompanyHoliday;
use App\Models\Employe;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class LeaveDurationCalculator
{
    public function calculate(
        int $employeeId,
        int $leaveTypeId,
        string $startDate,
        string $endDate,
        ?int $periodId = null
    ): float {
        Log::info('LeaveDurationCalculator::calculate', [
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        $leaveType = LeaveType::findOrFail($leaveTypeId);
        $policy = $this->getApplicablePolicy($employeeId, $leaveTypeId);
        $employee = Employe::find($employeeId);
        $holidays = $this->getHolidays($employee->SiegeID ?? null, $startDate, $endDate);

        // ✅ Récupérer les week-end days depuis la politique
        $weekendDays = $this->getWeekendDays($policy);
        $method = $policy->calculation_method ?? 'working_days';
        $excludeHolidays = $policy->exclude_holidays ?? true;

        Log::info('Règles appliquées', [
            'method' => $method,
            'weekend_days' => $weekendDays,
            'exclude_holidays' => $excludeHolidays,
            'holidays_count' => count($holidays)
        ]);

        return $this->calculateByMethod(
            $startDate,
            $endDate,
            $method,
            $weekendDays,
            $holidays,
            $excludeHolidays
        );
    }

    /**
     * ✅ Récupérer les jours de week-end depuis la politique
     * Gère à la fois le JSON et le format string
     */
   

    /**
     * ✅ Parser les formats de week-end comme "saturday_sunday"
     */
   

    private function getApplicablePolicy(int $employeeId, int $leaveTypeId): ?LeavePolicy
    {
        $employee = Employe::find($employeeId);
        
        // Priorité 1: Politique individuelle
        $policy = LeavePolicy::whereHas('assignments', function($q) use ($employeeId) {
                $q->where('employee_id', $employeeId)
                  ->where('assignment_type', 'individual')
                  ->where('is_active', true);
            })
            ->where('is_active', true)
            ->first();
        
        if ($policy) {
            Log::info('Politique trouvée: individuelle', ['policy_id' => $policy->id]);
            return $policy;
        }
        
        // Priorité 2: Politique par service
        if ($employee->department_id) {
            $policy = LeavePolicy::whereHas('assignments', function($q) use ($employee) {
                    $q->where('department_id', $employee->department_id)
                      ->where('assignment_type', 'department')
                      ->where('is_active', true);
                })
                ->where('is_active', true)
                ->first();
            
            if ($policy) {
                Log::info('Politique trouvée: service', ['policy_id' => $policy->id]);
                return $policy;
            }
        }
        
        // Priorité 3: Politique par site
        if ($employee->SiegeID) {
            $policy = LeavePolicy::whereHas('assignments', function($q) use ($employee) {
                    $q->where('site_id', $employee->SiegeID)
                      ->where('assignment_type', 'site')
                      ->where('is_active', true);
                })
                ->where('is_active', true)
                ->first();
            
            if ($policy) {
                Log::info('Politique trouvée: site', ['policy_id' => $policy->id]);
                return $policy;
            }
        }
        
        // Priorité 4: Politique par défaut
        $policy = LeavePolicy::where('is_default', true)
            ->where('is_active', true)
            ->first();
        
        if ($policy) {
            Log::info('Politique trouvée: défaut', ['policy_id' => $policy->id]);
            return $policy;
        }
        
        // Fallback: créer une politique temporaire
        Log::warning('Aucune politique trouvée, création d\'une politique par défaut');
        return $this->createDefaultPolicy($leaveTypeId);
    }

    private function calculateByMethod(
        string $startDate,
        string $endDate,
        string $method,
        array $weekendDays,
        array $holidays,
        bool $excludeHolidays
    ): float {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        if ($start->isSameDay($end)) {
            return $this->isWorkingDay($start, $weekendDays, $holidays, $excludeHolidays) ? 1.0 : 0.0;
        }
        
        $duration = 0;
        $period = CarbonPeriod::create($start, $end);
        
        foreach ($period as $date) {
            if ($this->isWorkingDay($date, $weekendDays, $holidays, $excludeHolidays)) {
                $duration++;
            }
        }
        
        Log::info('Calcul terminé', ['duration' => $duration]);
        
        return round($duration, 1);
    }

    private function isWorkingDay(
        Carbon $date,
        array $weekendDays,
        array $holidays,
        bool $excludeHolidays
    ): bool {
        $dayName = strtolower($date->format('l'));
        $dateKey = $date->format('Y-m-d');
        
        if ($excludeHolidays && in_array($dateKey, $holidays)) {
            Log::debug('Jour férié exclu', ['date' => $dateKey]);
            return false;
        }
        
        if (in_array($dayName, $weekendDays)) {
            Log::debug('Week-end exclu', ['date' => $dateKey, 'day' => $dayName]);
            return false;
        }
        
        return true;
    }

    private function getHolidays(?int $siteId, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $holidays = CompanyHoliday::where('is_active', true)
            ->where(function($q) use ($siteId) {
                $q->where('site_id', $siteId)
                  ->orWhereNull('site_id');
            })
            ->get()
            ->flatMap(function($holiday) use ($start, $end) {
                $dates = [];
                $holidayDate = Carbon::parse($holiday->date);
                
                if ($holiday->is_recurring) {
                    for ($year = $start->year; $year <= $end->year; $year++) {
                        $date = $holidayDate->copy()->year($year);
                        if ($date->between($start, $end)) {
                            $dates[] = $date->format('Y-m-d');
                        }
                    }
                } else {
                    if ($holidayDate->between($start, $end)) {
                        $dates[] = $holidayDate->format('Y-m-d');
                    }
                }
                
                return $dates;
            })
            ->unique()
            ->toArray();
        
        Log::debug('Jours fériés récupérés', ['count' => count($holidays), 'dates' => $holidays]);
        
        return $holidays;
    }

    /**
     * ✅ Créer une politique par défaut - CORRIGÉ
     */
    


  

    /**
     * ✅ Récupérer les jours de week-end depuis la politique
     * Gère le format ENUM/VARCHAR stocké en base
     */
    private function getWeekendDays($policy): array
    {
        if (!$policy || !$policy->weekend_days) {
            return ['saturday', 'sunday'];
        }

        $weekendDays = $policy->weekend_days;

        // Si c'est déjà un tableau
        if (is_array($weekendDays)) {
            return $weekendDays;
        }

        // Si c'est une chaîne JSON
        if (is_string($weekendDays)) {
            $decoded = json_decode($weekendDays, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            
            // Si c'est une chaîne simple comme "saturday_sunday"
            return $this->parseWeekendString($weekendDays);
        }

        return ['saturday', 'sunday'];
    }

    /**
     * ✅ Parser les différents formats de week-end
     */
    private function parseWeekendString(string $value): array
    {
        return match($value) {
            'saturday_sunday', '["saturday","sunday"]' => ['saturday', 'sunday'],
            'friday_saturday' => ['friday', 'saturday'],
            'sunday_only' => ['sunday'],
            'none' => [],
            default => ['saturday', 'sunday'],
        };
    }

    /**
     * ✅ Créer une politique par défaut - Version adaptée pour VARCHAR
     */
    private function createDefaultPolicy(int $leaveTypeId): LeavePolicy
    {
        // Vérifier si une politique existe déjà pour ce type
        $existing = LeavePolicy::where('leave_type_id', $leaveTypeId)
            ->where('is_default', true)
            ->first();

        if ($existing) {
            return $existing;
        }

        try {
            // ✅ Stocker comme chaîne simple (ENUM ou VARCHAR)
            $policy = LeavePolicy::create([
                'leave_type_id' => $leaveTypeId,
                'name' => 'Politique par défaut (auto-créée)',
                'calculation_method' => 'working_days',
                'weekend_days' => 'saturday_sunday', // ✅ Format ENUM/VARCHAR compatible
                'exclude_holidays' => true,
                'rounding_rule' => 'none',
                'is_default' => true,
                'is_active' => true,
            ]);

            Log::info('Politique par défaut créée', ['policy_id' => $policy->id]);
            return $policy;

        } catch (\Exception $e) {
            Log::error('Erreur création politique par défaut: ' . $e->getMessage());
            
            // Fallback avec une valeur différente
            try {
                $policy = LeavePolicy::create([
                    'leave_type_id' => $leaveTypeId,
                    'name' => 'Politique par défaut (fallback)',
                    'calculation_method' => 'working_days',
                    'weekend_days' => 'saturday_sunday',
                    'exclude_holidays' => 1,
                    'rounding_rule' => 'none',
                    'is_default' => 1,
                    'is_active' => 1,
                ]);
                return $policy;
            } catch (\Exception $e2) {
                Log::error('Erreur fallback: ' . $e2->getMessage());
                throw $e2;
            }
        }
    }

    // ... reste des méthodes ...

}