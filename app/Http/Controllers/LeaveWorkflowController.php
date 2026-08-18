<?php
// app/Http/Controllers/LeaveWorkflowController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepriseSiege;
use App\Models\LeaveWorkflow;
use App\Models\SiteLeaveWorkflowSetting;
use App\Services\LeaveWorkflowResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveWorkflowController extends Controller
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

    private function getDefaultSteps(): array
    {
        return [
            [
                'order' => 1,
                'role' => 'manager',
                'label' => 'Validation Manager',
                'description' => 'Le manager valide la demande'
            ],
            [
                'order' => 2,
                'role' => 'hr',
                'label' => 'Validation RH',
                'description' => 'Le service RH approuve la demande'
            ]
        ];
    }

    /**
     * Normalise les steps quel que soit le format d'entrée
     */
    private function normalizeSteps($steps): array
    {
        // Si c'est une chaîne JSON, la décoder
        if (is_string($steps)) {
            $steps = json_decode($steps, true);
        }

        // Si ce n'est pas un tableau, retourner les steps par défaut
        if (!is_array($steps) || empty($steps)) {
            return $this->getDefaultSteps();
        }

        // Nettoyer chaque étape
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

        // Si après nettoyage il n'y a rien, retourner les steps par défaut
        if (empty($normalized)) {
            return $this->getDefaultSteps();
        }

        // Trier par ordre
        usort($normalized, function($a, $b) {
            return $a['order'] - $b['order'];
        });

        return $normalized;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', LeaveWorkflow::class);

        $query = LeaveWorkflow::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $workflows = $query->paginate(15);

        if (! $this->isSuperAdmin()) {
            $resolver = new LeaveWorkflowResolver();
            $resolvedWorkflows = $resolver->resolveCollection($workflows->getCollection(), $this->getUserSiteId());
            $workflows->setCollection($resolvedWorkflows);
        }

        return view('conges.leave_workflows.index', compact('workflows'));
    }

    public function create()
    {
        $this->authorize('create', LeaveWorkflow::class);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        return view('conges.leave_workflows.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveWorkflow::class);

        $isAdmin = $this->isSuperAdmin();

        // Règles de validation (sans steps car on le gère manuellement)
        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        $exists = LeaveWorkflow::where('name', $validated['name'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Un workflow avec ce nom existe déjà pour ce siège.'])
                ->withInput();
        }

        // Traitement des steps - RÉCUPÉRATION CORRECTE
        $steps = $request->input('steps');
        
        // Normaliser les steps
        $validated['steps'] = $this->normalizeSteps($steps);
        
        // Vérifier qu'il y a au moins une étape
        if (empty($validated['steps'])) {
            return back()
                ->withErrors(['steps' => 'Veuillez définir au moins une étape de validation.'])
                ->withInput();
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
        if (! $this->isSuperAdmin() && $leaveWorkflow->isGlobal()) {
            $resolver = new LeaveWorkflowResolver();
            $resolved = $resolver->resolve($leaveWorkflow, $this->getUserSiteId());
        }

        return view('conges.leave_workflows.show', compact('leaveWorkflow', 'resolved'));
    }

    public function edit(LeaveWorkflow $leaveWorkflow)
    {
        $this->authorize('update', $leaveWorkflow);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        $override = null;
        if (! $this->isSuperAdmin() && $leaveWorkflow->isGlobal() && $leaveWorkflow->is_customizable) {
            $override = SiteLeaveWorkflowSetting::where('site_id', $this->getUserSiteId())
                ->where('leave_workflow_id', $leaveWorkflow->id)
                ->first();
        }

        return view('conges.leave_workflows.edit', compact('leaveWorkflow', 'sites', 'override'));
    }

    public function update(Request $request, LeaveWorkflow $leaveWorkflow)
    {
        $this->authorize('update', $leaveWorkflow);

        $isAdmin = $this->isSuperAdmin();

        if (! $isAdmin && $leaveWorkflow->isGlobal() && $leaveWorkflow->is_customizable) {
            return $this->updateOverride($request, $leaveWorkflow);
        }

        // Règles de validation (sans steps)
        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
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
                return back()
                    ->withErrors(['name' => 'Un workflow avec ce nom existe déjà pour ce siège.'])
                    ->withInput();
            }
        }

        // Traitement des steps
        $steps = $request->input('steps');
        $validated['steps'] = $this->normalizeSteps($steps);

        if (empty($validated['steps'])) {
            return back()
                ->withErrors(['steps' => 'Veuillez définir au moins une étape de validation.'])
                ->withInput();
        }

        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $leaveWorkflow->update($validated);

        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Workflow de congé mis à jour avec succès.');
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
            ->with('success', 'Workflow de congé restauré avec succès.');
    }

    private function updateOverride(Request $request, LeaveWorkflow $leaveWorkflow)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Traitement des steps
        $steps = $request->input('steps');
        $normalizedSteps = $this->normalizeSteps($steps);

        $overrideData = [
            'site_id' => $siteId,
            'leave_workflow_id' => $leaveWorkflow->id,
        ];

        if ($request->has('name')) {
            $overrideData['name'] = $validated['name'] ?: null;
        }
        if ($request->has('description')) {
            $overrideData['description'] = $validated['description'] ?: null;
        }
        if ($request->has('steps') && !empty($normalizedSteps)) {
            $overrideData['steps'] = $normalizedSteps;
        }
        if ($request->has('is_default')) {
            $overrideData['is_default'] = $request->boolean('is_default');
        }
        if ($request->has('is_active')) {
            $overrideData['is_active'] = $request->boolean('is_active');
        }

        SiteLeaveWorkflowSetting::updateOrCreate(
            [
                'site_id' => $siteId,
                'leave_workflow_id' => $leaveWorkflow->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.leave-workflows.index')
            ->with('success', 'Configuration locale du workflow mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?LeaveWorkflow $ignore = null): array
    {
        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}