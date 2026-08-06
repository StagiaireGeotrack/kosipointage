<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\RuleField;
use Illuminate\Http\Request;

class RuleFieldController extends Controller
{
    /**
     * Affiche la page de configuration des champs d'un type
     */
    public function index(LeaveType $leaveType)
    {
        $fields = $leaveType->ruleFields()->get()->map(fn($f) => [
            'id'            => $f->id,
            'field_key'     => $f->field_key,
            'field_type'    => $f->field_type,
            'label'         => $f->label,
            'default_value' => $f->default_value,
            'options'       => $f->options,
            'validation'    => $f->validation,
            'sort_order'    => $f->sort_order,
        ]);

        return view('conges.leave_types.rule_fields', compact('leaveType', 'fields'));
    }

    /**
     * Ajouter un champ
     */
    public function store(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'field_key'     => 'required|string|max:50|regex:/^[a-z0-9_]+$/|unique:rule_fields,field_key,NULL,id,leave_type_id,' . $leaveType->id,
            'field_type'    => 'required|in:number,boolean,select,checkbox,text,formula',
            'label'         => 'required|string|max:255',
            'options'       => 'nullable|array',
            'options.*.value' => 'required_with:options|string',
            'options.*.label' => 'required_with:options|string',
            'validation'    => 'nullable|array',
            'validation.min'  => 'nullable|numeric',
            'validation.max'  => 'nullable|numeric',
            'validation.required' => 'nullable|boolean',
            'validation.step' => 'nullable|numeric',
        ]);

        $maxOrder = $leaveType->ruleFields()->max('sort_order') ?? 0;

        $field = $leaveType->ruleFields()->create([
            'field_key'     => $validated['field_key'],
            'field_type'    => $validated['field_type'],
            'label'         => $validated['label'],
            'default_value' => $this->parseDefaultValue(
                $request->input('default_value'),
                $validated['field_type']
            ),
            'options'       => $validated['options'] ?? null,
            'validation'    => $validated['validation'] ?? null,
            'sort_order'    => $maxOrder + 1,
        ]);

        return response()->json([
            'message' => 'Champ ajouté.',
            'field'   => [
                'id'            => $field->id,
                'field_key'     => $field->field_key,
                'field_type'    => $field->field_type,
                'label'         => $field->label,
                'default_value' => $field->default_value,
                'options'       => $field->options,
                'validation'    => $field->validation,
                'sort_order'    => $field->sort_order,
            ]
        ], 201);
    }

    /**
     * Modifier un champ
     */
    public function update(Request $request, RuleField $ruleField)
    {
        $validated = $request->validate([
            'label'         => 'required|string|max:255',
            'options'       => 'nullable|array',
            'options.*.value' => 'required_with:options|string',
            'options.*.label' => 'required_with:options|string',
            'validation'    => 'nullable|array',
            'validation.min'  => 'nullable|numeric',
            'validation.max'  => 'nullable|numeric',
            'validation.required' => 'nullable|boolean',
            'validation.step' => 'nullable|numeric',
        ]);

        $ruleField->update([
            'label'         => $validated['label'],
            'default_value' => $this->parseDefaultValue(
                $request->input('default_value'),
                $ruleField->field_type
            ),
            'options'       => $validated['options'] ?? null,
            'validation'    => $validated['validation'] ?? null,
        ]);

        return response()->json([
            'message' => 'Champ mis à jour.',
            'field'   => [
                'id'            => $ruleField->id,
                'field_key'     => $ruleField->field_key,
                'field_type'    => $ruleField->field_type,
                'label'         => $ruleField->label,
                'default_value' => $ruleField->default_value,
                'options'       => $ruleField->options,
                'validation'    => $ruleField->validation,
                'sort_order'    => $ruleField->sort_order,
            ]
        ]);
    }

    /**
     * Supprimer un champ
     */
    public function destroy(RuleField $ruleField)
    {
        $ruleField->delete();
        return response()->json(['message' => 'Champ supprimé.']);
    }

    /**
     * Réordonner les champs
     */
    public function reorder(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer|exists:rule_fields,id',
            'orders.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            RuleField::where('id', $item['id'])
                ->where('leave_type_id', $leaveType->id)
                ->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Ordre mis à jour.']);
    }

    /**
     * Dupliquer les règles par défaut (15 champs standards)
     */
    public function seedDefaults(LeaveType $leaveType)
    {
        if ($leaveType->ruleFields()->exists()) {
            return response()->json(['message' => 'Ce type a déjà des champs.'], 409);
        }

        $defaults = [
            ['field_key' => 'min_notice_days', 'field_type' => 'number', 'label' => 'Préavis minimum (jours)', 'default_value' => '15', 'validation' => ['min' => 0, 'max' => 365, 'required' => true]],
            ['field_key' => 'max_per_year', 'field_type' => 'number', 'label' => 'Maximum par an', 'default_value' => '25', 'validation' => ['min' => 0, 'max' => 365, 'required' => true]],
            ['field_key' => 'max_consecutive_days', 'field_type' => 'number', 'label' => 'Max consécutifs (jours)', 'default_value' => '24', 'validation' => ['min' => 1, 'max' => 365]],
            ['field_key' => 'max_carryover_days', 'field_type' => 'number', 'label' => 'Report max (jours)', 'default_value' => '0', 'validation' => ['min' => 0, 'max' => 365]],
            ['field_key' => 'min_duration_days', 'field_type' => 'number', 'label' => 'Durée minimale (jours)', 'default_value' => '0.5', 'validation' => ['min' => 0.5, 'max' => 365, 'step' => 0.5]],
            ['field_key' => 'requires_approval_from', 'field_type' => 'select', 'label' => 'Approbation par', 'default_value' => 'manager', 'options' => [['value' => 'manager', 'label' => 'Manager'], ['value' => 'rh', 'label' => 'RH'], ['value' => 'direction', 'label' => 'Direction'], ['value' => 'manager_then_rh', 'label' => 'Manager puis RH']]],
            ['field_key' => 'allow_half_day', 'field_type' => 'boolean', 'label' => 'Demi-journée autorisée', 'default_value' => '1'],
            ['field_key' => 'exclude_weekends', 'field_type' => 'boolean', 'label' => 'Exclure week-ends', 'default_value' => '1'],
            ['field_key' => 'exclude_holidays', 'field_type' => 'boolean', 'label' => 'Exclure jours fériés', 'default_value' => '1'],
            ['field_key' => 'deducts_balance', 'field_type' => 'boolean', 'label' => 'Décompte solde', 'default_value' => '1'],
            ['field_key' => 'approval_required', 'field_type' => 'boolean', 'label' => 'Approbation requise', 'default_value' => '1'],
            ['field_key' => 'requires_attachment', 'field_type' => 'select', 'label' => 'Pièce justificative', 'default_value' => 'never', 'options' => [['value' => 'never', 'label' => 'Jamais'], ['value' => 'always', 'label' => 'Toujours'], ['value' => 'from_duration', 'label' => 'À partir d\'une durée']]],
            ['field_key' => 'attachment_threshold', 'field_type' => 'number', 'label' => 'Seuil pièce (jours)', 'default_value' => '0', 'validation' => ['min' => 0, 'max' => 365, 'step' => 0.5]],
            ['field_key' => 'allow_negative_balance', 'field_type' => 'boolean', 'label' => 'Solde négatif autorisé', 'default_value' => '0'],
            ['field_key' => 'negative_limit', 'field_type' => 'number', 'label' => 'Limite négative', 'default_value' => '0', 'validation' => ['min' => 0, 'max' => 365]],
        ];

        foreach ($defaults as $index => $field) {
            $field['sort_order'] = $index + 1;
            $leaveType->ruleFields()->create($field);
        }

        return response()->json([
            'message' => '15 champs par défaut créés.',
            'count'   => 15
        ]);
    }

    /**
     * Parse la valeur par défaut selon le type de champ.
     */
    private function parseDefaultValue($value, string $fieldType)
    {
        if (is_null($value) || $value === '' || $value === 'null') {
            return null;
        }

        // Checkbox : attend un tableau JSON ["value1", "value2"]
        if ($fieldType === 'checkbox') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : null;
        }

        // Les autres types : string simple
        return (string) $value;
    }
}