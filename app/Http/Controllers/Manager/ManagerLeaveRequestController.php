<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employe;
use App\Services\LeaveRequestService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\LeaveApproval;
use App\Services\LeaveBalanceService;
use App\Models\LeaveType;


class ManagerLeaveRequestController extends Controller
{
    protected $leaveRequestService;
    protected $notificationService;

    public function __construct(
        LeaveRequestService $leaveRequestService,
        NotificationService $notificationService
    ) {
        $this->leaveRequestService = $leaveRequestService;
        $this->notificationService = $notificationService;
    }

    /**
     * Afficher les demandes en attente (PAS les brouillons)
     */
   public function index(Request $request)
{
    $user = auth()->user();
    $approverEmployeeId = $user->employee_id ?? null;

    if (!$approverEmployeeId) {
        abort(403, 'Vous n\'êtes pas lié à un employé.');
    }

    // ✅ Récupérer les approbations en attente (is_current = true) pour ce manager
    $pendingApprovals = LeaveApproval::where('approver_id', $approverEmployeeId)
        ->where('is_current', true)
        ->where('status', 'pending')
        ->with(['leaveRequest' => function($q) {
            $q->with(['employee', 'leaveType']);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    // On récupère les demandes correspondantes
    $leaveRequests = $pendingApprovals->map(function($approval) {
        return $approval->leaveRequest;
    });

    // Statistiques (toujours en utilisant les approbations de ce manager)
    $pendingCount = LeaveApproval::where('approver_id', $approverEmployeeId)
        ->where('status', 'pending')
        ->where('is_current', true)
        ->count();

    $approvedCount = LeaveApproval::where('approver_id', $approverEmployeeId)
        ->where('status', 'approved')
        ->count();

    $rejectedCount = LeaveApproval::where('approver_id', $approverEmployeeId)
        ->where('status', 'rejected')
        ->count();

    $totalCount = LeaveApproval::where('approver_id', $approverEmployeeId)
        ->count();

    return view('manager.leave_requests.index', compact(
        'leaveRequests',
        'pendingCount',
        'approvedCount',
        'rejectedCount',
        'totalCount',
        'pendingApprovals' // pour avoir les approbations si besoin
    ));
}

    /**
     * Afficher une demande spécifique
     */
    public function show($id)
    {
        $user = auth()->user();
        $managerId = $user->ID ?? $user->id ?? null;

        if (!$managerId) {
            abort(403, 'Utilisateur non authentifié.');
        }

        $leaveRequest = LeaveRequest::with(['employee', 'leaveType', 'attachments'])
            ->whereIn('status', ['pending', 'approved', 'rejected']) // ✅ Exclure les brouillons
            ->findOrFail($id);

        // Vérifier que l'employé est sous ce manager
        $employee = Employe::find($leaveRequest->employee_id);
        if (!$employee || ($employee->manager_id != $managerId && $employee->user_id != $managerId)) {
            abort(403, 'Vous n\'avez pas accès à cette demande.');
        }

        return view('manager.leave_requests.show', compact('leaveRequest'));
    }

    /**
     * Approuver une demande
     */
    // app/Http/Controllers/Manager/ManagerLeaveRequestController.php

// Ajouter les imports


// app/Http/Controllers/Manager/ManagerLeaveRequestController.php



public function approve($id)
{
    try {
        $user = auth()->user();
        $approverEmployeeId = $user->employee_id ?? null;
        
        if (!$approverEmployeeId) {
            throw new \Exception('Vous n\'êtes pas lié à un employé.');
        }

        // Récupérer l'approbation en cours
        $approval = LeaveApproval::where('is_current', true)
            ->where('status', 'pending')
            ->where('approver_id', $approverEmployeeId)
            ->whereHas('leaveRequest', function ($q) {
                $q->whereIn('status', ['pending']);
            })
            ->findOrFail($id);

        // Marquer comme approuvée
        $approval->status = 'approved';
        $approval->approved_at = now();
        $approval->is_current = false;
        $approval->save();

        $leaveRequest = $approval->leaveRequest;

        // Passer à l'étape suivante
        $nextApproval = LeaveApproval::where('leave_request_id', $leaveRequest->id)
            ->where('step_order', '>', $approval->step_order)
            ->orderBy('step_order')
            ->first();

        if ($nextApproval) {
            $nextApproval->is_current = true;
            $nextApproval->save();
            $leaveRequest->status = 'pending';
        } else {
            // ✅ TOUTES LES ÉTAPES SONT APPROUVÉES
            $leaveRequest->status = 'approved';
            $leaveRequest->approved_at = now();
            
            // ✅ DÉDUIRE LE SOLDE (UNIQUEMENT SI LE TYPE DE CONGÉ DÉDUIT LE SOLDE)
            $this->deductBalance($leaveRequest);
        }
        $leaveRequest->save();

        return redirect()->route('manager.leave-requests.index')
            ->with('success', 'Demande approuvée avec succès.');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

/**
 * ✅ DÉDUIRE LE SOLDE DE L'EMPLOYÉ
 * La logique est ici, pas dans le service
 */
private function deductBalance($leaveRequest)
{
    $leaveType = LeaveType::find($leaveRequest->leave_type_id);
    
    // ❌ Ne pas déduire si le type ne déduit pas le solde
    if (!$leaveType || !$leaveType->deducts_balance) {
        \Log::info('Congé approuvé SANS déduction de solde', [
            'request_id' => $leaveRequest->id,
            'employee_id' => $leaveRequest->employee_id,
            'leave_type' => $leaveType?->name ?? 'inconnu',
            'reason' => 'Ce type de congé ne déduit pas le solde (deducts_balance=0)'
        ]);
        return;
    }

    // ✅ Vérifier le solde disponible
    $balanceService = app(LeaveBalanceService::class);
    $availableBalance = $balanceService->getAvailableBalance(
        $leaveRequest->employee_id,
        $leaveRequest->leave_type_id,
        $leaveRequest->period_id
    );

    if ($availableBalance < $leaveRequest->duration) {
        throw new \Exception('Solde insuffisant pour cette demande. (Disponible: ' . $availableBalance . ' jours)');
    }

    // ✅ DÉBITER LE SOLDE
    $balanceService->debitBalance(
        $leaveRequest->employee_id,
        $leaveRequest->leave_type_id,
        $leaveRequest->period_id,
        $leaveRequest->duration,
        $leaveRequest->id,
        'Validation de congé - ' . ($leaveRequest->reason ?? '')
    );

    \Log::info('Solde débité pour le congé approuvé', [
        'request_id' => $leaveRequest->id,
        'employee_id' => $leaveRequest->employee_id,
        'leave_type' => $leaveType?->name ?? 'inconnu',
        'duration' => $leaveRequest->duration
    ]);
}

public function reject(Request $request, $id)
{
    try {
        $request->validate(['rejection_reason' => 'required|string|min:3|max:500']);

        $user = auth()->user();
        $approverEmployeeId = $user->employee_id ?? null;
        if (!$approverEmployeeId) {
            throw new \Exception('Vous n\'êtes pas lié à un employé.');
        }

        $approval = LeaveApproval::where('is_current', true)
            ->where('status', 'pending')
            ->where('approver_id', $approverEmployeeId)
            ->whereHas('leaveRequest', function ($q) {
                $q->whereIn('status', ['pending']);
            })
            ->findOrFail($id);

        $approval->status = 'rejected';
        $approval->rejected_at = now();
        $approval->is_current = false;
        $approval->rejection_reason = $request->rejection_reason;
        $approval->save();

        $leaveRequest = $approval->leaveRequest;
        $leaveRequest->status = 'rejected';
        $leaveRequest->rejection_reason = $request->rejection_reason;
        $leaveRequest->save();

        return redirect()->route('manager.leave-requests.index')
            ->with('success', 'Demande refusée avec succès.');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
}