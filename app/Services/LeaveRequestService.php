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
     */
    public function createRequest($employeeId, $leaveTypeId, $periodId, $startDate, $endDate, $reason = null, $comment = null, $attachments = [])
    {
        // ✅ Récupérer le type de congé
        $leaveType = LeaveType::findOrFail($leaveTypeId);
        
        // ✅ VALIDATION DES PIÈCES SELON LA VALEUR DE requires_attachment
        $this->validateAttachments($leaveType, $attachments);

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

        // ✅ Ajouter les pièces justificatives si présentes
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                LeaveRequestAttachment::create([
                    'leave_request_id' => $request->id,
                    'file_name' => $attachment['name'] ?? $attachment['file_name'],
                    'file_path' => $attachment['path'] ?? $attachment['file_path'],
                    'file_size' => $attachment['size'] ?? $attachment['file_size'] ?? null,
                    'mime_type' => $attachment['type'] ?? $attachment['mime_type'] ?? null,
                    'uploaded_by' => $attachment['uploaded_by'] ?? Auth::user()?->name ?? 'System',
                ]);
            }
        }

        return $request;
    }

    /**
     * Soumettre une demande (draft → pending)
     */
    public function submitRequest($requestId)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'draft') {
            throw new \Exception('Cette demande ne peut pas être soumise.');
        }

        // ✅ VALIDATION DES PIÈCES SELON LA VALEUR DE requires_attachment
        $this->validateRequestAttachments($request);

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
     */
    public function approveRequest($requestId, $approvedBy, $comment = null)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être approuvée.');
        }

        // ✅ VALIDATION DES PIÈCES SELON LA VALEUR DE requires_attachment
        $this->validateRequestAttachments($request);

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
            // Créer la transaction de débit
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
     */
    public function cancelApprovedRequest($requestId)
    {
        $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
        
        if ($request->status !== 'approved') {
            throw new \Exception('Seules les demandes approuvées peuvent être annulées.');
        }

        // ✅ Vérifier si un reversal existe déjà
        $existingReversal = LeaveBalanceTransaction::where('reference_id', $request->id)
            ->where('reference_type', 'leave_request')
            ->where('type', 'reversal')
            ->first();

        if ($existingReversal) {
            if ($request->status !== 'cancelled') {
                $request->status = 'cancelled';
                $request->save();
            }
            return $request;
        }

        // ✅ Vérifier si un ancien crédit existe (compatibilité)
        $existingCredit = LeaveBalanceTransaction::where('reference_id', $request->id)
            ->where('reference_type', 'leave_request')
            ->where('type', 'credit')
            ->where('amount', '>', 0)
            ->first();

        if ($existingCredit) {
            $existingCredit->type = 'reversal';
            $existingCredit->save();
            
            $this->balanceService->updateBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id
            );
            
            if ($request->status !== 'cancelled') {
                $request->status = 'cancelled';
                $request->save();
            }
            
            return $request;
        }

        DB::transaction(function () use ($request) {
            // ✅ Utiliser reverseDebit
            $this->balanceService->reverseDebit(
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

    /**
     * Annuler une demande en attente (sans impact sur le solde)
     */
    public function cancelPendingRequest($requestId)
    {
        $request = LeaveRequest::with(['employee'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Seules les demandes en attente peuvent être annulées.');
        }

        if ($request->status === 'cancelled') {
            return $request;
        }

        DB::transaction(function () use ($request) {
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
     * ✅ VALIDATION DES PIÈCES SELON LA VALEUR DE requires_attachment
     * Les valeurs possibles : 'never', 'always', 'after_duration'
     */
    protected function validateAttachments($leaveType, $attachments)
    {
        // 🔴 Si requires_attachment = 'always' → PIÈCE OBLIGATOIRE
        if ($leaveType->requires_attachment === 'always') {
            if (empty($attachments)) {
                throw new \Exception(
                    'Le type de congé "' . $leaveType->name . '" requiert une pièce justificative obligatoire.'
                );
            }
        }

        // 🟡 Si requires_attachment = 'after_duration' → PIÈCE OBLIGATOIRE AU-DELÀ D'UNE DURÉE
        // (mais ici on n'a pas encore la durée, donc on ne peut pas valider)
        // La validation se fera dans validateRequestAttachments()
        
        // 🟢 Si requires_attachment = 'never' → AUCUNE PIÈCE REQUISE
        // Ne rien faire

        return true;
    }

    /**
     * ✅ VALIDATION DES PIÈCES POUR UNE DEMANDE EXISTANTE
     * Lit la valeur de requires_attachment dans leave_types
     */
    protected function validateRequestAttachments($request)
    {
        $leaveType = $request->leaveType;
        
        if (!$leaveType) {
            return;
        }

        // 🔴 CAS 1 : requires_attachment = 'always' → Pièce toujours obligatoire
        if ($leaveType->requires_attachment === 'always') {
            $hasAttachments = LeaveRequestAttachment::where('leave_request_id', $request->id)->exists();
            if (!$hasAttachments) {
                throw new \Exception(
                    'Le type de congé "' . $leaveType->name . '" requiert une pièce justificative obligatoire.'
                );
            }
        }

        // 🟡 CAS 2 : requires_attachment = 'after_duration' → Pièce obligatoire au-delà d'une durée
        if ($leaveType->requires_attachment === 'after_duration') {
            $threshold = $leaveType->requires_attachment_after ?? 3; // Par défaut 3 jours
            
            if ($request->duration > $threshold) {
                $hasAttachments = LeaveRequestAttachment::where('leave_request_id', $request->id)->exists();
                if (!$hasAttachments) {
                    throw new \Exception(
                        'Les congés de plus de ' . $threshold . ' jours nécessitent une pièce justificative.'
                    );
                }
            }
        }

        // 🟢 CAS 3 : requires_attachment = 'never' → Aucune pièce requise
        // Ne rien faire
    }

    /**
     * ✅ VÉRIFIER SI UNE DEMANDE A TOUTES LES PIÈCES REQUISES
     * Lecture de la valeur de requires_attachment dans leave_types
     */
    public function hasRequiredAttachments($requestId)
    {
        $request = LeaveRequest::with('leaveType')->findOrFail($requestId);
        
        if (!$request->leaveType) {
            return true;
        }

        $leaveType = $request->leaveType;
        
        // 🔴 requires_attachment = 'always' → Pièce toujours obligatoire
        if ($leaveType->requires_attachment === 'always') {
            return LeaveRequestAttachment::where('leave_request_id', $requestId)->exists();
        }

        // 🟡 requires_attachment = 'after_duration' → Pièce obligatoire au-delà d'une durée
        if ($leaveType->requires_attachment === 'after_duration') {
            $threshold = $leaveType->requires_attachment_after ?? 3;
            if ($request->duration > $threshold) {
                return LeaveRequestAttachment::where('leave_request_id', $requestId)->exists();
            }
            return true;
        }

        // 🟢 requires_attachment = 'never' → Aucune pièce requise
        return true;
    }

    /**
     * ✅ OBTENIR LE STATUT DES PIÈCES POUR UNE DEMANDE
     */
    public function getAttachmentsStatus($requestId)
    {
        $request = LeaveRequest::with('leaveType')->findOrFail($requestId);
        $attachments = LeaveRequestAttachment::where('leave_request_id', $requestId)->get();
        $hasAttachments = $attachments->count() > 0;
        
        $required = false;
        $rule = 'never';
        $message = 'Aucune pièce justificative requise';
        
        if ($request->leaveType) {
            $leaveType = $request->leaveType;
            $rule = $leaveType->requires_attachment;
            
            if ($rule === 'always') {
                $required = true;
                $message = $hasAttachments ? '✅ Pièces fournies' : '❌ Pièce justificative obligatoire';
            } elseif ($rule === 'after_duration') {
                $threshold = $leaveType->requires_attachment_after ?? 3;
                if ($request->duration > $threshold) {
                    $required = true;
                    $message = $hasAttachments ? '✅ Pièces fournies' : '❌ Pièce justificative requise (' . $threshold . ' jours)';
                } else {
                    $message = 'Aucune pièce requise (durée inférieure à ' . $threshold . ' jours)';
                }
            }
        }
        
        return [
            'leave_type' => $request->leaveType?->name ?? 'N/A',
            'rule' => $rule,
            'has_attachments' => $hasAttachments,
            'attachments_required' => $required,
            'message' => $message,
            'count' => $attachments->count(),
            'attachments' => $attachments
        ];
    }

    /**
     * ✅ AJOUTER UNE PIÈCE JUSTIFICATIVE
     */
    public function addAttachment($requestId, $fileData)
    {
        $request = LeaveRequest::findOrFail($requestId);
        
        if (in_array($request->status, ['approved', 'rejected', 'cancelled'])) {
            throw new \Exception('Impossible d\'ajouter une pièce à une demande déjà traitée.');
        }

        return LeaveRequestAttachment::create([
            'leave_request_id' => $requestId,
            'file_name' => $fileData['name'] ?? $fileData['file_name'],
            'file_path' => $fileData['path'] ?? $fileData['file_path'],
            'file_size' => $fileData['size'] ?? $fileData['file_size'] ?? null,
            'mime_type' => $fileData['type'] ?? $fileData['mime_type'] ?? null,
            'uploaded_by' => $fileData['uploaded_by'] ?? Auth::user()?->name ?? 'System',
        ]);
    }

    /**
     * ✅ SUPPRIMER UNE PIÈCE JUSTIFICATIVE
     */
    public function deleteAttachment($attachmentId)
    {
        $attachment = LeaveRequestAttachment::findOrFail($attachmentId);
        $request = LeaveRequest::findOrFail($attachment->leave_request_id);
        
        if (in_array($request->status, ['approved', 'rejected', 'cancelled'])) {
            throw new \Exception('Impossible de supprimer une pièce d\'une demande déjà traitée.');
        }

        // Supprimer le fichier physiquement
        $filePath = storage_path('app/public/' . $attachment->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $attachment->delete();
        return true;
    }

    /**
     * ✅ OBTENIR LES PIÈCES JUSTIFICATIVES D'UNE DEMANDE
     */
    public function getAttachments($requestId)
    {
        return LeaveRequestAttachment::where('leave_request_id', $requestId)->get();
    }

    // ============================================
    // MÉTHODES EXISTANTES (inchangées)
    // ============================================

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