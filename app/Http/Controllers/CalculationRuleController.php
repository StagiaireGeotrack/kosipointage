<?php

namespace App\Http\Controllers;

use App\Models\CalculationRule;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class CalculationRuleController extends Controller
{
    public function index(LeaveType $leaveType)
    {
        $rules = $leaveType->calculationRules->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'formula' => $r->formula,
            'output_variable' => $r->output_variable,
            'is_active' => $r->is_active,
            'sort_order' => $r->sort_order,
        ]);

        // Variables disponibles pour l'aide
        $variables = [
            'anciennete_annees' => 'Ancienneté en années',
            'anciennete_mois' => 'Ancienneté en mois',
            'mois_travailles' => 'Mois travaillés dans l\'année',
            'mois_annee' => '12 (fixe)',
            'salaire_brut' => 'Salaire brut',
            'salaire_net' => 'Salaire net',
            'nombre_enfants' => 'Nombre d\'enfants',
            'date_jour' => 'Date du jour (YYYY-MM-DD)',
        ];

        // Ajoute les field_keys comme variables
        foreach ($leaveType->ruleFields as $rf) {
            $variables[$rf->field_key] = $rf->label . ' (policy)';
        }

        return view('conges.leave_types.calculation_rules', compact('leaveType', 'rules', 'variables'));
    }

    public function store(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'formula' => 'required|string|max:2000',
            'output_variable' => 'required|string|max:50|regex:/^[a-z0-9_]+$/',
            'is_active' => 'boolean',
        ]);

        $maxOrder = $leaveType->calculationRules()->max('sort_order') ?? 0;

        $rule = $leaveType->calculationRules()->create([
            'name' => $validated['name'],
            'formula' => $validated['formula'],
            'output_variable' => $validated['output_variable'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $maxOrder + 1,
        ]);

        return response()->json([
            'message' => 'Formule créée.',
            'rule' => $rule
        ], 201);
    }

    public function update(Request $request, CalculationRule $calculationRule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'formula' => 'required|string|max:2000',
            'output_variable' => 'required|string|max:50|regex:/^[a-z0-9_]+$/',
            'is_active' => 'boolean',
        ]);

        $calculationRule->update($validated);

        return response()->json([
            'message' => 'Formule mise à jour.',
            'rule' => $calculationRule
        ]);
    }

    public function destroy(CalculationRule $calculationRule)
    {
        $calculationRule->delete();
        return response()->json(['message' => 'Formule supprimée.']);
    }

    public function testFormula(Request $request)
    {
        $formula = $request->input('formula');
        $variables = $request->input('variables', []);

        try {
            $engine = new \App\Services\FormulaEngine();
            $engine->setVariables($variables);
            $result = $engine->evaluate($formula);

            return response()->json([
                'success' => true,
                'result' => $result
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}