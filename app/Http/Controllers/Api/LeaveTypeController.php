<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        return LeaveType::all();
    }

    public function store(Request $request)
    {
        // logique de création
    }

    public function show(LeaveType $leaveType)
    {
        return $leaveType;
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        // logique de mise à jour
    }

    public function destroy(LeaveType $leaveType)
    {
        // logique de suppression
    }
}