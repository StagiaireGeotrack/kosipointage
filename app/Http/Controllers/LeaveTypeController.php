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

    public function index(Request $request)
    {
        $this->authorize('viewAny', LeaveType::class);

        $query = LeaveType::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $leaveTypes = $query->paginate(15);

        // Admin site : on résout les overrides
        if (! $this->isSuperAdmin()) {
            $resolver = new LeaveTypeResolver();
            $resolvedTypes = $resolver->resolveCollection($leaveTypes->getCollection(), $this->getUserSiteId());
            $leaveTypes->setCollection($resolvedTypes);
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

        // Vérification des doublons (code + site_id unique)
        $exists = LeaveType::where('code', $validated['code'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                ->withInput();
        }

        $validated['deducts_balance'] = $request->boolean('deducts_balance');
        $validated['allow_negative_balance'] = $request->boolean('allow_negative_balance');
        $validated['is_active'] = $request->boolean('is_active', true);

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

        $resolved = $leaveType;
        if (! $this->isSuperAdmin() && $leaveType->isGlobal()) {
            $resolver = new LeaveTypeResolver();
            $resolved = $resolver->resolve($leaveType, $this->getUserSiteId());
        }

        return view('conges.leave_types.show', compact('leaveType', 'resolved'));
    }

    public function edit(LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

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

        // Vérification des doublons
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

        $validated['deducts_balance'] = $request->boolean('deducts_balance');
        $validated['allow_negative_balance'] = $request->boolean('allow_negative_balance');
        $validated['is_active'] = $request->boolean('is_active', true);

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

    public function restore($id)
    {
        $type = LeaveType::withTrashed()->findOrFail($id);
        $this->authorize('update', $type);

        $type->restore();

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Type de congé restauré avec succès.');
    }

    private function updateOverride(Request $request, LeaveType $leaveType)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'local_name' => 'nullable|string|max:100',
            'local_color' => 'nullable|string|max:7',
            'local_requires_attachment' => 'nullable|in:never,always,after_duration',
            'local_requires_attachment_after' => 'nullable|integer|min:1',
            'local_allow_negative_balance' => 'nullable|boolean',
            'local_max_negative_limit' => 'nullable|integer',
            'local_deducts_balance' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if (($validated['local_requires_attachment'] ?? null) !== 'after_duration') {
            $validated['local_requires_attachment_after'] = null;
        }

        $overrideData = [
            'site_id' => $siteId,
            'leave_type_id' => $leaveType->id,
        ];

        if ($request->has('local_name')) {
            $overrideData['local_name'] = $validated['local_name'] ?: null;
        }
        if ($request->has('local_color')) {
            $overrideData['local_color'] = $validated['local_color'] ?: null;
        }
        if ($request->has('local_requires_attachment')) {
            $overrideData['local_requires_attachment'] = $validated['local_requires_attachment'] ?: null;
        }
        if ($request->has('local_requires_attachment_after')) {
            $overrideData['local_requires_attachment_after'] = $validated['local_requires_attachment_after'];
        }
        if ($request->has('local_allow_negative_balance')) {
            $overrideData['local_allow_negative_balance'] = $request->boolean('local_allow_negative_balance');
        }
        if ($request->has('local_max_negative_limit')) {
            $overrideData['local_max_negative_limit'] = $validated['local_max_negative_limit'];
        }
        if ($request->has('local_deducts_balance')) {
            $overrideData['local_deducts_balance'] = $request->boolean('local_deducts_balance');
        }
        if ($request->has('is_active')) {
            $overrideData['is_enabled'] = $request->boolean('is_active');
        }

        SiteLeaveTypeSetting::updateOrCreate(
            [
                'site_id' => $siteId,
                'leave_type_id' => $leaveType->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.leave-types.index')
            ->with('success', 'Configuration locale du type de congé mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?LeaveType $ignore = null): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('leave_types')->ignore($ignore?->id),
            ],
            'unit' => 'required|in:days,half_days,hours',
            'requires_attachment' => 'required|in:never,always,after_duration',
            'requires_attachment_after' => 'nullable|integer|min:1',
            'max_negative_limit' => 'nullable|integer',
            'color' => 'required|string|max:7',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}