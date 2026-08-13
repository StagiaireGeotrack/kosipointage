<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepriseSiege;
use App\Models\LeaveType;
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
        $leaveTypes = LeaveType::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('name')
            ->get();

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

        // Vérification doublon code + site_id
        $exists = LeaveType::where('code', $validated['code'])
            ->where('site_id', $validated['site_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['code' => 'Ce code est déjà utilisé pour ce siège.'])
                ->withInput();
        }

        $validated['deducts_balance']        = $request->boolean('deducts_balance');
        $validated['allow_negative_balance'] = $request->boolean('allow_negative_balance');
        $validated['is_active']              = $request->boolean('is_active', true);

        // ← CORRIGÉ : nettoyage aussi dans store (était seulement dans update)
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

        return view('conges.leave_types.edit', compact('leaveType', 'sites'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $isAdmin = $this->isSuperAdmin();
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

    private function rules(bool $isAdmin, ?LeaveType $ignore = null): array
    {
        $rules = [
            'name'                      => 'required|string|max:100',
            'code'                      => [
                'required',
                'string',
                'max:20',
                // ← CORRIGÉ : utilisation de $ignore pour ignorer l'enregistrement en cours
                Rule::unique('leave_types')->where(function ($query) use ($isAdmin) {
                    if (! $isAdmin) {
                        $query->where('site_id', $this->getUserSiteId());
                    }
                    // Si admin, on laisse le check manuel ou on ajoute site_id si présent
                })->ignore($ignore?->id),
            ],
            'unit'                      => 'required|in:days,half_days,hours',
            'requires_attachment'       => 'required|in:never,always,after_duration',
            'requires_attachment_after' => 'nullable|integer|min:1',
            'max_negative_limit'        => 'nullable|integer',
            'color'                     => 'required|string|max:7',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
        }

        return $rules;
    }
}