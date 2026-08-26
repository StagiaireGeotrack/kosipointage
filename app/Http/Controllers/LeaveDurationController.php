<?php
// app/Http/Controllers/Employe/LeaveRequestController.php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveBalance;
use App\Models\Employe;
use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;
use App\Services\LeaveDurationCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequestAttachment;
use Illuminate\Support\Facades\Storage;

class LeaveRequestController extends Controller
{
    protected $leaveRequestService;
    protected $balanceService;
    protected $durationCalculator; // Ajouter cette propriété

    public function __construct(
        LeaveRequestService $leaveRequestService,
        LeaveBalanceService $balanceService,
        LeaveDurationCalculator $durationCalculator // Injecter le calculateur
    ) {
        $this->leaveRequestService = $leaveRequestService;
        $this->balanceService = $balanceService;
        $this->durationCalculator = $durationCalculator; // Stocker
    }

    // ... vos autres méthodes ...

    /**
     * ✅ NOUVELLE MÉTHODE : Calcul AJAX de la durée
     * Utilise la session de l'utilisateur connecté
     */
    public function calculateDurationAjax(Request $request)
    {
        try {
            // Récupérer l'employé connecté
            $employee = $this->getEmployee();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employé non trouvé'
                ], 401);
            }

            // Valider les paramètres
            $request->validate([
                'leave_type_id' => 'required|exists:leave_types,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'period_id' => 'nullable|exists:leave_periods,id'
            ]);

            // Calculer la durée avec les règles configurées
            $duration = $this->durationCalculator->calculate(
                $employee->ID,
                $request->leave_type_id,
                $request->start_date,
                $request->end_date,
                $request->period_id
            );

            return response()->json([
                'success' => true,
                'duration' => $duration,
                'duration_formatted' => number_format($duration, 1) . ' jour' . ($duration > 1 ? 's' : ''),
                'details' => [
                    'employee' => $employee->FirstName . ' ' . $employee->LastName,
                    'method' => 'jours ouvrés (selon politique)',
                    'weekends_excluded' => true,
                    'holidays_excluded' => true
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erreur calcul durée AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Votre méthode getEmployee() existante
    private function getEmployee()
    {
        if (session()->has('employee_id')) {
            $employee = Employe::find(session('employee_id'));
            if ($employee) {
                return $employee;
            }
        }
        
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        if ($user instanceof Employe) {
            session(['employee_id' => $user->ID]);
            return $user;
        }
        
        if (isset($user->employee) && $user->employee) {
            session(['employee_id' => $user->employee->ID]);
            return $user->employee;
        }
        
        if (isset($user->employee_id) && $user->employee_id) {
            $employee = Employe::find($user->employee_id);
            if ($employee) {
                session(['employee_id' => $employee->ID]);
                return $employee;
            }
        }
        
        $userId = $user->ID ?? $user->id ?? null;
        if ($userId) {
            $employee = Employe::where('user_id', $userId)->first();
            if ($employee) {
                session(['employee_id' => $employee->ID]);
                return $employee;
            }
        }
        
        $employee = Employe::where('Actived', 1)->first();
        if ($employee) {
            session(['employee_id' => $employee->ID]);
            return $employee;
        }
        
        return null;
    }
}