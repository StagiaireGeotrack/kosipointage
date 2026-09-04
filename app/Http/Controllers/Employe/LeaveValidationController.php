<?php
// app/Http/Controllers/Employe/LeaveValidationController.php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveValidationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:employe');
    }

    public function index()
    {
        $employe = Auth::guard('employe')->user();
        if (!$employe) abort(403);

        // Demandes en attente de validation
        $pendingApprovals = LeaveApproval::where('approver_id', $employe->ID)
            ->where('is_current', true)
            ->where('status', 'pending')
            ->with(['leaveRequest' => fn($q) => $q->with(['employee', 'leaveType', 'period', 'attachments', 'approvals'])])
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        // Historique des validations
        $history = LeaveApproval::where('approver_id', $employe->ID)
            ->where('status', '!=', 'pending')
            ->with(['leaveRequest' => fn($q) => $q->with(['employee', 'leaveType', 'attachments', 'approvals'])])
            ->orderBy('updated_at', 'desc')
            ->paginate(15, ['*'], 'history_page');

        return view('employes.validations.index', compact('pendingApprovals', 'history'));
    }

    public function approve($id)
    {
        $approval = LeaveApproval::findOrFail($id);
        $employe = Auth::guard('employe')->user();

        if ($approval->approver_id != $employe->ID || $approval->status != 'pending' || !$approval->is_current) {
            abort(403);
        }

        // ✅ VÉRIFIER LES PIÈCES JOINTES
        $this->validateAttachments($approval->leaveRequest);

        $approval->status = 'approved';
        $approval->approved_at = now();
        $approval->is_current = false;
        $approval->save();

        $leaveRequest = $approval->leaveRequest;
        $nextApproval = LeaveApproval::where('leave_request_id', $leaveRequest->id)
            ->where('step_order', '>', $approval->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextApproval) {
            $nextApproval->is_current = true;
            $nextApproval->save();
            $leaveRequest->status = 'pending';
            $message = 'Demande transmise à l\'étape suivante.';
        } else {
            // ✅ TOUTES LES ÉTAPES SONT APPROUVÉES
            $leaveRequest->status = 'approved';
            $leaveRequest->approved_at = now();
            
            // ✅ DÉDUIRE LE SOLDE
            $this->deductBalance($leaveRequest);
            $message = 'Demande approuvée avec succès.';
        }
        $leaveRequest->save();

        return redirect()->route('employe.validations.index')
            ->with('success', $message);
    }

    /**
     * ✅ REJETER UNE DEMANDE - NE BLOQUE PAS LE WORKFLOW (sauf dernière étape)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:500'
        ]);

        $approval = LeaveApproval::findOrFail($id);
        $employe = Auth::guard('employe')->user();

        if ($approval->approver_id != $employe->ID || $approval->status != 'pending' || !$approval->is_current) {
            abort(403);
        }

        // ✅ Marquer l'étape comme rejetée
        $approval->status = 'rejected';
        $approval->rejected_at = now();
        $approval->is_current = false;
        $approval->rejection_reason = $request->input('rejection_reason');
        $approval->save();

        $leaveRequest = $approval->leaveRequest;

        // ✅ Vérifier s'il y a une étape suivante
        $nextApproval = LeaveApproval::where('leave_request_id', $leaveRequest->id)
            ->where('step_order', '>', $approval->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextApproval) {
            // ✅ S'il y a une étape suivante, on continue le workflow
            $nextApproval->is_current = true;
            $nextApproval->save();
            $leaveRequest->status = 'pending';
            $message = 'Demande transmise à l\'étape suivante avec votre avis.';
        } else {
            // ✅ Dernière étape : la demande est définitivement rejetée
            $leaveRequest->status = 'rejected';
            $leaveRequest->rejection_reason = $request->input('rejection_reason');
            $message = 'Demande définitivement rejetée.';
        }
        $leaveRequest->save();

        return redirect()->route('employe.validations.index')
            ->with('success', $message);
    }

    /**
     * ✅ AFFICHER LE DÉTAIL D'UNE DEMANDE
     */
    public function show($id)
    {
        $employe = Auth::guard('employe')->user();
        if (!$employe) abort(403);

        $leaveRequest = LeaveRequest::with([
            'employee', 
            'leaveType', 
            'period', 
            'attachments',
            'approvals' => function($q) {
                $q->orderBy('step_order', 'asc');
            },
            'approvals.approver'
        ])->findOrFail($id);

        // Vérifier que l'employé est bien l'approbateur d'une des étapes
        $isApprover = $leaveRequest->approvals->contains('approver_id', $employe->ID);
        if (!$isApprover) {
            abort(403, 'Vous n\'avez pas accès à cette demande.');
        }

        // Récupérer l'étape actuelle
        $currentApproval = $leaveRequest->approvals->where('is_current', true)->first();

        return view('employes.validations.show', compact('leaveRequest', 'currentApproval'));
    }

    /**
     * ✅ VALIDER LES PIÈCES JOINTES
     */
    private function validateAttachments($leaveRequest)
    {
        if (!$leaveRequest || !$leaveRequest->leaveType) {
            return;
        }

        $leaveType = $leaveRequest->leaveType;
        $hasAttachments = $leaveRequest->attachments()->exists();

        if ($leaveType->requires_attachment === 'always') {
            if (!$hasAttachments) {
                throw new \Exception(
                    'Le type de congé "' . $leaveType->name . '" requiert une pièce justificative obligatoire.'
                );
            }
        }

        if ($leaveType->requires_attachment === 'after_duration') {
            $threshold = $leaveType->requires_attachment_after ?? 3;
            if ($leaveRequest->duration > $threshold && !$hasAttachments) {
                throw new \Exception(
                    'Les congés de plus de ' . $threshold . ' jours nécessitent une pièce justificative.'
                );
            }
        }
    }

    /**
     * ✅ DÉDUIRE LE SOLDE
     */
    private function deductBalance($leaveRequest)
    {
        $leaveType = LeaveType::find($leaveRequest->leave_type_id);
        
        if (!$leaveType || !$leaveType->deducts_balance) {
            \Log::info('Congé approuvé SANS déduction de solde (LeaveValidation)', [
                'request_id' => $leaveRequest->id,
                'employee_id' => $leaveRequest->employee_id,
                'leave_type' => $leaveType?->name ?? 'inconnu',
                'reason' => 'Ce type de congé ne déduit pas le solde (deducts_balance=0)'
            ]);
            return;
        }

        $balanceService = app(LeaveBalanceService::class);
        $availableBalance = $balanceService->getAvailableBalance(
            $leaveRequest->employee_id,
            $leaveRequest->leave_type_id,
            $leaveRequest->period_id
        );

        if ($availableBalance < $leaveRequest->duration) {
            \Log::warning('Solde insuffisant pour la déduction (LeaveValidation)', [
                'request_id' => $leaveRequest->id,
                'employee_id' => $leaveRequest->employee_id,
                'available' => $availableBalance,
                'required' => $leaveRequest->duration
            ]);
            throw new \Exception('Solde insuffisant pour cette demande. (Disponible: ' . $availableBalance . ' jours)');
        }

        $balanceService->debitBalance(
            $leaveRequest->employee_id,
            $leaveRequest->leave_type_id,
            $leaveRequest->period_id,
            $leaveRequest->duration,
            $leaveRequest->id,
            'Validation de congé - ' . ($leaveRequest->reason ?? '')
        );

        \Log::info('Solde débité pour le congé approuvé (LeaveValidation)', [
            'request_id' => $leaveRequest->id,
            'employee_id' => $leaveRequest->employee_id,
            'leave_type' => $leaveType?->name ?? 'inconnu',
            'duration' => $leaveRequest->duration
        ]);
    }
}