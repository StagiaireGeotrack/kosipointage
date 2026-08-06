<?php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use App\Models\LeaveType;
use App\Models\PolicyValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeavePolicyController extends Controller
{
    private function companyId(): int
    {
        $user = auth()->user();

        if ($user && $user->isTrueSuperAdmin()) {
            return (int) session('admin_selected_siege_id', $user->SiegeID ?? 1);
        }

        return (int) ($user->SiegeID ?? 1);
    }

    public function page()
    {
        $sieges = null;
        $selectedSiegeId = session('admin_selected_siege_id', auth()->user()->SiegeID ?? 1);
        $selectedSiegeName = null;

        if (auth()->user()?->isTrueSuperAdmin()) {
            $sieges = \App\Models\EntrepriseSiege::orderBy('Nom')->get();
            $selectedSiegeName = $sieges->firstWhere('ID', $selectedSiegeId)?->Nom ?? 'Siège ' . $selectedSiegeId;
        } else {
            $siege = \App\Models\EntrepriseSiege::find($selectedSiegeId);
            $selectedSiegeName = $siege?->Nom ?? 'Siège ' . $selectedSiegeId;
        }

        return view('conges.leave-policies', compact('sieges', 'selectedSiegeId', 'selectedSiegeName'));
    }

    /**
     * LISTE : types globaux + rule_fields + policies du siège avec leurs values
     */
    public function index()
    {
        $companyId = $this->companyId();

        $types = LeaveType::where('is_active', true)
            ->with('ruleFields')
            ->orderBy('name')
            ->get();

        $policies = LeavePolicy::where('company_id', $companyId)
            ->with(['values' => fn($q) => $q->with('ruleField')])
            ->get()
            ->keyBy('leave_type_id');

        $items = $types->map(function ($type) use ($policies) {
            $policy = $policies->get($type->id);

            // Valeurs par défaut depuis les rule_fields
            $defaultValues = [];
            foreach ($type->ruleFields as $rf) {
                $defaultValues[$rf->field_key] = $rf->default_value ?? '';
            }

            // Valeurs actuellement enregistrées dans la policy
            $policyValues = [];
            if ($policy) {
                foreach ($policy->values as $pv) {
                    $val = $pv->value;
                    // Décoder JSON pour checkbox
                    if ($pv->ruleField->field_type === 'checkbox') {
                        $decoded = json_decode($val, true);
                        $val = is_array($decoded) ? $decoded : [];
                    }
                    $policyValues[$pv->ruleField->field_key] = $val;
                }
            }

            return [
                'leave_type' => [
                    'id'          => $type->id,
                    'code'        => $type->code,
                    'name'        => $type->name,
                    'color'       => $type->color,
                    'description' => $type->description,
                ],
                'rule_fields'    => $type->ruleFields->map(fn($rf) => [
                    'id'            => $rf->id,
                    'field_key'     => $rf->field_key,
                    'field_type'    => $rf->field_type,
                    'label'         => $rf->label,
                    'default_value' => $rf->default_value,
                    'options'       => $rf->options,
                    'validation'    => $rf->validation,
                    'sort_order'    => $rf->sort_order,
                ]),
                'policy'         => $policy ? [
                    'id'        => $policy->id,
                    'is_active' => $policy->is_active,
                ] : null,
                'policy_values'  => $policyValues,
                'default_values' => $defaultValues,
                'configured'     => !is_null($policy),
            ];
        });

        return response()->json($items);
    }

    /**
     * CRÉER une policy + pré-remplir avec les default_value des rule_fields
     */
    public function store(Request $request)
    {
        $companyId = $this->companyId();

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'is_active'     => 'boolean',
        ]);

        $exists = LeavePolicy::where('company_id', $companyId)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Cette règle existe déjà.'], 409);
        }

        $type = LeaveType::with('ruleFields')->findOrFail($validated['leave_type_id']);

        $policy = DB::transaction(function () use ($companyId, $type, $validated) {
            $policy = LeavePolicy::create([
                'company_id'    => $companyId,
                'leave_type_id' => $type->id,
                'rules'         => null,
                'is_active'     => $validated['is_active'] ?? true,
            ]);

            foreach ($type->ruleFields as $rf) {
                $value = $rf->default_value ?? '';
                // Encoder en JSON si checkbox (array)
                if ($rf->field_type === 'checkbox' && is_array($value)) {
                    $value = json_encode($value);
                }

                PolicyValue::create([
                    'leave_policy_id' => $policy->id,
                    'rule_field_id'   => $rf->id,
                    'value'           => (string) $value,
                ]);
            }

            return $policy;
        });

        return response()->json([
            'message' => 'Règle créée avec succès.',
            'policy'  => $policy->load('leaveType')
        ], 201);
    }

    /**
     * METTRE À JOUR les valeurs d'une policy (formulaire dynamique)
     */
    public function update(Request $request, $id)
    {
        $companyId = $this->companyId();

        $policy = LeavePolicy::where('company_id', $companyId)
            ->with('leaveType.ruleFields')
            ->findOrFail($id);

        $ruleFields = $policy->leaveType->ruleFields->keyBy('field_key');

        // Construction dynamique des règles de validation
        $rules = ['is_active' => 'boolean'];
        foreach ($ruleFields as $key => $rf) {
            $fieldRules = [];
            $val = $rf->validation ?? [];

            if ($rf->field_type === 'number') {
                $fieldRules[] = 'numeric';
                if (isset($val['min'])) $fieldRules[] = 'min:' . $val['min'];
                if (isset($val['max'])) $fieldRules[] = 'max:' . $val['max'];
            } elseif ($rf->field_type === 'boolean') {
                $fieldRules[] = 'boolean';
            } elseif ($rf->field_type === 'select') {
                $allowed = collect($rf->options ?? [])->pluck('value')->implode(',');
                $fieldRules[] = 'in:' . $allowed;
            } elseif ($rf->field_type === 'checkbox') {
                $fieldRules[] = 'array';
                $fieldRules[] = 'nullable';
                $rules["values.$key.*"] = 'in:' . collect($rf->options ?? [])->pluck('value')->implode(',');
            }

            if ($val['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            $rules["values.$key"] = $fieldRules;
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($policy, $ruleFields, $validated, $request) {
            if (isset($validated['is_active'])) {
                $policy->update(['is_active' => $validated['is_active']]);
            }

            $incoming = $request->input('values', []);
            foreach ($ruleFields as $key => $rf) {
                // Gestion spécifique checkbox (tableau → JSON)
                if ($rf->field_type === 'checkbox') {
                    $raw = $incoming[$key] ?? [];
                    $value = json_encode(array_values($raw));
                } else {
                    $raw = $incoming[$key] ?? ($rf->field_type === 'boolean' ? '0' : '');
                    $value = (string) $raw;
                }

                PolicyValue::updateOrCreate(
                    ['leave_policy_id' => $policy->id, 'rule_field_id' => $rf->id],
                    ['value' => $value]
                );
            }
        });

        return response()->json([
            'message' => 'Règles mises à jour.',
            'policy'  => $policy->fresh()->load('values.ruleField')
        ]);
    }

    public function toggleActive($id)
    {
        $companyId = $this->companyId();
        $policy = LeavePolicy::where('company_id', $companyId)->findOrFail($id);
        $policy->update(['is_active' => !$policy->is_active]);

        return response()->json([
            'message' => $policy->is_active ? 'Type activé.' : 'Type désactivé.',
            'policy'  => $policy->load('leaveType')
        ]);
    }
}