<?php
// app/Services/LeaveRequestService.php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveRequestAttachment;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveType;
use App\Models\Employe;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeaveRequestService
{
    protected $balanceService;
    protected $notificationService;

    public function __construct(
        LeaveBalanceService $balanceService,
        NotificationService $notificationService
    ) {
        $this->balanceService = $balanceService;
        $this->notificationService = $notificationService;
    }

    /**
     * Créer une demande de congé
     */
    public function createRequest($employeeId, $leaveTypeId, $periodId, $startDate, $endDate, $reason = null, $comment = null)
    {
        // Calculer la durée
        $duration = $this->calculateDuration($startDate, $endDate, $leaveTypeId);

        // Créer la demande avec TOUS les champs
        $request = LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $leaveTypeId,
            'period_id' => $periodId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $duration,
            'status' => 'draft',
            'reason' => $reason,
            'comment' => $comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $request;
    }

    /**
     * Soumettre une demande (passer de draft à pending)
     * 🔔 ENVOIE UNE NOTIFICATION AU MANAGER
     */
    public function submitRequest($requestId)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        // Vérifier que la demande est en brouillon
        if ($request->status !== 'draft') {
            throw new \Exception('Cette demande ne peut pas être soumise.');
        }

        // Vérifier le solde
        $balance = $this->balanceService->getBalance(
            $request->employee_id,
            $request->leave_type_id,
            $request->period_id
        );

        $leaveType = LeaveType::find($request->leave_type_id);

        if ($balance && $balance->remaining < $request->duration) {
            if (!$leaveType || !$leaveType->allow_negative_balance) {
                throw new \Exception('Solde insuffisant pour cette demande.');
            }
        }

        DB::transaction(function () use ($request) {
            $request->status = 'pending';
            $request->save();

            // Mettre à jour le solde en prévisionnel
            $balance = LeaveBalance::where('employee_id', $request->employee_id)
                ->where('leave_type_id', $request->leave_type_id)
                ->where('period_id', $request->period_id)
                ->first();

            if ($balance) {
                $balance->total_pending += $request->duration;
                $balance->save();
            }

            // 🔔 ENVOYER LA NOTIFICATION AU MANAGER
            try {
                $this->notificationService->notifyManager($request);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Approuver une demande
     * 🔔 NOTIFICATION À L'EMPLOYÉ
     */
    public function approveRequest($requestId, $approvedBy, $comment = null)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être approuvée.');
        }

        DB::transaction(function () use ($request, $approvedBy, $comment) {
            // Créer la transaction de débit
            $this->balanceService->debitBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id,
                $request->duration,
                $request->id,
                'Validation de congé - ' . ($request->reason ?? '')
            );

            // Mettre à jour la demande
            $request->status = 'approved';
            $request->approved_by = $approvedBy;
            $request->approved_at = now();
            $request->comment = $comment;
            $request->save();

            // Mettre à jour le solde (retirer du pending)
            $balance = LeaveBalance::where('employee_id', $request->employee_id)
                ->where('leave_type_id', $request->leave_type_id)
                ->where('period_id', $request->period_id)
                ->first();

            if ($balance) {
                $balance->total_pending -= $request->duration;
                $balance->total_taken += $request->duration;
                $balance->remaining = $balance->total_entitled - $balance->total_taken;
                $balance->save();
            }

            // 🔔 NOTIFIER L'EMPLOYÉ QUE SA DEMANDE EST APPROUVÉE
            try {
                $this->notificationService->notifyEmployeeApproved($request);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification d\'approbation: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Rejeter une demande
     * 🔔 NOTIFICATION À L'EMPLOYÉ
     */
   
   public function rejectRequest($requestId, $rejectedBy, $reason)
{
    $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
    
    if ($request->status !== 'pending') {
        throw new \Exception('Cette demande ne peut pas être rejetée.');
    }

    \DB::transaction(function () use ($request, $rejectedBy, $reason) {
        $request->status = 'rejected';
        $request->rejected_by = $rejectedBy;
        $request->rejected_at = now();
        $request->rejection_reason = $reason;
        $request->save();

        // Retirer du pending
        $balance = LeaveBalance::where('employee_id', $request->employee_id)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('period_id', $request->period_id)
            ->first();

        if ($balance) {
            $balance->total_pending -= $request->duration;
            $balance->save();
        }

        // 🔔 NOTIFIER L'EMPLOYÉ
        $this->notificationService->notifyEmployeeRejected($request);
    });

    return $request;
}
    /**
     * Annuler une demande approuvée
     * 🔔 NOTIFICATION À L'EMPLOYÉ
     */
    public function cancelApprovedRequest($requestId)
    {
        $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
        
        if ($request->status !== 'approved') {
            throw new \Exception('Seules les demandes approuvées peuvent être annulées.');
        }

        DB::transaction(function () use ($request) {
            // Créditer le solde
            $this->balanceService->creditBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id,
                $request->duration,
                $request->id,
                'Annulation de congé - ' . ($request->reason ?? '')
            );

            $request->status = 'cancelled';
            $request->save();

            // 🔔 NOTIFIER L'EMPLOYÉ QUE SON CONGÉ EST ANNULÉ
            try {
                $this->notificationService->notifyEmployeeCancelled($request);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification d\'annulation: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Calculer la durée entre deux dates
     */
    public function calculateDuration($startDate, $endDate, $leaveTypeId)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Calculer les jours ouvrés (lundi-vendredi)
        $days = 0;
        $current = $start->copy();
        
        while ($current <= $end) {
            if ($current->isWeekday()) {
                $days++;
            }
            $current->addDay();
        }

        return $days;
    }

    /**
     * Récupérer les demandes d'un employé
     */
    public function getEmployeeRequests($employeeId)
    {
        return LeaveRequest::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer les demandes en attente pour un manager
     */
    public function getPendingRequestsForManager($managerId)
    {
        // Récupérer les employés du manager
        $employeeIds = Employe::where('manager_id', $managerId)
            ->pluck('ID')
            ->toArray();

        return LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Récupérer les demandes par statut
     */
    public function getRequestsByStatus($employeeId, $status)
    {
        return LeaveRequest::where('employee_id', $employeeId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }





    
    /**
     * Vérifier si un employé a des demandes en conflit
     */

    public function hasConflictingRequests($employeeId, $startDate, $endDate, $excludeRequestId = null)
    {
        $query = LeaveRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeRequestId) {
            $query->where('id', '!=', $excludeRequestId);
        }

        return $query->exists();
    }


    
}