<?php

namespace App\Services;

use App\Models\LeavePolicy;
use Carbon\Carbon;

class LeaveCalculator
{
    public function calculate(LeavePolicy $policy, $employee): array
    {
        $type = $policy->leaveType;
        $formulas = $type->calculationRules()->where('is_active', true)->get();

        if ($formulas->isEmpty()) {
            return ['_error' => 'Aucune formule pour ce type'];
        }

        // Valeurs de la policy
        $policyValues = [];
        foreach ($policy->values as $pv) {
            $val = $pv->value;
            if (is_numeric($val)) $val = (float) $val;
            elseif ($val === '1' || $val === true) $val = true;
            elseif ($val === '0' || $val === false || $val === '') $val = false;
            $policyValues[$pv->ruleField->field_key] = $val;
        }

        // Variables système employé
        $systemVars = $this->buildSystemVariables($employee);

        $allVars = array_merge($systemVars, $policyValues);

        $engine = new FormulaEngine();
        $engine->setVariables($allVars);

        $results = [];
        foreach ($formulas as $f) {
            try {
                $results[$f->output_variable] = $engine->evaluate($f->formula);
            } catch (\Throwable $e) {
                $results[$f->output_variable] = null;
                $results['_errors'][] = $f->name . ': ' . $e->getMessage();
            }
        }

        return $results;
    }

    private function buildSystemVariables($employee): array
    {
        $now = Carbon::now();
        $hireDate = isset($employee->date_embauche) ? Carbon::parse($employee->date_embauche) : $now;

        return [
            'anciennete_annees'  => (float) $hireDate->diffInYears($now),
            'anciennete_mois'    => (float) $hireDate->diffInMonths($now),
            'date_jour'          => $now->format('Y-m-d'),
            'mois_travailles'    => (int) $now->month,
            'mois_annee'         => 12,
            'salaire_brut'       => (float) ($employee->salaire_brut ?? 0),
            'salaire_net'        => (float) ($employee->salaire_net ?? 0),
            'nombre_enfants'     => (int) ($employee->nombre_enfants ?? 0),
        ];
    }
}