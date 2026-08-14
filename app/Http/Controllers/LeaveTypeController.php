<?php
// app/Http/Controllers/LeaveTypeController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepriseSiege;
use App\Models\LeaveType;
use App\Models\SiteLeaveTypeSetting;
use App\Services\LeaveTypeResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveTypeController extends Controller
{
    private function isSuperAdmin(): bool
    {
        $user = Auth::user();
        return $user && $user->IsSuperAdmin == 1;
    }

    private function getUserSiteId(): ?int
    {
        $user = Auth::user();
        return $user ? $user->SiegeID : null;
    }

    public function index()
    {
        $query = LeaveType::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('name');

        $leaveTypes = $query->get();

        // Admin site : on résout les overrides pour afficher les valeurs locales
        if (! $this->isSuperAdmin()) {
            $resolver = new LeaveTypeResolver();
            $leaveTypes = $resolver->resolveCollection($leaveTypes, $this->getUserSiteId());
        }

        return view('conges.leave_types.index', compact('leaveTypes'));
    }

    public function create()
    {
        $this->authorize('create', LeaveType::class);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        return view('conges.leave_types.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveType::class);

        $isAdmin = $this->isSuperAdmin();
        $validated = $request->validate($this->rules($isAdmin));

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        // Doublon code + site_id
        $exists = LeaveType::where('code', $validated['code'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                ->withInput();
        }

        $validated['deducts_balance']        = $request->boolean('deducts_balance');
        $validated['allow_negative_balance'] = $request->boolean('allow_negative_balance');
        $validated['is_active']              = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        if (($validated['requires_attachment'] ?? null) !== 'after_duration') {
            $validated['requires_attachment_after'] = null;
        }

        LeaveType::create($validated);

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Type de congé créé avec succès.');
    }

    public function show(LeaveType $leaveType)
    {
        $this->authorize('view', $leaveType);
        return view('conges.leave_types.show', compact('leaveType'));
    }

    public function edit(LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        // Charge l'override existant si on est en mode "édition locale d'un global"
        $override = null;
        if (! $this->isSuperAdmin() && $leaveType->isGlobal() && $leaveType->is_customizable) {
            $override = SiteLeaveTypeSetting::where('site_id', $this->getUserSiteId())
                ->where('leave_type_id', $leaveType->id)
                ->first();
        }

        return view('conges.leave_types.edit', compact('leaveType', 'sites', 'override'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $isAdmin = $this->isSuperAdmin();

        // CAS SPÉCIAL : Admin site + global customizable → on écrit dans l'override
        if (! $isAdmin && $leaveType->isGlobal() && $leaveType->is_customizable) {
            return $this->updateOverride($request, $leaveType);
        }

        // CAS STANDARD : Super Admin ou type local
        $validated = $request->validate($this->rules($isAdmin, $leaveType));

        if (! $isAdmin) {
            unset($validated['site_id']);
            $newSiteId = $leaveType->site_id;
        } else {
            $newSiteId = $validated['site_id'] ?? null;
        }

        if ($validated['code'] !== $leaveType->code || $newSiteId != $leaveType->site_id) {
            $exists = LeaveType::where('code', $validated['code'])
                ->where('site_id', $newSiteId)
                ->where('id', '!=', $leaveType->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                    ->withInput();
            }
        }

        $validated['deducts_balance']        = $request->boolean('deducts_balance');
        $validated['allow_negative_balance'] = $request->boolean('allow_negative_balance');
        $validated['is_active']              = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        if (($validated['requires_attachment'] ?? null) !== 'after_duration') {
            $validated['requires_attachment_after'] = null;
        }

        $leaveType->update($validated);

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Type de congé mis à jour avec succès.');
    }

    public function destroy(LeaveType $leaveType)
    {
        $this->authorize('delete', $leaveType);

        $leaveType->delete();

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Type de congé supprimé.');
    }

    /**
     * Écriture dans la table d'override locale.
     */
    private function updateOverride(Request $request, LeaveType $leaveType)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'name'                      => 'nullable|string|max:100',
            'color'                     => 'nullable|string|max:7',
            'requires_attachment'       => 'nullable|in:never,always,after_duration',
            'requires_attachment_after' => 'nullable|integer|min:1',
            'allow_negative_balance'    => 'nullable|boolean',
            'max_negative_limit'        => 'nullable|integer',
            'deducts_balance'           => 'nullable|boolean',
            'is_active'                 => 'nullable|boolean',
        ]);

        if (($validated['requires_attachment'] ?? null) !== 'after_duration') {
            $validated['requires_attachment_after'] = null;
        }

        $overrideData = [
            'site_id'       => $siteId,
            'leave_type_id' => $leaveType->id,
        ];

        // On ne stocke que les champs explicitement envoyés (null = héritage du global)
        if ($request->has('name')) {
            $overrideData['local_name'] = $validated['name'] ?: null;
        }
        if ($request->has('color')) {
            $overrideData['local_color'] = $validated['color'] ?: null;
        }
        if ($request->has('requires_attachment')) {
            $overrideData['local_requires_attachment'] = $validated['requires_attachment'] ?: null;
        }
        if ($request->has('requires_attachment_after')) {
            $overrideData['local_requires_attachment_after'] = $validated['requires_attachment_after'];
        }
        if ($request->has('allow_negative_balance')) {
            $overrideData['local_allow_negative_balance'] = $request->boolean('allow_negative_balance');
        }
        if ($request->has('max_negative_limit')) {
            $overrideData['local_max_negative_limit'] = $validated['max_negative_limit'];
        }
        if ($request->has('deducts_balance')) {
            $overrideData['local_deducts_balance'] = $request->boolean('deducts_balance');
        }
        if ($request->has('is_active')) {
            $overrideData['is_enabled'] = $request->boolean('is_active');
        }

        SiteLeaveTypeSetting::updateOrCreate(
            [
                'site_id'       => $siteId,
                'leave_type_id' => $leaveType->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Configuration locale mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?LeaveType $ignore = null): array
    {
        $rules = [
            'name'                      => 'required|string|max:100',
            'code'                      => [
                'required',
                'string',
                'max:20',
                Rule::unique('leave_types')->ignore($ignore?->id),
            ],
            'unit'                      => 'required|in:days,half_days,hours',
            'requires_attachment'       => 'required|in:never,always,after_duration',
            'requires_attachment_after' => 'nullable|integer|min:1',
            'max_negative_limit'        => 'nullable|integer',
            'color'                     => 'required|string|max:7',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}