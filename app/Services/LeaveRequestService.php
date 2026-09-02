<?php
// app/Services/LeaveRequestService.php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveRequestAttachment;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceTransaction;
use App\Models\LeaveType;
use App\Models\Employe;
use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;
use App\Models\Administration;
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
     * ✅ NE VALIDE PAS LES PIÈCES ICI - SEULEMENT À LA SOUMISSION
     */
    public function createRequest($employeeId, $leaveTypeId, $periodId, $startDate, $endDate, $reason = null, $comment = null, $attachments = [])
    {
        // ✅ Récupérer le type de congé
        $leaveType = LeaveType::findOrFail($leaveTypeId);
        
        // ✅ NE PAS valider les pièces ici - c'est un brouillon
        // Les pièces seront validées à la soumission (submitRequest)

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
     * Mettre à jour une demande de congé (brouillon)
     * ✅ NE VALIDE PAS LES PIÈCES ICI - SEULEMENT À LA SOUMISSION
     */
    public function updateRequest($requestId, $startDate, $endDate, $reason = null, $comment = null, $attachments = [])
    {
        $request = LeaveRequest::with('leaveType')->findOrFail($requestId);
        
        // ✅ Vérifier que c'est un brouillon
        if ($request->status !== 'draft') {
            throw new \Exception('Seuls les brouillons peuvent être modifiés.');
        }

        // ✅ NE PAS valider les pièces ici - c'est un brouillon
        // Les pièces seront validées à la soumission

        $duration = $this->durationCalculator->calculate(
            $request->employee_id,
            $request->leave_type_id,
            $startDate,
            $endDate,
            $request->period_id
        );

        $request->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration' => $duration,
            'reason' => $reason,
            'comment' => $comment,
        ]);

        // ✅ Ajouter les nouvelles pièces justificatives si présentes
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
     * ✅ VALIDATION OBLIGATOIRE DES PIÈCES ICI
     * ✅ VÉRIFICATION DU SOLDE UNIQUEMENT SI deducts_balance = TRUE
     * ✅ DÉMARRAGE DU WORKFLOW DE VALIDATION
     */
    public function submitRequest($requestId)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'draft') {
            throw new \Exception('Cette demande ne peut pas être soumise.');
        }

        // ✅ VALIDATION OBLIGATOIRE DES PIÈCES À LA SOUMISSION
        $this->validateRequestAttachments($request);

        // ✅ Récupérer le type de congé
        $leaveType = $request->leaveType;
        $deductsBalance = $leaveType && $leaveType->deducts_balance;

        // ✅ Vérifier le solde UNIQUEMENT si le type DÉDUIT le solde
        if ($deductsBalance) {
            $availableBalance = $this->balanceService->getAvailableBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id
            );

            if ($availableBalance < $request->duration) {
                if (!$leaveType || !$leaveType->allow_negative_balance) {
                    throw new \Exception('Solde insuffisant pour cette demande. (Disponible: ' . $availableBalance . ' jours)');
                }
            }
        } else {
            Log::info('Soumission de congé sans vérification de solde', [
                'request_id' => $request->id,
                'employee_id' => $request->employee_id,
                'leave_type' => $leaveType?->name ?? 'inconnu',
                'deducts_balance' => $deductsBalance,
                'reason' => 'Ce type de congé ne déduit pas le solde (deducts_balance=0)'
            ]);
        }

        // ✅ Récupérer le workflow applicable
        $workflow = $this->getWorkflowForEmployee($request->employee_id);
        $steps = json_decode($workflow->steps, true);
        if (empty($steps)) {
            throw new \Exception('Aucune étape de validation configurée dans le workflow.');
        }

        $firstStep = $steps[0];
        $responsibles = $this->getResponsiblesForStep($request->employee, $firstStep);

        DB::transaction(function () use ($request, $workflow, $firstStep, $responsibles) {
            $request->status = 'pending';
            $request->workflow_id = $workflow->id;       // À ajouter dans la migration
            $request->workflow_step = 0;                 // Index de l'étape en cours
            $request->save();

            // Envoyer les notifications aux responsables de la première étape
            $this->notifyStep($request, $firstStep, $responsibles);
        });

        return $request;
    }

    /**
     * Approuver une demande (pending → progression ou approved)
     * ✅ VALIDATION OBLIGATOIRE DES PIÈCES ICI
     * ✅ AVANCEMENT DU WORKFLOW
     */
    public function approveRequest($requestId, $approvedBy, $comment = null)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'pending') {
            throw new \Exception('Cette demande ne peut pas être approuvée.');
        }

        // ✅ VALIDATION OBLIGATOIRE DES PIÈCES À L'APPROBATION
        $this->validateRequestAttachments($request);

        // Récupérer le workflow et l'étape courante
        $workflow = LeaveWorkflow::find($request->workflow_id);
        if (!$workflow) {
            throw new \Exception('Workflow introuvable.');
        }
        $steps = json_decode($workflow->steps, true);
        $currentStepIndex = $request->workflow_step ?? 0;
        $currentStep = $steps[$currentStepIndex] ?? null;

        if (!$currentStep) {
            throw new \Exception('Étape de validation invalide.');
        }

        // Vérifier que l'approbateur a le rôle requis pour cette étape
        $this->checkApproverRole($request, $currentStep, $approvedBy);

        // Déterminer si c'est la dernière étape
        $nextStepIndex = $currentStepIndex + 1;
        $isLastStep = !isset($steps[$nextStepIndex]);

        DB::transaction(function () use ($request, $approvedBy, $comment, $isLastStep, $nextStepIndex, $steps) {
            if ($isLastStep) {
                // Dernière étape : validation finale, on débite le solde (si le type le permet)
                $this->finalizeApproval($request, $approvedBy, $comment);
            } else {
                // Il reste des étapes : on avance
                $request->workflow_step = $nextStepIndex;
                $request->save();

                // Notifier les responsables de la prochaine étape
                $nextStep = $steps[$nextStepIndex];
                $responsibles = $this->getResponsiblesForStep($request->employee, $nextStep);
                $this->notifyStep($request, $nextStep, $responsibles);
            }
        });

        return $request;
    }

    /**
     * Finalise l'approbation (dernière étape)
     */
    protected function finalizeApproval($request, $approvedBy, $comment)
    {
        $leaveType = $request->leaveType;
        $deductsBalance = $leaveType && $leaveType->deducts_balance;

        if ($deductsBalance) {
            // Vérification solde une dernière fois
            $availableBalance = $this->balanceService->getAvailableBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id
            );
            if ($availableBalance < $request->duration) {
                if (!$leaveType->allow_negative_balance) {
                    throw new \Exception('Solde insuffisant pour cette demande.');
                }
            }

            $this->balanceService->debitBalance(
                $request->employee_id,
                $request->leave_type_id,
                $request->period_id,
                $request->duration,
                $request->id,
                'Validation de congé - ' . ($request->reason ?? '')
            );

            Log::info('Solde débité pour le congé approuvé', [
                'request_id' => $request->id,
                'employee_id' => $request->employee_id,
                'leave_type' => $leaveType?->name ?? 'inconnu',
                'duration' => $request->duration
            ]);
        } else {
            Log::info('Congé approuvé SANS déduction de solde', [
                'request_id' => $request->id,
                'employee_id' => $request->employee_id,
                'leave_type' => $leaveType?->name ?? 'inconnu'
            ]);
        }

        $request->status = 'approved';
        $request->approved_by = $approvedBy;
        $request->approved_at = now();
        $request->comment = $comment;
        $request->save();

        // Notifier l'employé
        $this->notificationService->notifyEmployeeApproved($request);
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
     * ✅ ANNULATION DU DÉBIT UNIQUEMENT SI deducts_balance = TRUE
     */
    public function cancelApprovedRequest($requestId)
    {
        $request = LeaveRequest::with(['employee', 'leaveType'])->findOrFail($requestId);
        
        if ($request->status !== 'approved') {
            throw new \Exception('Seules les demandes approuvées peuvent être annulées.');
        }

        // ✅ Récupérer le type de congé
        $leaveType = $request->leaveType;
        $deductsBalance = $leaveType && $leaveType->deducts_balance;

        // ✅ Si le type ne DÉDUIT PAS le solde, pas besoin d'annuler
        if (!$deductsBalance) {
            $request->status = 'cancelled';
            $request->save();
            
            Log::info('Congé annulé (sans déduction de solde)', [
                'request_id' => $request->id,
                'employee_id' => $request->employee_id,
                'leave_type' => $leaveType?->name ?? 'inconnu'
            ]);
            
            return $request;
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
            $threshold = $leaveType->requires_attachment_after ?? 3;
            
            if ($request->duration > $threshold) {
                $hasAttachments = LeaveRequestAttachment::where('leave_request_id', $request->id)->exists();
                if (!$hasAttachments) {
                    throw new \Exception(
                        'Les congés de plus de ' . $threshold . ' jours nécessitent une pièce justificative.'
                    );
                }
            }
        }
        // 🟢 CAS 3 : requires_attachment = 'never' → Aucune pièce requise (ne rien faire)
    }

    /**
     * ✅ VÉRIFIER SI UNE DEMANDE A TOUTES LES PIÈCES REQUISES
     */
    public function hasRequiredAttachments($requestId)
    {
        $request = LeaveRequest::with('leaveType')->findOrFail($requestId);
        
        if (!$request->leaveType) {
            return true;
        }

        $leaveType = $request->leaveType;
        
        if ($leaveType->requires_attachment === 'always') {
            return LeaveRequestAttachment::where('leave_request_id', $requestId)->exists();
        }

        if ($leaveType->requires_attachment === 'after_duration') {
            $threshold = $leaveType->requires_attachment_after ?? 3;
            if ($request->duration > $threshold) {
                return LeaveRequestAttachment::where('leave_request_id', $requestId)->exists();
            }
            return true;
        }

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
    // MÉTHODES EXISTANTES (conservées)
    // ============================================

    public function getEmployeeRequests($employeeId)
    {
        return LeaveRequest::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

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

    public function getRequestsByStatus($employeeId, $status)
    {
        return LeaveRequest::where('employee_id', $employeeId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

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

    public function getEmployeeBalance($employeeId, $leaveTypeId, $periodId)
    {
        return $this->balanceService->getBalance($employeeId, $leaveTypeId, $periodId);
    }

    public function getEmployeeAvailableBalance($employeeId, $leaveTypeId, $periodId)
    {
        return $this->balanceService->getAvailableBalance($employeeId, $leaveTypeId, $periodId);
    }

    /**
     * ✅ VÉRIFIER SI UN TYPE DE CONGÉ DÉDUIT LE SOLDE
     */
    public function doesLeaveTypeDeductBalance($leaveTypeId)
    {
        $leaveType = LeaveType::find($leaveTypeId);
        return $leaveType && $leaveType->deducts_balance;
    }

    /**
     * ✅ OBTENIR LE STATUT DE DÉDUCTION POUR UNE DEMANDE
     */
    public function getDeductionStatus($requestId)
    {
        $request = LeaveRequest::with('leaveType')->findOrFail($requestId);
        $leaveType = $request->leaveType;
        
        return [
            'request_id' => $request->id,
            'leave_type' => $leaveType?->name ?? 'N/A',
            'deducts_balance' => $leaveType ? $leaveType->deducts_balance : false,
            'will_deduct' => $leaveType && $leaveType->deducts_balance,
            'explanation' => $leaveType && $leaveType->deducts_balance 
                ? 'Ce type de congé déduira le solde à l\'approbation' 
                : 'Ce type de congé ne déduit PAS le solde'
        ];
    }

    // ============================================
    // MÉTHODES DE WORKFLOW (ajoutées)
    // ============================================

    /**
     * Récupère le workflow applicable pour un employé
     * Priorité : site spécifique → global → défaut
     */
    protected function getWorkflowForEmployee(int $employeeId): LeaveWorkflow
    {
        $employee = Employe::find($employeeId);
        $siteId = $employee->SiegeID;

        // 1. Workflow spécifique au site
        $siteSetting = SiteLeaveWorkflowSetting::where('site_id', $siteId)
                        ->where('is_active', true)
                        ->with('workflow')
                        ->first();

        if ($siteSetting && $siteSetting->workflow) {
            return $siteSetting->workflow;
        }

        // 2. Workflow global par défaut
        $default = LeaveWorkflow::where('is_default', true)
                    ->where('is_active', true)
                    ->first();

        if ($default) {
            return $default;
        }

        throw new \Exception('Aucun workflow de validation configuré pour cet employé.');
    }

    /**
     * Récupère les IDs des responsables (table administration) pour une étape donnée
     */
    protected function getResponsiblesForStep(Employe $employee, array $step): array
    {
        $role = $step['role'] ?? 'manager';
        $adminIds = [];

        switch ($role) {
            case 'manager':
                $managerId = $employee->manager_id;
                if ($managerId) {
                    $manager = Employe::find($managerId);
                    if ($manager && $manager->user_id) {
                        $adminIds[] = $manager->user_id;
                    } else {
                        // Fallback : chercher un admin manager du même site
                        $admin = Administration::where('SiegeID', $employee->SiegeID)
                                    ->where('IsManager', 1)
                                    ->first();
                        if ($admin) $adminIds[] = $admin->ID;
                    }
                }
                break;

            case 'hr':
                // Chercher un admin avec IsManager = 1 sur le même site (ou un rôle RH dédié)
                $admins = Administration::where('SiegeID', $employee->SiegeID)
                            ->where('IsManager', 1)
                            ->get();
                foreach ($admins as $admin) {
                    $adminIds[] = $admin->ID;
                }
                break;

            case 'director':
                // Manager du manager
                $manager = Employe::find($employee->manager_id);
                if ($manager && $manager->manager_id) {
                    $director = Employe::find($manager->manager_id);
                    if ($director && $director->user_id) {
                        $adminIds[] = $director->user_id;
                    }
                }
                break;

            default:
                // Rôle non reconnu, on lève une exception
                throw new \Exception("Rôle d'approbation non géré : {$role}");
        }

        return array_unique($adminIds);
    }

    /**
     * Vérifie que l'approbateur a le rôle requis pour l'étape
     */
    protected function checkApproverRole(LeaveRequest $request, array $step, $approverId)
    {
        $authorizedIds = $this->getResponsiblesForStep($request->employee, $step);
        if (!in_array($approverId, $authorizedIds)) {
            throw new \Exception('Vous n\'êtes pas autorisé à valider cette étape.');
        }
    }

    /**
     * Envoie une notification aux responsables de l'étape
     */
    protected function notifyStep(LeaveRequest $request, array $step, array $adminIds)
    {
        $message = "Nouvelle demande de congé en attente de validation (" . ($step['label'] ?? 'étape') . ")";
        foreach ($adminIds as $adminId) {
            // Créer une notification dans la table notifications
            // Vous pouvez adapter selon votre NotificationService
            $this->notificationService->notifyManager($request, $adminId, $message);
        }
    }
}