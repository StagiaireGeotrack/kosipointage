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
    protected $durationCalculator;

    public function __construct(
        LeaveBalanceService $balanceService,
        NotificationService $notificationService,
        LeaveDurationCalculator $durationCalculator
    ) {
        $this->balanceService = $balanceService;
        $this->notificationService = $notificationService;
        $this->durationCalculator = $durationCalculator;
    }

    /**
     * Créer une demande de congé (brouillon)
     * ✅ AUCUN IMPACT SUR LE SOLDE
     */
    public function createRequest($employeeId, $leaveTypeId, $periodId, $startDate, $endDate, $reason = null, $comment = null)
    {
        $duration = $this->durationCalculator->calculate(
            $employeeId,
            $leaveTypeId,
            $startDate,
            $endDate,
            $periodId
        );

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
        ]);

        return $request;
    }

    /**
     * Soumettre une demande (draft → pending)
     * ✅ AUCUN IMPACT SUR LE SOLDE
     * ✅ NE PAS créer de transaction en attente
     */
    public function submitRequest($requestId)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'draft') {
            throw new \Exception('Cette demande ne peut pas être soumise.');
        }

        // Vérifier le solde disponible
        $availableBalance = $this->balanceService->getAvailableBalance(
            $request->employee_id,
            $request->leave_type_id,
            $request->period_id
        );

        $leaveType = LeaveType::find($request->leave_type_id);

        if ($availableBalance < $request->duration) {
            if (!$leaveType || !$leaveType->allow_negative_balance) {
                throw new \Exception('Solde insuffisant pour cette demande. (Disponible: ' . $availableBalance . ' jours)');
            }
        }

        DB::transaction(function () use ($request) {
            $request->status = 'pending';
            $request->save();

            // ✅ NE PAS créer de transaction en attente

            try {
                $this->notificationService->notifyManager($request);
            } catch (\Exception $e) {
                Log::error('Erreur notification: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Approuver une demande (pending → approved)
     * ✅ DÉBITE LE SOLDE UNIQUEMENT ICI
     */
    public function approveRequest($requestId, $approvedBy, $comment = null)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être approuvée.');
        }

        // Vérifier le solde disponible
        $availableBalance = $this->balanceService->getAvailableBalance(
            $request->employee_id,
            $request->leave_type_id,
            $request->period_id
        );

        $leaveType = LeaveType::find($request->leave_type_id);

        if ($availableBalance < $request->duration) {
            if (!$leaveType || !$leaveType->allow_negative_balance) {
                throw new \Exception('Solde insuffisant pour cette demande. (Disponible: ' . $availableBalance . ' jours)');
            }
        }

        DB::transaction(function () use ($request, $approvedBy, $comment) {
            // ✅ Créer la transaction de débit (UNIQUEMENT à l'approbation)
            $this->balanceService->debitBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id,
                $request->duration,
                $request->id,
                'Validation de congé - ' . ($request->reason ?? '')
            );

            $request->status = 'approved';
            $request->approved_by = $approvedBy;
            $request->approved_at = now();
            $request->comment = $comment;
            $request->save();

            try {
                $this->notificationService->notifyEmployeeApproved($request);
            } catch (\Exception $e) {
                Log::error('Erreur notification approbation: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Rejeter une demande (pending → rejected)
     * ✅ AUCUN IMPACT SUR LE SOLDE
     */
    public function rejectRequest($requestId, $rejectedBy, $reason)
    {
        $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être rejetée.');
        }

        DB::transaction(function () use ($request, $rejectedBy, $reason) {
            $request->status = 'rejected';
            $request->rejected_by = $rejectedBy;
            $request->rejected_at = now();
            $request->rejection_reason = $reason;
            $request->save();

            try {
                $this->notificationService->notifyEmployeeRejected($request);
            } catch (\Exception $e) {
                Log::error('Erreur notification rejet: ' . $e->getMessage());
            }
        });

        return $request;
    }

    /**
     * Annuler une demande approuvée
     * ✅ CRÉDITE LE SOLDE
     */
   // app/Services/LeaveRequestService.php

// app/Services/LeaveRequestService.php

public function cancelApprovedRequest($requestId)
{
    $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
    
    // ✅ Vérifier le statut
    if ($request->status !== 'approved') {
        throw new \Exception('Seules les demandes approuvées peuvent être annulées.');
    }

    // ✅ Vérifier si un crédit existe déjà (vérification plus robuste)
    $existingCredit = LeaveBalanceTransaction::where('reference_id', $request->id)
        ->where('reference_type', 'leave_request')
        ->whereIn('type', ['credit', 'adjustment']) // ✅ inclure adjustment si jamais
        ->where('amount', '>', 0) // ✅ s'assurer que c'est un crédit
        ->first();

    if ($existingCredit) {
        // ✅ Si le crédit existe déjà, on vérifie si la demande est déjà annulée
        if ($request->status === 'cancelled') {
            Log::info('Demande déjà annulée', ['request_id' => $requestId]);
            return $request;
        }
        
        // ✅ Si le crédit existe mais la demande n'est pas annulée, on met juste à jour le statut
        Log::warning('Crédit existant mais demande non annulée, mise à jour du statut', [
            'request_id' => $requestId,
            'transaction_id' => $existingCredit->id
        ]);
        
        $request->status = 'cancelled';
        $request->save();
        
        return $request;
    }

    DB::transaction(function () use ($request) {
        // ✅ Créer le crédit
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

        try {
            $this->notificationService->notifyEmployeeCancelled($request);
        } catch (\Exception $e) {
            Log::error('Erreur notification annulation: ' . $e->getMessage());
        }
    });

    return $request;
}
// app/Services/LeaveRequestService.php

/**
 * Vérifier si une demande peut être annulée
 */
public function canCancelRequest($requestId)
{
    $request = LeaveRequest::find($requestId);
    
    if (!$request) {
        return false;
    }
    
    // ✅ Déjà annulée
    if ($request->status === 'cancelled') {
        return false;
    }
    
    // ✅ Seulement les demandes approuvées ou en attente peuvent être annulées
    if (!in_array($request->status, ['approved', 'pending'])) {
        return false;
    }
    
    // ✅ Vérifier si un crédit existe déjà (pour les demandes approuvées)
    if ($request->status === 'approved') {
        $existingCredit = LeaveBalanceTransaction::where('reference_id', $request->id)
            ->where('reference_type', 'leave_request')
            ->where('type', 'credit')
            ->where('amount', '>', 0)
            ->exists();
            
        if ($existingCredit) {
            return false; // Déjà annulée
        }
    }
    
    return true;
}
// app/Services/LeaveRequestService.php

/**
 * Annuler une demande en attente (sans impact sur le solde)
 */
public function cancelPendingRequest($requestId)
{
    $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
    
    if ($request->status !== 'pending') {
        throw new \Exception('Seules les demandes en attente peuvent être annulées.');
    }

    // ✅ Vérifier si la demande est déjà annulée
    if ($request->status === 'cancelled') {
        return $request;
    }

    DB::transaction(function () use ($request) {
        // ✅ Aucun impact sur le solde pour les demandes en attente
        $request->status = 'cancelled';
        $request->save();

        try {
            $this->notificationService->notifyEmployeeCancelled($request);
        } catch (\Exception $e) {
            Log::error('Erreur notification annulation: ' . $e->getMessage());
        }
    });

    return $request;
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

    /**
     * Obtenir le solde d'un employé pour un type de congé
     */
    public function getEmployeeBalance($employeeId, $leaveTypeId, $periodId)
    {
        return $this->balanceService->getBalance($employeeId, $leaveTypeId, $periodId);
    }

    /**
     * Obtenir le solde disponible d'un employé
     */
    public function getEmployeeAvailableBalance($employeeId, $leaveTypeId, $periodId)
    {
        return $this->balanceService->getAvailableBalance($employeeId, $leaveTypeId, $periodId);
    }
}