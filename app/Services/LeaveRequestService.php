<?php
// app/Services/LeaveRequestService.php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveRequestAttachment;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestService
{
    protected $balanceService;

    public function __construct(LeaveBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
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
            'leave_type_id' => $leaveTypeId,      // <-- AJOUTÉ
            'period_id' => $periodId,              // <-- AJOUTÉ
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
     */
    public function submitRequest($requestId)
    {
        $request = LeaveRequest::findOrFail($requestId);
        
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

        return $request;
    }

    /**
     * Approuver une demande
     */
    public function approveRequest($requestId, $approvedBy, $comment = null)
    {
        $request = LeaveRequest::findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être approuvée.');
        }

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

        return $request;
    }

    /**
     * Rejeter une demande
     */
    public function rejectRequest($requestId, $rejectedBy, $reason)
    {
        $request = LeaveRequest::findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être rejetée.');
        }

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

        return $request;
    }

    /**
     * Annuler une demande approuvée
     */
    public function cancelApprovedRequest($requestId)
    {
        $request = LeaveRequest::findOrFail($requestId);
        
        if ($request->status !== 'approved') {
            throw new \Exception('Seules les demandes approuvées peuvent être annulées.');
        }

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
        $employeeIds = \App\Models\Employe::where('manager_id', $managerId)
            ->pluck('ID')
            ->toArray();

        return LeaveRequest::whereIn('employee_id', $employeeIds)
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}