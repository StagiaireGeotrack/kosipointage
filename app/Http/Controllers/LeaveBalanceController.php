<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeavePolicy;
use App\Services\LeaveCalculator;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function calculate(Request $request, $policyId)
    {
        $policy = LeavePolicy::with(['leaveType.calculationRules', 'values.ruleField'])
            ->findOrFail($policyId);

        // Récupère l'employé (à adapter selon ton système)
        $employeeId = $request->input('employee_id');
        $employee = $employeeId
            ? Employee::findOrFail($employeeId)
            : auth()->user(); // ou un employé par défaut

        $calculator = new LeaveCalculator();
        $result = $calculator->calculate($policy, $employee);

        return response()->json([
            'employee' => [
                'id' => $employee->id ?? null,
                'name' => $employee->name ?? 'N/A',
                'anciennete_annees' => isset($employee->date_embauche)
                    ? \Carbon\Carbon::parse($employee->date_embauche)->diffInYears(now())
                    : 0,
            ],
            'leave_type' => $policy->leaveType->name,
            'policy_values' => $policy->values->mapWithKeys(
                fn($v) => [$v->ruleField->field_key => $v->value]
            ),
            'calculated' => $result,
        ]);
    }
}