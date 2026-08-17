<?php
// app/Http/Controllers/CompanyHolidayController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use App\Models\EntrepriseSiege;
use App\Models\SiteCompanyHolidaySetting;
use App\Services\CompanyHolidayResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CompanyHolidayController extends Controller
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
        $this->authorize('viewAny', CompanyHoliday::class);

        $query = CompanyHoliday::with('site')
            ->visibleForUser(Auth::user())
            ->orderBy('date');

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $holidays = $query->paginate(15);

        // Admin site : on résout les overrides
        if (! $this->isSuperAdmin()) {
            $resolver = new CompanyHolidayResolver();
            $resolvedHolidays = $resolver->resolveCollection($holidays->getCollection(), $this->getUserSiteId());
            $holidays->setCollection($resolvedHolidays);
        }

        $years = CompanyHoliday::visibleForUser(Auth::user())
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('conges.company_holidays.index', compact('holidays', 'years'));
    }

    public function create()
    {
        $this->authorize('create', CompanyHoliday::class);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        return view('conges.company_holidays.create', compact('sites'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CompanyHoliday::class);

        $isAdmin = $this->isSuperAdmin();
        $validated = $request->validate($this->rules($isAdmin));

        if (! $isAdmin) {
            $validated['site_id'] = $this->getUserSiteId();
        } else {
            $validated['site_id'] = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        $exists = CompanyHoliday::where('date', $validated['date'])
            ->where('name', $validated['name'])
            ->where('site_id', $validated['site_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['date' => 'Ce jour férié existe déjà pour cette date et ce site.'])
                ->withInput();
        }

        $validated['is_recurring'] = $request->boolean('is_recurring', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        CompanyHoliday::create($validated);

        return redirect()->route('admin.company-holidays.index')
            ->with('success', 'Jour férié créé avec succès.');
    }

    public function show(CompanyHoliday $companyHoliday)
    {
        $this->authorize('view', $companyHoliday);

        $resolved = $companyHoliday;
        if (! $this->isSuperAdmin() && $companyHoliday->isGlobal()) {
            $resolver = new CompanyHolidayResolver();
            $resolved = $resolver->resolve($companyHoliday, $this->getUserSiteId());
        }

        return view('conges.company_holidays.show', compact('companyHoliday', 'resolved'));
    }

    public function edit(CompanyHoliday $companyHoliday)
    {
        $this->authorize('update', $companyHoliday);

        $sites = $this->isSuperAdmin()
            ? EntrepriseSiege::orderBy('nom')->get()
            : collect();

        // Charge l'override existant si on est en mode "édition locale d'un global"
        $override = null;
        if (! $this->isSuperAdmin() && $companyHoliday->isGlobal() && $companyHoliday->is_customizable) {
            $override = SiteCompanyHolidaySetting::where('site_id', $this->getUserSiteId())
                ->where('company_holiday_id', $companyHoliday->id)
                ->first();
        }

        return view('conges.company_holidays.edit', compact('companyHoliday', 'sites', 'override'));
    }

    public function update(Request $request, CompanyHoliday $companyHoliday)
    {
        $this->authorize('update', $companyHoliday);

        $isAdmin = $this->isSuperAdmin();

        // CAS SPÉCIAL : Admin site + global customizable → on écrit dans l'override
        if (! $isAdmin && $companyHoliday->isGlobal() && $companyHoliday->is_customizable) {
            return $this->updateOverride($request, $companyHoliday);
        }

        // CAS STANDARD : Super Admin ou jour férié local
        $validated = $request->validate($this->rules($isAdmin, $companyHoliday));

        if (! $isAdmin) {
            unset($validated['site_id']);
            $newSiteId = $companyHoliday->site_id;
        } else {
            $newSiteId = $validated['site_id'] ?? null;
        }

        // Vérification des doublons
        if ($validated['date'] != $companyHoliday->date || 
            $validated['name'] !== $companyHoliday->name || 
            $newSiteId != $companyHoliday->site_id) {
            $exists = CompanyHoliday::where('date', $validated['date'])
                ->where('name', $validated['name'])
                ->where('site_id', $newSiteId)
                ->where('id', '!=', $companyHoliday->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['date' => 'Ce jour férié existe déjà pour cette date et ce site.'])
                    ->withInput();
            }
        }

        $validated['is_recurring'] = $request->boolean('is_recurring');
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($isAdmin) {
            $validated['is_customizable'] = $request->boolean('is_customizable', false);
        }

        $companyHoliday->update($validated);

        return redirect()->route('admin.company-holidays.index')
            ->with('success', 'Jour férié mis à jour avec succès.');
    }

    public function destroy(CompanyHoliday $companyHoliday)
    {
        $this->authorize('delete', $companyHoliday);

        $companyHoliday->delete();

        return redirect()->route('admin.company-holidays.index')
            ->with('success', 'Jour férié supprimé.');
    }

    public function restore($id)
    {
        $holiday = CompanyHoliday::withTrashed()->findOrFail($id);
        $this->authorize('update', $holiday);

        $holiday->restore();

        return redirect()->route('admin.company-holidays.index')
            ->with('success', 'Jour férié restauré avec succès.');
    }

    private function updateOverride(Request $request, CompanyHoliday $companyHoliday)
    {
        $siteId = $this->getUserSiteId();

        $validated = $request->validate([
            'date' => 'nullable|date',
            'name' => 'nullable|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $overrideData = [
            'site_id' => $siteId,
            'company_holiday_id' => $companyHoliday->id,
        ];

        if ($request->has('date')) {
            $overrideData['date'] = $validated['date'];
        }
        if ($request->has('name')) {
            $overrideData['name'] = $validated['name'] ?: null;
        }
        if ($request->has('is_recurring')) {
            $overrideData['is_recurring'] = $request->boolean('is_recurring');
        }
        if ($request->has('is_active')) {
            $overrideData['is_active'] = $request->boolean('is_active');
        }

        SiteCompanyHolidaySetting::updateOrCreate(
            [
                'site_id' => $siteId,
                'company_holiday_id' => $companyHoliday->id,
            ],
            $overrideData
        );

        return redirect()->route('admin.company-holidays.index')
            ->with('success', 'Configuration locale du jour férié mise à jour avec succès.');
    }

    private function rules(bool $isAdmin, ?CompanyHoliday $ignore = null): array
    {
        $rules = [
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ];

        if ($isAdmin) {
            $rules['site_id'] = 'nullable|exists:entreprises_sieges,ID';
            $rules['is_customizable'] = 'nullable|boolean';
        }

        return $rules;
    }
}