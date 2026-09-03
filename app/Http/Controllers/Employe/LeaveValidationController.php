<?php
// app/Http/Controllers/Employe/LeaveValidationController.php

namespace App\Http\Controllers\Employe;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
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
            ->with(['leaveRequest' => fn($q) => $q->with(['employee', 'leaveType', 'period'])])
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        // Historique des validations
        $history = LeaveApproval::where('approver_id', $employe->ID)
            ->where('status', '!=', 'pending')
            ->with(['leaveRequest' => fn($q) => $q->with(['employee', 'leaveType'])])
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
        } else {
            $leaveRequest->status = 'approved';
            $leaveRequest->approved_at = now();
        }
        $leaveRequest->save();

        return redirect()->route('employe.validations.index')
            ->with('success', 'Demande approuvée avec succès.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $approval = LeaveApproval::findOrFail($id);
        $employe = Auth::guard('employe')->user();

        if ($approval->approver_id != $employe->ID || $approval->status != 'pending' || !$approval->is_current) {
            abort(403);
        }

        $approval->status = 'rejected';
        $approval->rejected_at = now();
        $approval->is_current = false;
        $approval->rejection_reason = $request->input('reason');
        $approval->save();

        $leaveRequest = $approval->leaveRequest;
        $leaveRequest->status = 'rejected';
        $leaveRequest->rejection_reason = $request->input('reason');
        $leaveRequest->save();

        return redirect()->route('employe.validations.index')
            ->with('success', 'Demande rejetée.');
    }
}