<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employe;
use App\Services\LeaveRequestService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $user = auth()->user();
        $managerId = $user->ID ?? $user->id ?? null;

        // Récupérer les employés du manager
        $employees = Employe::where('manager_id', $managerId)
            ->orWhere('user_id', $managerId)
            ->pluck('ID')
            ->toArray();

        // Si aucun employé, prendre tous les employés du site
        if (empty($employees)) {
            $employees = Employe::where('SiegeID', $user->SiegeID)
                ->pluck('ID')
                ->toArray();
        }

        $query = LeaveRequest::whereIn('employee_id', $employees)
            ->with(['employee', 'leaveType']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        $pendingCount = LeaveRequest::whereIn('employee_id', $employees)->where('status', 'pending')->count();
        $approvedCount = LeaveRequest::whereIn('employee_id', $employees)->where('status', 'approved')->count();
        $rejectedCount = LeaveRequest::whereIn('employee_id', $employees)->where('status', 'rejected')->count();
        $totalCount = LeaveRequest::whereIn('employee_id', $employees)->count();

        return view('manager.leave_requests.index', compact(
            'leaveRequests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount'
        ));
    }

    public function show($id)
    {
        $user = auth()->user();
        $managerId = $user->ID ?? $user->id ?? null;

        $leaveRequest = LeaveRequest::with(['employee', 'leaveType', 'attachments'])
            ->findOrFail($id);

        // Vérifier que l'employé est sous ce manager
        $employee = Employe::find($leaveRequest->employee_id);
        if ($employee->manager_id != $managerId && $employee->user_id != $managerId) {
            abort(403, 'Vous n\'avez pas accès à cette demande.');
        }

        return view('manager.leave_requests.show', compact('leaveRequest'));
    }

    public function approve($id)
    {
        try {
            $user = auth()->user();
            $approverId = $user->ID ?? $user->id ?? null;

            $this->leaveRequestService->approveRequest($id, $approverId);

            return redirect()->route('manager.leave-requests.index')
                ->with('success', 'Demande approuvée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $rejecterId = $user->ID ?? $user->id ?? null;

            $request->validate([
                'rejection_reason' => 'required|string|min:3|max:500'
            ]);

            $this->leaveRequestService->rejectRequest($id, $rejecterId, $request->rejection_reason);

            return redirect()->route('manager.leave-requests.index')
                ->with('success', 'Demande refusée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}