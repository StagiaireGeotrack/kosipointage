<?php

namespace App\Http\Controllers;

use App\Models\LeavePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeavePolicyController extends Controller
{
    /**
     * Récupère le SiegeID de l'admin connecté.
     */
    private function companyId(): int
    {
        $user = auth()->user();

        if ($user && $user->isTrueSuperAdmin()) {
            return (int) session('admin_selected_siege_id', $user->SiegeID ?? 1);
        }

        if ($user && isset($user->SiegeID)) {
            return (int) $user->SiegeID;
        }

        return 1;
    }

    /**
     * Page Blade
     */
    public function page()
    {
        $sieges = null;
        $selectedSiegeId = session('admin_selected_siege_id', auth()->user()->SiegeID ?? 1);
        $selectedSiegeName = null;

        if (auth()->user() && auth()->user()->isTrueSuperAdmin()) {
            $sieges = \App\Models\EntrepriseSiege::orderBy('Nom')->get();
            $selectedSiegeName = $sieges->firstWhere('ID', $selectedSiegeId)?->Nom ?? 'Siège ' . $selectedSiegeId;
        } else {
            $siege = \App\Models\EntrepriseSiege::find($selectedSiegeId);
            $selectedSiegeName = $siege?->Nom ?? 'Siège ' . $selectedSiegeId;
        }

        return view('conges.leave-policies', compact('sieges', 'selectedSiegeId', 'selectedSiegeName'));
    }

    /**
     * LISTE : toutes les policies du siège connecté, avec le type global
     */
    public function index()
    {
        $policies = LeavePolicy::with('leaveType')
            ->where('company_id', $this->companyId())
            ->orderBy('id')
            ->get();

        return response()->json($policies);
    }

    /**
     * MODIFIER les règles d'une policy
     */
   public function update(Request $request, $id)
{
    $companyId = $this->companyId();

    $policy = LeavePolicy::where('company_id', $companyId)->findOrFail($id);

    $validated = $request->validate([
        'rules' => 'sometimes|array',
        'rules.min_notice_days' => 'nullable|integer|min:0',
        'rules.max_per_year' => 'nullable|integer|min:1',
        'rules.max_consecutive_days' => 'nullable|integer|min:1',
        'rules.max_carryover_days' => 'nullable|integer|min:0',
        'rules.min_duration_days' => 'nullable|numeric|min:0.5',
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

    // Récupère les règles actuelles (déjà un tableau grâce au cast)
    $currentRules = $policy->rules ?? [];

    // Si rules est envoyé, on fusionne (les nouvelles valeurs écrasent les anciennes)
    if (isset($validated['rules'])) {
        $validated['rules'] = array_merge($currentRules, $validated['rules']);
    }

    $policy->update($validated);

    return response()->json([
        'message' => 'Règles mises à jour.',
        'policy' => $policy->fresh()->load('leaveType')
    ]);
}
    /**
     * Activer / Désactiver rapidement une policy
     */
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