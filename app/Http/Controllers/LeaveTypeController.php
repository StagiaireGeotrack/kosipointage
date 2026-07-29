<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    /**
     * Récupère le company_id (SiegeID) de l'admin connecté.
     * Adapte selon ton auth. Si ça ne marche pas tout de suite,
     * remplace par `return 1;` pour tester.
     */
 private function companyId(): int
{
    $user = auth()->user();

    // SUPER ADMIN : lit la session d'abord (c'est là que le sélecteur écrit)
    if ($user && $user->isTrueSuperAdmin()) {
        return (int) session('admin_selected_siege_id', $user->SiegeID ?? 1);
    }

    // SIMPLE ADMIN : son SiegeID en base
    if ($user && isset($user->SiegeID)) {
        return (int) $user->SiegeID;
    }

    return 1;
}

    // Affiche la page admin
  public function page()
{
    $sieges = null;
    $selectedSiegeId = session('admin_selected_siege_id', auth()->user()->SiegeID ?? 1);
    $selectedSiegeName = null;

    // Si Super Admin, on récupère tous les sièges pour le sélecteur
    if (auth()->user() && auth()->user()->isTrueSuperAdmin()) {
        $sieges = \App\Models\EntrepriseSiege::orderBy('Nom')->get();
        $selectedSiegeName = $sieges->firstWhere('ID', $selectedSiegeId)?->Nom ?? 'Siège ' . $selectedSiegeId;
    } else {
        // Simple Admin : on récupère juste le nom de son siège
        $siege = \App\Models\EntrepriseSiege::find($selectedSiegeId);
        $selectedSiegeName = $siege?->Nom ?? 'Siège ' . $selectedSiegeId;
    }

    return view('conges.leave-types', compact('sieges', 'selectedSiegeId', 'selectedSiegeName'));
}
    // LISTE (JSON)
    public function index()
    {
        $types = LeaveType::forCompany($this->companyId())
            ->orderBy('name')
            ->get();

        return response()->json($types);
    }

    // DÉTAIL (JSON)
    public function show($id)
    {
        $type = LeaveType::forCompany($this->companyId())->findOrFail($id);
        return response()->json($type);
    }

    // CRÉER
    public function store(Request $request)
    {
        $companyId = $this->companyId();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => ['required', 'string', 'max:20', Rule::unique('leave_types')->where(fn ($q) => $q->where('company_id', $companyId))],
            'color' => 'required|string|max:7',
            'unit' => 'required|in:days,half_days,hours',
            'deducts_balance' => 'boolean',
            'requires_attachment' => 'required|in:never,always,from_duration',
            'attachment_threshold' => 'integer|min:0',
            'approval_required' => 'boolean',
            'allow_negative_balance' => 'boolean',
            'negative_limit' => 'numeric|min:0',
            'visibility_level' => 'required|in:all,manager,rh,admin',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['company_id'] = $companyId;

        $type = LeaveType::create($data);
        return response()->json($type, 201);
    }

    // MODIFIER
    public function update(Request $request, $id)
    {
        $companyId = $this->companyId();

        $type = LeaveType::forCompany($companyId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'code' => ['required', 'string', 'max:20', Rule::unique('leave_types')->where(fn ($q) => $q->where('company_id', $companyId))->ignore($type->id)],
            'color' => 'required|string|max:7',
            'unit' => 'required|in:days,half_days,hours',
            'deducts_balance' => 'boolean',
            'requires_attachment' => 'required|in:never,always,from_duration',
            'attachment_threshold' => 'integer|min:0',
            'approval_required' => 'boolean',
            'allow_negative_balance' => 'boolean',
            'negative_limit' => 'numeric|min:0',
            'visibility_level' => 'required|in:all,manager,rh,admin',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type->update($validator->validated());
        return response()->json($type);
    }

    // SUPPRIMER
    public function destroy($id)
    {
        $companyId = $this->companyId();
        $type = LeaveType::forCompany($companyId)->findOrFail($id);

        // Vérification : est-ce que ce type est utilisé par une politique ?
        $hasPolicies = \DB::table('leave_policies')
            ->where('leave_type_id', $type->id)
            ->exists();

        if ($hasPolicies) {
            return response()->json([
                'error' => 'Ce type est utilisé par une politique. Supprimez d\'abord la politique associée.'
            ], 409);
        }

        $type->delete();
        return response()->json(['message' => 'Type supprimé.']);
    }
}