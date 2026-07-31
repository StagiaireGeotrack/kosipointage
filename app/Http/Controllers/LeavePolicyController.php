<?php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use App\Models\LeaveType;
use Illuminate\Http\Request;

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
     * Retourne TOUS les types globaux actifs, fusionnés avec les policies du siège
     */
    public function index()
    {
        $companyId = $this->companyId();

        $types = LeaveType::where('is_active', true)->orderBy('name')->get();

        $existingPolicies = LeavePolicy::where('company_id', $companyId)
            ->get()
            ->keyBy('leave_type_id');

        $items = $types->map(function ($type) use ($existingPolicies) {
            $policy = $existingPolicies->get($type->id);
            return [
                'leave_type' => [
                    'id'          => $type->id,
                    'code'        => $type->code,
                    'name'        => $type->name,
                    'color'       => $type->color,
                    'description' => $type->description,
                ],
                'policy'     => $policy ? [
                    'id'            => $policy->id,
                    'company_id'    => $policy->company_id,
                    'leave_type_id' => $policy->leave_type_id,
                    'rules'         => $policy->rules,
                    'is_active'     => $policy->is_active,
                ] : null,
                'configured' => !is_null($policy),
            ];
        });

        return response()->json($items);
    }

    /**
     * CRÉER une policy par défaut pour ce siège
     */
    public function store(Request $request)
    {
        $companyId = $this->companyId();

        $validated = $request->validate([
            'leave_type_id'                => 'required|exists:leave_types,id',
            'rules'                        => 'nullable|array',
            'rules.min_notice_days'        => 'nullable|integer|min:0',
            'rules.max_per_year'           => 'nullable|integer|min:0',
            'rules.max_consecutive_days'   => 'nullable|integer|min:0',
            'rules.max_carryover_days'     => 'nullable|integer|min:0',
            'rules.min_duration_days'      => 'nullable|numeric|min:0',
            'rules.requires_approval_from' => 'nullable|in:manager,rh,direction,manager_then_rh',
            'rules.allow_half_day'         => 'nullable|boolean',
            'rules.exclude_weekends'       => 'nullable|boolean',
            'rules.exclude_holidays'       => 'nullable|boolean',
            'rules.deducts_balance'        => 'nullable|boolean',
            'rules.approval_required'      => 'nullable|boolean',
            'rules.requires_attachment'    => 'nullable|in:never,always,from_duration',
            'rules.attachment_threshold'   => 'nullable|numeric|min:0',
            'rules.allow_negative_balance' => 'nullable|boolean',
            'rules.negative_limit'         => 'nullable|numeric|min:0',
            'is_active'                    => 'boolean',
        ]);

        // Vérifie doublon
        $exists = LeavePolicy::where('company_id', $companyId)
            ->where('leave_type_id', $validated['leave_type_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Cette règle existe déjà.'], 409);
        }

        $policy = LeavePolicy::create([
            'company_id'    => $companyId,
            'leave_type_id' => $validated['leave_type_id'],
            'rules'         => array_merge([
                'min_notice_days'        => 15,
                'max_per_year'           => 25,
                'max_consecutive_days'   => 24,
                'max_carryover_days'     => 5,
                'min_duration_days'      => 0.5,
                'requires_approval_from' => 'manager_then_rh',
                'allow_half_day'         => true,
                'exclude_weekends'       => true,
                'exclude_holidays'       => true,
                'deducts_balance'        => true,
                'approval_required'      => true,
                'requires_attachment'    => 'never',
                'attachment_threshold'   => 0,
                'allow_negative_balance' => false,
                'negative_limit'         => 0,
            ], $validated['rules'] ?? []),
            'is_active'     => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Règle créée avec succès.',
            'policy'  => $policy->load('leaveType')
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $companyId = $this->companyId();
        $policy = LeavePolicy::where('company_id', $companyId)->findOrFail($id);

        $validated = $request->validate([
            'rules' => 'sometimes|array',
            'rules.min_notice_days' => 'nullable|integer|min:0',
            'rules.max_per_year' => 'nullable|integer|min:0',
            'rules.max_consecutive_days' => 'nullable|integer|min:0',
            'rules.max_carryover_days' => 'nullable|integer|min:0',
            'rules.min_duration_days' => 'nullable|numeric|min:0',
            'rules.requires_approval_from' => 'nullable|in:manager,rh,direction,manager_then_rh',
            'rules.allow_half_day' => 'nullable|boolean',
            'rules.exclude_weekends' => 'nullable|boolean',
            'rules.exclude_holidays' => 'nullable|boolean',
            'rules.deducts_balance' => 'nullable|boolean',
            'rules.approval_required' => 'nullable|boolean',
            'rules.requires_attachment' => 'nullable|in:never,always,from_duration',
            'rules.attachment_threshold' => 'nullable|numeric|min:0',
            'rules.allow_negative_balance' => 'nullable|boolean',
            'rules.negative_limit' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $currentRules = $policy->rules ?? [];
        if (isset($validated['rules'])) {
            $validated['rules'] = array_merge($currentRules, $validated['rules']);
        }

        $policy->update($validated);

        return response()->json([
            'message' => 'Règles mises à jour.',
            'policy' => $policy->fresh()->load('leaveType')
        ]);
    }

    public function toggleActive($id)
    {
        $companyId = $this->companyId();
        $policy = LeavePolicy::where('company_id', $companyId)->findOrFail($id);
        $policy->update(['is_active' => !$policy->is_active]);

        return response()->json([
            'message' => $policy->is_active ? 'Type activé.' : 'Type désactivé.',
            'policy' => $policy->load('leaveType')
        ]);
    }
}