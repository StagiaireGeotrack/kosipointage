<?php
// app/Http/Controllers/LeaveWorkflowController.php

namespace App\Http\Controllers;

use App\Models\EntrepriseSiege;
use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;
use App\Models\LeaveType;
use App\Services\LeaveWorkflowResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveWorkflowController extends Controller
{
    private function isSuperAdmin(): bool
    {
        return Auth::user() && Auth::user()->IsSuperAdmin == 1;
    }

    private function getUserSiteId(): ?int
    {
        return Auth::user() ? Auth::user()->SiegeID : null;
    }

    private function getDefaultSteps(): array
    {
        return [
            ['order' => 1, 'role' => 'manager', 'label' => 'Validation Manager', 'description' => 'Le manager valide la demande'],
            ['order' => 2, 'role' => 'hr', 'label' => 'Validation RH', 'description' => 'Le service RH approuve la demande']
        ];
    }

    private function normalizeSteps($steps): array
    {
        if (is_string($steps)) {
            $steps = json_decode($steps, true);
        }
        if (!is_array($steps) || empty($steps)) {
            return $this->getDefaultSteps();
        }
        $normalized = [];
        foreach ($steps as $step) {
            if (is_array($step) && isset($step['role'])) {
                $normalized[] = [
                    'order' => $step['order'] ?? count($normalized) + 1,
                    'role' => $step['role'],
                    'label' => $step['label'] ?? $step['role'],
                    'description' => $step['description'] ?? '',
                ];
            }
        }
        if (empty($normalized)) {
            return $this->getDefaultSteps();
        }
        usort($normalized, fn($a, $b) => $a['order'] - $b['order']);
        return $normalized;
    }

    public function index(Request $request)
{
    $this->authorize('viewAny', LeaveWorkflow::class);

    $user = Auth::user();
    $isSuperAdmin = $user && $user->IsSuperAdmin == 1;
    $userSiteId = $user->SiegeID ?? null;

    // Requête de base
    $query = LeaveWorkflow::with(['site', 'leaveType']);

    // 🔥 Filtrage selon les droits
    if (!$isSuperAdmin) {
        // L'admin simple ne voit que :
        // - les workflows globaux (site_id null)
        // - les workflows de son siège (site_id = $userSiteId)
        $query->where(function ($q) use ($userSiteId) {
            $q->whereNull('site_id')
              ->orWhere('site_id', $userSiteId);
        });
    }

    // Filtre recherche
    if ($request->filled('search')) {
        $query->where('name', 'LIKE', "%{$request->search}%");
    }

    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active);
    }

    // Pagination
    $workflows = $query->orderBy('name')->paginate(15);

    // 🔥 Résolution des workflows (application des overrides)
    if (!$isSuperAdmin && $userSiteId) {
        $resolver = new LeaveWorkflowResolver();
        $resolvedCollection = $resolver->resolveCollection($workflows->getCollection(), $userSiteId);
        $workflows->setCollection($resolvedCollection);
    } else {
        // Pour superadmin, on transforme en objet stdClass avec les bonnes propriétés
        $resolvedCollection = $workflows->getCollection()->map(function ($workflow) {
            $obj = new \stdClass();
            foreach ($workflow->getAttributes() as $k => $v) {
                $obj->{$k} = $v;
            }
            $obj->is_global = is_null($workflow->site_id);
            $obj->site_name = $workflow->site->Nom ?? '—';
            $obj->is_customizable = $workflow->is_customizable ?? false;
            $obj->is_overridden = false;
            $obj->override_id = null;
            $obj->steps = is_string($workflow->steps) ? json_decode($workflow->steps, true) : ($workflow->steps ?? []);
            $obj->leave_type_name = $workflow->leaveType->name ?? null;
            return $obj;
        });
        $workflows->setCollection($resolvedCollection);
    }

    return view('conges.leave_workflows.index', compact('workflows'));
}

    public function create()
{
    $this->authorize('create', LeaveWorkflow::class);
    $sites = $this->isSuperAdmin() ? EntrepriseSiege::orderBy('nom')->get() : collect();
    $leaveTypes = LeaveType::where('is_active', 1)->orderBy('name')->get();
    return view('conges.leave_workflows.create', compact('sites', 'leaveTypes'));
}

    public function store(Request $request)
    {
        $this->authorize('create', LeaveWorkflow::class);
        $isAdmin = $this->isSuperAdmin();

        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'leave_type_id' => 'nullable|exists:leave_types,id',
        ];
        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }
        $validated = $request->validate($rules);

        if (!$isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        $exists = LeaveWorkflow::where('name', $validated['name'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();
        if ($exists) {
            return back()->withErrors(['name' => 'Un workflow avec ce nom existe déjà pour ce siège.'])->withInput();
        }

        $steps = $request->input('steps');
        $validated['steps'] = $this->normalizeSteps($steps);
        if (empty($validated['steps'])) {
            return back()->withErrors(['steps' => 'Veuillez définir au moins une étape de validation.'])->withInput();
        }

        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        LeaveWorkflow::create($validated);

        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Workflow de congé créé avec succès.');
    }

    public function show(LeaveWorkflow $leaveWorkflow)
    {
        $this->authorize('view', $leaveWorkflow);
        $resolved = $leaveWorkflow;
        if (!$this->isSuperAdmin() && $leaveWorkflow->isGlobal()) {
            $resolver = new LeaveWorkflowResolver();
            $resolved = $resolver->resolve($leaveWorkflow, $this->getUserSiteId());
        }
        return view('conges.leave_workflows.show', compact('leaveWorkflow', 'resolved'));
    }

   public function edit(LeaveWorkflow $leaveWorkflow)
{
    $this->authorize('update', $leaveWorkflow);
    $sites = $this->isSuperAdmin() ? EntrepriseSiege::orderBy('nom')->get() : collect();
    $leaveTypes = LeaveType::where('is_active', 1)->orderBy('name')->get();
    $override = null;
    if (!$this->isSuperAdmin() && $leaveWorkflow->isGlobal() && $leaveWorkflow->is_customizable) {
        $override = SiteLeaveWorkflowSetting::where('site_id', $this->getUserSiteId())
            ->where('leave_workflow_id', $leaveWorkflow->id)
            ->first();
    }
    return view('conges.leave_workflows.edit', compact('leaveWorkflow', 'sites', 'leaveTypes', 'override'));
}

    public function update(Request $request, LeaveWorkflow $leaveWorkflow)
    {
        $this->authorize('update', $leaveWorkflow);
        $isAdmin = $this->isSuperAdmin();

        if (!$isAdmin && $leaveWorkflow->isGlobal() && $leaveWorkflow->is_customizable) {
            return $this->updateOverride($request, $leaveWorkflow);
        }

        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'leave_type_id' => 'nullable|exists:leave_types,id',
        ];
        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }
        $validated = $request->validate($rules);

        if (!$isAdmin) {
            unset($validated['site_id']);
            $newSiteId = $leaveWorkflow->site_id;
        } else {
            $newSiteId = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        if ($validated['name'] !== $leaveWorkflow->name || $newSiteId != $leaveWorkflow->site_id) {
            $exists = LeaveWorkflow::where('name', $validated['name'])
                ->where('site_id', $newSiteId)
                ->where('id', '!=', $leaveWorkflow->id)
                ->exists();
            if ($exists) {
                return back()->withErrors(['name' => 'Un workflow avec ce nom existe déjà pour ce siège.'])->withInput();
            }
        }

        $steps = $request->input('steps');
        $validated['steps'] = $this->normalizeSteps($steps);
        if (empty($validated['steps'])) {
            return back()->withErrors(['steps' => 'Veuillez définir au moins une étape de validation.'])->withInput();
        }

        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $leaveWorkflow->update($validated);

        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Workflow de congé mis à jour.');
    }

    public function destroy(LeaveWorkflow $leaveWorkflow)
    {
        $this->authorize('delete', $leaveWorkflow);
        $leaveWorkflow->delete();
        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Workflow de congé supprimé.');
    }

    public function restore($id)
    {
        $workflow = LeaveWorkflow::withTrashed()->findOrFail($id);
        $this->authorize('update', $workflow);
        $workflow->restore();
        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Workflow de congé restauré.');
    }

    private function updateOverride(Request $request, LeaveWorkflow $leaveWorkflow)
    {
        $siteId = $this->getUserSiteId();
        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'leave_type_id' => 'nullable|exists:leave_types,id',
        ]);
        $steps = $request->input('steps');
        $normalizedSteps = $this->normalizeSteps($steps);

        $overrideData = [
            'site_id' => $siteId,
            'leave_workflow_id' => $leaveWorkflow->id,
        ];
        if ($request->has('name')) $overrideData['name'] = $validated['name'] ?: null;
        if ($request->has('description')) $overrideData['description'] = $validated['description'] ?: null;
        if ($request->has('steps') && !empty($normalizedSteps)) $overrideData['steps'] = $normalizedSteps;
        if ($request->has('is_default')) $overrideData['is_default'] = $request->boolean('is_default');
        if ($request->has('is_active')) $overrideData['is_active'] = $request->boolean('is_active');
        if ($request->has('leave_type_id')) $overrideData['leave_type_id'] = $validated['leave_type_id'] ?: null;

        SiteLeaveWorkflowSetting::updateOrCreate(
            ['site_id' => $siteId, 'leave_workflow_id' => $leaveWorkflow->id],
            $overrideData
        );

        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Configuration locale du workflow mise à jour.');
    }
}