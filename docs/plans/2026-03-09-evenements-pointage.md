# Événements d'erreur de pointage — Plan d'implémentation

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Ajouter un module de détection et correction des erreurs de pointage accessible aux Simple Admins (correction) et Super Admins (lecture seule).

**Architecture:** Détection en temps réel (pas de table pour les erreurs de données) + table `pointage_event_exceptions` uniquement pour les acknowledgments des erreurs contextuelles (jour férié / weekend). Les corrections (add/delete pointage) réutilisent les routes existantes de `PointageController` via `redirect()->back()`. Aucune logique existante n'est modifiée hormis l'ajout d'un guard d'export dans `ReportController`.

**Tech Stack:** Laravel, MySQL, Bootstrap 5, Bootstrap Icons, Blade — même stack que l'existant.

---

## Contraintes critiques

- **Ne pas modifier** les méthodes existantes de `PointageController`, `PointageRequest`, `PointageRepository`
- **Ne pas modifier** les routes, middlewares, gates existants
- **Ne pas modifier** les modèles existants
- Toujours utiliser `withoutGlobalScope(\App\Scopes\SiegeScope::class)` pour les requêtes internes au service
- Les modifications à `ReportController` et `navigation.blade.php` sont les seuls fichiers existants touchés

---

## Task 1 : Migration — Table `pointage_event_exceptions`

**Files:**
- Create: `database/migrations/2026_03_09_000000_create_pointage_event_exceptions_table.php`

**Step 1: Créer le fichier de migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pointage_event_exceptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->enum('error_type', ['pointage_jour_ferie', 'pointage_weekend']);
            $table->unsignedInteger('SiegeID');
            $table->unsignedBigInteger('acknowledged_by');
            $table->dateTime('acknowledged_at');
            $table->text('note')->nullable();

            $table->unique(['employee_id', 'date', 'error_type'], 'unique_exception');
            $table->index('SiegeID');
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pointage_event_exceptions');
    }
};
```

**Step 2: Exécuter la migration**

```bash
cd /d/FRANCO/GeoTrack/Project_Antigravity/kosi-pointage
php artisan migrate
```

Expected: `Migrating: 2026_03_09_000000_create_pointage_event_exceptions_table` puis `Migrated`.

**Step 3: Commit**

```bash
git add database/migrations/2026_03_09_000000_create_pointage_event_exceptions_table.php
git commit -m "feat: add pointage_event_exceptions migration"
```

---

## Task 2 : Modèle `PointageEventException`

**Files:**
- Create: `app/Models/PointageEventException.php`

**Step 1: Créer le modèle**

```php
<?php
// app/Models/PointageEventException.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointageEventException extends Model
{
    public $timestamps = false;
    protected $table = 'pointage_event_exceptions';

    protected $fillable = [
        'employee_id',
        'date',
        'error_type',
        'SiegeID',
        'acknowledged_by',
        'acknowledged_at',
        'note',
    ];

    protected $casts = [
        'date'            => 'date',
        'acknowledged_at' => 'datetime',
    ];

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'employee_id', 'ID');
    }

    public function siege(): BelongsTo
    {
        return $this->belongsTo(EntrepriseSiege::class, 'SiegeID', 'ID');
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(Administration::class, 'acknowledged_by', 'ID');
    }
}
```

**Step 2: Commit**

```bash
git add app/Models/PointageEventException.php
git commit -m "feat: add PointageEventException model"
```

---

## Task 3 : Service `EventDetectionService`

**Files:**
- Create: `app/Services/EventDetectionService.php`

**Step 1: Créer le service**

```php
<?php
// app/Services/EventDetectionService.php

namespace App\Services;

use App\Models\Employe;
use App\Scopes\SiegeScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventDetectionService
{
    /**
     * Détecte toutes les erreurs non résolues pour un siège.
     */
    public function detect(int $siegeId): Collection
    {
        return collect()
            ->merge($this->detectDoublonsEntree($siegeId))
            ->merge($this->detectDoublonsSortie($siegeId))
            ->merge($this->detectManqueSortie($siegeId))
            ->merge($this->detectManqueEntree($siegeId))
            ->merge($this->detectJourFerie($siegeId))
            ->merge($this->detectWeekend($siegeId))
            ->sortBy('date');
    }

    /**
     * Compte les erreurs non résolues (utilisé pour bloquer les exports).
     */
    public function countUnresolved(int $siegeId): int
    {
        return $this->detect($siegeId)->count();
    }

    // ─── DOUBLON ENTRÉE ───────────────────────────────────────────────────────

    private function detectDoublonsEntree(int $siegeId): Collection
    {
        $rows = DB::table('Pointages')
            ->where('SiegeID', $siegeId)
            ->where('type_', 'entry')
            ->select('employee_id', DB::raw("DATE(timestamp_) as date"), DB::raw('COUNT(*) as cnt'))
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->having('cnt', '>', 1)
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'doublon_entree', 'Doublon d\'entrée', 'danger', 'bi-arrow-down-circle-fill',
            $row->employee_id, $row->date, $siegeId, 'entry'
        ));
    }

    // ─── DOUBLON SORTIE ───────────────────────────────────────────────────────

    private function detectDoublonsSortie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages')
            ->where('SiegeID', $siegeId)
            ->where('type_', 'exit')
            ->select('employee_id', DB::raw("DATE(timestamp_) as date"), DB::raw('COUNT(*) as cnt'))
            ->groupBy('employee_id', DB::raw('DATE(timestamp_)'))
            ->having('cnt', '>', 1)
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'doublon_sortie', 'Doublon de sortie', 'warning', 'bi-arrow-up-circle-fill',
            $row->employee_id, $row->date, $siegeId, 'exit'
        ));
    }

    // ─── MANQUE SORTIE ────────────────────────────────────────────────────────

    private function detectManqueSortie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->where('p.type_', 'entry')
            ->whereDate('p.timestamp_', '<', Carbon::today())
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) use ($siegeId) {
                $q->from('Pointages as p2')
                  ->whereColumn('p2.employee_id', 'p.employee_id')
                  ->whereRaw('DATE(p2.timestamp_) = DATE(p.timestamp_)')
                  ->where('p2.type_', 'exit')
                  ->where('p2.SiegeID', $siegeId);
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'manque_sortie', 'Manque de sortie', 'warning', 'bi-box-arrow-right',
            $row->employee_id, $row->date, $siegeId, null, 'exit'
        ));
    }

    // ─── MANQUE ENTRÉE ────────────────────────────────────────────────────────

    private function detectManqueEntree(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->where('p.type_', 'exit')
            ->whereDate('p.timestamp_', '<', Carbon::today())
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) use ($siegeId) {
                $q->from('Pointages as p2')
                  ->whereColumn('p2.employee_id', 'p.employee_id')
                  ->whereRaw('DATE(p2.timestamp_) = DATE(p.timestamp_)')
                  ->where('p2.type_', 'entry')
                  ->where('p2.SiegeID', $siegeId);
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'manque_entree', 'Manque d\'entrée', 'info', 'bi-box-arrow-in-right',
            $row->employee_id, $row->date, $siegeId, null, 'entry'
        ));
    }

    // ─── JOUR FÉRIÉ ───────────────────────────────────────────────────────────

    private function detectJourFerie(int $siegeId): Collection
    {
        $rows = DB::table('Pointages as p')
            ->join('jours_non_travailles as j', function ($join) use ($siegeId) {
                $join->whereRaw('DATE(p.timestamp_) = j.Date')
                     ->where('j.SiegeID', $siegeId)
                     ->where('j.Actived', 1);
            })
            ->where('p.SiegeID', $siegeId)
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"), 'j.Nom as jour_nom')
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_jour_ferie');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'), 'j.Nom')
            ->get();

        return $rows->map(fn($row) => $this->buildError(
            'pointage_jour_ferie', 'Pointage jour férié', 'secondary', 'bi-calendar-x-fill',
            $row->employee_id, $row->date, $siegeId, null, null, "Jour : {$row->jour_nom}"
        ));
    }

    // ─── WEEKEND ──────────────────────────────────────────────────────────────

    private function detectWeekend(int $siegeId): Collection
    {
        // DAYOFWEEK MySQL : 1=Dimanche, 7=Samedi
        $rows = DB::table('Pointages as p')
            ->where('p.SiegeID', $siegeId)
            ->whereRaw('DAYOFWEEK(p.timestamp_) IN (1, 7)')
            ->select('p.employee_id', DB::raw("DATE(p.timestamp_) as date"))
            ->whereNotExists(function ($q) {
                $q->from('pointage_event_exceptions as e')
                  ->whereColumn('e.employee_id', 'p.employee_id')
                  ->whereRaw('e.date = DATE(p.timestamp_)')
                  ->where('e.error_type', 'pointage_weekend');
            })
            ->groupBy('p.employee_id', DB::raw('DATE(p.timestamp_)'))
            ->get();

        return $rows->map(function ($row) use ($siegeId) {
            $date = Carbon::parse($row->date);
            $jourSemaine = $date->dayOfWeek === 0 ? 'Dimanche' : 'Samedi';
            return $this->buildError(
                'pointage_weekend', 'Pointage weekend', 'secondary', 'bi-calendar2-week-fill',
                $row->employee_id, $row->date, $siegeId, null, null, $jourSemaine
            );
        });
    }

    // ─── BUILDER ──────────────────────────────────────────────────────────────

    private function buildError(
        string $type,
        string $label,
        string $color,
        string $icon,
        int    $employeeId,
        string $date,
        int    $siegeId,
        ?string $filterType = null,   // filtre pour les pointages à afficher (entry/exit)
        ?string $manqueType = null,   // type à ajouter si manque (entry/exit)
        ?string $extra = null
    ): object {
        $employe = Employe::withoutGlobalScope(SiegeScope::class)->find($employeeId);

        $query = DB::table('Pointages')
            ->where('employee_id', $employeeId)
            ->where('SiegeID', $siegeId)
            ->whereDate('timestamp_', $date)
            ->orderBy('timestamp_');

        if ($filterType) {
            $query->where('type_', $filterType);
        }

        $pointages = $query->get();

        return (object) [
            'type'       => $type,
            'label'      => $label,
            'color'      => $color,
            'icon'       => $icon,
            'employee'   => $employe,
            'date'       => Carbon::parse($date),
            'pointages'  => $pointages,
            'manqueType' => $manqueType,  // 'entry' ou 'exit' si c'est un manque
            'extra'      => $extra,
            'key'        => "{$type}_{$employeeId}_{$date}",
        ];
    }
}
```

**Step 2: Commit**

```bash
git add app/Services/EventDetectionService.php
git commit -m "feat: add EventDetectionService for pointage error detection"
```

---

## Task 4 : Contrôleur `EventPointageController`

**Files:**
- Create: `app/Http/Controllers/EventPointageController.php`

**Step 1: Créer le contrôleur**

```php
<?php
// app/Http/Controllers/EventPointageController.php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\EntrepriseSiege;
use App\Models\PointageEventException;
use App\Services\EventDetectionService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class EventPointageController extends Controller
{
    public function __construct(private EventDetectionService $detector) {}

    public function index(Request $request)
    {
        $user = auth()->user();

        // Super Admin → lecture seule, tous les sièges avec filtre optionnel
        if ($user->isTrueSuperAdmin()) {
            $sieges  = EntrepriseSiege::all();
            $siegeId = $request->input('SiegeID') ? (int) $request->input('SiegeID') : null;

            if ($siegeId) {
                $erreurs = $this->detector->detect($siegeId);
            } else {
                $erreurs = collect();
                foreach ($sieges as $siege) {
                    $erreurs = $erreurs->merge($this->detector->detect($siege->ID));
                }
            }

            return view('evenements.index', [
                'erreurs'     => $erreurs,
                'sieges'      => $sieges,
                'siegeId'     => $siegeId,
                'readOnly'    => true,
                'totalCount'  => $erreurs->count(),
                'filterType'  => $request->input('filter_type'),
                'sites'       => collect(),
            ]);
        }

        // Simple Admin → correction possible, son siège uniquement
        if ($user->isSimpleAdmin()) {
            $erreurs = $this->detector->detect($user->SiegeID);
            $sites   = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 1)->get();

            return view('evenements.index', [
                'erreurs'     => $erreurs,
                'sieges'      => collect(),
                'siegeId'     => $user->SiegeID,
                'readOnly'    => false,
                'totalCount'  => $erreurs->count(),
                'filterType'  => $request->input('filter_type'),
                'sites'       => $sites,
            ]);
        }

        abort(403, 'Accès non autorisé');
    }

    public function acknowledge(Request $request)
    {
        $user = auth()->user();

        if (!$user->isSimpleAdmin()) {
            abort(403, 'Réservé aux administrateurs simples');
        }

        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:Employes,ID',
            'date'        => 'required|date',
            'error_type'  => 'required|in:pointage_jour_ferie,pointage_weekend',
            'note'        => 'nullable|string|max:500',
        ]);

        PointageEventException::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date'        => $validated['date'],
                'error_type'  => $validated['error_type'],
            ],
            [
                'SiegeID'         => $user->SiegeID,
                'acknowledged_by' => $user->ID,
                'acknowledged_at' => now(),
                'note'            => $validated['note'] ?? null,
            ]
        );

        ActivityLogService::log(
            action: 'acknowledge',
            modelType: 'PointageEvent',
            modelLabel: "{$validated['error_type']} - employé #{$validated['employee_id']} - {$validated['date']}",
        );

        return redirect()->route('evenements.index')
            ->with('success', 'Événement marqué comme intentionnel.');
    }

    public function removeAcknowledge(int $id)
    {
        $user = auth()->user();

        if (!$user->isSimpleAdmin()) {
            abort(403, 'Réservé aux administrateurs simples');
        }

        $exception = PointageEventException::where('id', $id)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        ActivityLogService::log(
            action: 'remove_acknowledge',
            modelType: 'PointageEvent',
            modelId: $exception->id,
            modelLabel: "{$exception->error_type} - employé #{$exception->employee_id} - {$exception->date}",
        );

        $exception->delete();

        return redirect()->route('evenements.index')
            ->with('success', 'Acknowledgment supprimé. L\'événement est de nouveau visible.');
    }
}
```

**Step 2: Commit**

```bash
git add app/Http/Controllers/EventPointageController.php
git commit -m "feat: add EventPointageController"
```

---

## Task 5 : Routes

**Files:**
- Modify: `routes/web.php`

**Step 1: Ajouter le use en haut du fichier** (après la ligne `use App\Http\Controllers\ActivityLogController;`)

```php
use App\Http\Controllers\EventPointageController;
```

**Step 2: Ajouter les routes dans le groupe `siege.access`**

Chercher le bloc `// ========== JOURS NON TRAVAILLÉS` et ajouter APRÈS sa fermeture `});` et AVANT la fermeture du groupe `siege.access` :

```php
        // ========== ÉVÉNEMENTS D'ERREUR POINTAGE ==========
        // Accessible aux Simple Admin (correction) et Super Admin (lecture seule)
        // Non accessible aux Vendeurs (block.sellers)
        Route::middleware('block.sellers')->group(function () {
            Route::get('/evenements', [EventPointageController::class, 'index'])->name('evenements.index');
            Route::post('/evenements/acknowledge', [EventPointageController::class, 'acknowledge'])->name('evenements.acknowledge');
            Route::delete('/evenements/acknowledge/{id}', [EventPointageController::class, 'removeAcknowledge'])->name('evenements.remove-acknowledge');
        });
```

**Step 3: Vérifier que les routes sont bien enregistrées**

```bash
php artisan route:list | grep evenements
```

Expected output :
```
GET|HEAD  evenements              evenements.index
POST      evenements/acknowledge  evenements.acknowledge
DELETE    evenements/acknowledge/{id}  evenements.remove-acknowledge
```

**Step 4: Commit**

```bash
git add routes/web.php
git commit -m "feat: add evenements routes"
```

---

## Task 6 : Blocage des exports dans `ReportController`

**Files:**
- Modify: `app/Http/Controllers/ReportController.php`

**Step 1: Ajouter le use en haut** (après les autres use)

```php
use App\Services\EventDetectionService;
```

**Step 2: Ajouter le guard au début de `exportExcel()`**

Trouver la méthode `public function exportExcel(Request $request, string $type)` et ajouter ces lignes **immédiatement après** `DB::statement(...)` :

```php
        // Bloquer l'export si le Simple Admin a des erreurs non résolues
        if (auth()->user()->isSimpleAdmin()) {
            $count = app(EventDetectionService::class)->countUnresolved(auth()->user()->SiegeID);
            if ($count > 0) {
                return redirect()->route('evenements.index')
                    ->with('error', "Vous avez {$count} événement(s) non résolu(s). Corrigez-les avant d'exporter les rapports.");
            }
        }
```

**Step 3: Même guard au début de `exportPdf()`**

Même opération sur `public function exportPdf(Request $request, string $type)` :

```php
        // Bloquer l'export si le Simple Admin a des erreurs non résolues
        if (auth()->user()->isSimpleAdmin()) {
            $count = app(EventDetectionService::class)->countUnresolved(auth()->user()->SiegeID);
            if ($count > 0) {
                return redirect()->route('evenements.index')
                    ->with('error', "Vous avez {$count} événement(s) non résolu(s). Corrigez-les avant d'exporter les rapports.");
            }
        }
```

**Step 4: Commit**

```bash
git add app/Http/Controllers/ReportController.php
git commit -m "feat: block report exports for simple admin with unresolved events"
```

---

## Task 7 : Vue principale `evenements/index.blade.php`

**Files:**
- Create: `resources/views/evenements/index.blade.php`

**Step 1: Créer le répertoire et la vue**

```php
{{-- resources/views/evenements/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                {{ __('Événements de pointage') }}
                @if($totalCount > 0)
                    <span class="badge bg-danger ms-2">{{ $totalCount }}</span>
                @else
                    <span class="badge bg-success ms-2">{{ __('Tout est correct') }}</span>
                @endif
            </h2>
            @if($readOnly)
                <span class="badge bg-secondary fs-6">
                    <i class="bi bi-eye me-1"></i>{{ __('Lecture seule') }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="p-2">

        {{-- Alerte exports bloqués (Simple Admin uniquement) --}}
        @if(!$readOnly && $totalCount > 0)
        <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-lock-fill fs-4 me-3"></i>
            <div>
                <strong>{{ __('Exports des rapports bloqués') }}</strong> —
                {{ __('Vous avez') }} <strong>{{ $totalCount }}</strong> {{ __('événement(s) non résolu(s).') }}
                {{ __('Corrigez-les pour débloquer les exports.') }}
            </div>
        </div>
        @endif

        {{-- Message succès --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Filtres --}}
                <form method="GET" action="{{ route('evenements.index') }}" class="mb-4">
                    <div class="row g-3">

                        {{-- Filtre siège (Super Admin uniquement) --}}
                        @if($readOnly && $sieges->count() > 0)
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">{{ __('Siège') }}</label>
                            <select name="SiegeID" class="form-select">
                                <option value="">{{ __('Tous les sièges') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ $siegeId == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Filtre par type d'erreur --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">{{ __('Type d\'événement') }}</label>
                            <select name="filter_type" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="doublon_entree"      {{ $filterType === 'doublon_entree'      ? 'selected' : '' }}>{{ __('Doublon d\'entrée') }}</option>
                                <option value="doublon_sortie"      {{ $filterType === 'doublon_sortie'      ? 'selected' : '' }}>{{ __('Doublon de sortie') }}</option>
                                <option value="manque_sortie"       {{ $filterType === 'manque_sortie'       ? 'selected' : '' }}>{{ __('Manque de sortie') }}</option>
                                <option value="manque_entree"       {{ $filterType === 'manque_entree'       ? 'selected' : '' }}>{{ __('Manque d\'entrée') }}</option>
                                <option value="pointage_jour_ferie" {{ $filterType === 'pointage_jour_ferie' ? 'selected' : '' }}>{{ __('Jour férié') }}</option>
                                <option value="pointage_weekend"    {{ $filterType === 'pointage_weekend'    ? 'selected' : '' }}>{{ __('Weekend') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i>{{ __('Filtrer') }}
                            </button>
                            <a href="{{ route('evenements.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Compteurs par type --}}
                @php
                    $filtered = $filterType
                        ? $erreurs->filter(fn($e) => $e->type === $filterType)->values()
                        : $erreurs;

                    $counts = [
                        'doublon_entree'      => $erreurs->where('type', 'doublon_entree')->count(),
                        'doublon_sortie'      => $erreurs->where('type', 'doublon_sortie')->count(),
                        'manque_sortie'       => $erreurs->where('type', 'manque_sortie')->count(),
                        'manque_entree'       => $erreurs->where('type', 'manque_entree')->count(),
                        'pointage_jour_ferie' => $erreurs->where('type', 'pointage_jour_ferie')->count(),
                        'pointage_weekend'    => $erreurs->where('type', 'pointage_weekend')->count(),
                    ];
                @endphp

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('evenements.index', array_merge(request()->except('filter_type'), [])) }}"
                       class="btn btn-sm {{ !$filterType ? 'btn-dark' : 'btn-outline-dark' }}">
                        {{ __('Tous') }} <span class="badge bg-white text-dark ms-1">{{ $totalCount }}</span>
                    </a>
                    @foreach([
                        'doublon_entree'      => ['label' => 'Doublons entrée',  'color' => 'danger'],
                        'doublon_sortie'      => ['label' => 'Doublons sortie',  'color' => 'warning'],
                        'manque_sortie'       => ['label' => 'Manque sortie',    'color' => 'warning'],
                        'manque_entree'       => ['label' => 'Manque entrée',    'color' => 'info'],
                        'pointage_jour_ferie' => ['label' => 'Jours fériés',     'color' => 'secondary'],
                        'pointage_weekend'    => ['label' => 'Weekends',         'color' => 'secondary'],
                    ] as $key => $meta)
                        @if($counts[$key] > 0)
                        <a href="{{ route('evenements.index', array_merge(request()->except('filter_type'), ['filter_type' => $key])) }}"
                           class="btn btn-sm {{ $filterType === $key ? 'btn-'.$meta['color'] : 'btn-outline-'.$meta['color'] }}">
                            {{ __($meta['label']) }}
                            <span class="badge {{ $filterType === $key ? 'bg-white text-dark' : 'bg-'.$meta['color'].' text-white' }} ms-1">
                                {{ $counts[$key] }}
                            </span>
                        </a>
                        @endif
                    @endforeach
                </div>

                {{-- Liste des erreurs --}}
                @forelse($filtered as $erreur)
                <div class="card border-{{ $erreur->color }} mb-3">
                    <div class="card-header bg-{{ $erreur->color }} bg-opacity-10 d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $erreur->icon }} text-{{ $erreur->color }} fs-5"></i>
                            <span class="badge bg-{{ $erreur->color }}">{{ __($erreur->label) }}</span>
                            <strong>{{ $erreur->employee->Nom ?? '—' }}</strong>
                            @if($erreur->employee->num_mat)
                                <span class="text-muted small">· N° {{ $erreur->employee->num_mat }}</span>
                            @endif
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ ucfirst($erreur->date->isoFormat('dddd D MMMM YYYY')) }}
                            @if($erreur->extra)
                                <span class="badge bg-secondary ms-2">{{ $erreur->extra }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body py-2">

                        {{-- Pointages concernés --}}
                        @if($erreur->pointages->count() > 0)
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-secondary text-uppercase">{{ __('Heure') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('Type') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('Méthode') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('GPS') }}</th>
                                        @if(!$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']))
                                            <th class="small text-secondary text-uppercase">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($erreur->pointages as $i => $p)
                                    <tr class="{{ !$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']) && $i > 0 ? 'table-danger' : '' }}">
                                        <td class="align-middle">
                                            <strong>{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge {{ $p->type_ === 'entry' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $p->type_ === 'entry' ? __('Entrée') : __('Sortie') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark border">{{ $p->auth_method }}</span>
                                        </td>
                                        <td class="align-middle text-muted small">
                                            @if($p->latitude && $p->longitude)
                                                {{ number_format($p->latitude, 4) }}, {{ number_format($p->longitude, 4) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        @if(!$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']))
                                        <td class="align-middle">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete"
                                                data-id="{{ $p->ID }}"
                                                data-heure="{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}"
                                                data-employe="{{ $erreur->employee->Nom ?? '' }}">
                                                <i class="bi bi-trash me-1"></i>{{ __('Supprimer') }}
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted small mb-2"><em>{{ __('Aucun pointage trouvé pour ce jour.') }}</em></p>
                        @endif

                        {{-- Actions de correction (Simple Admin uniquement) --}}
                        @if(!$readOnly)
                            {{-- Ajouter un manque --}}
                            @if(in_array($erreur->type, ['manque_sortie', 'manque_entree']))
                            <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAjoutPointage"
                                data-employee-id="{{ $erreur->employee->ID }}"
                                data-employee-nom="{{ $erreur->employee->Nom }}"
                                data-date="{{ $erreur->date->format('Y-m-d') }}"
                                data-type="{{ $erreur->manqueType }}"
                                data-siege="{{ $siegeId }}">
                                <i class="bi bi-plus-circle me-1"></i>
                                {{ $erreur->manqueType === 'entry' ? __('Ajouter une entrée') : __('Ajouter une sortie') }}
                            </button>
                            @endif

                            {{-- Acknowledger (jour férié / weekend) --}}
                            @if(in_array($erreur->type, ['pointage_jour_ferie', 'pointage_weekend']))
                            <button type="button"
                                class="btn btn-sm btn-outline-success"
                                data-bs-toggle="modal"
                                data-bs-target="#modalAcknowledge"
                                data-employee-id="{{ $erreur->employee->ID }}"
                                data-employee-nom="{{ $erreur->employee->Nom }}"
                                data-date="{{ $erreur->date->format('Y-m-d') }}"
                                data-error-type="{{ $erreur->type }}">
                                <i class="bi bi-check-circle me-1"></i>{{ __('Marquer comme intentionnel') }}
                            </button>
                            @endif
                        @endif

                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-success">{{ __('Aucun événement') }}</h5>
                    <p class="text-muted">{{ __('Tous les pointages sont corrects.') }}</p>
                    @if(!$readOnly)
                        <a href="{{ route('reports.index') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-file-earmark-text me-1"></i>{{ __('Accéder aux rapports') }}
                        </a>
                    @endif
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- ═══ MODAL : Supprimer un pointage (doublon) ═══ --}}
    <div class="modal fade" id="modalDelete" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-trash me-2"></i>{{ __('Supprimer ce pointage') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Voulez-vous supprimer le pointage de') }} <strong id="deleteEmployeNom"></strong>
                    {{ __('à') }} <strong id="deleteHeure"></strong> ?</p>
                    <p class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>{{ __('Cette action est irréversible.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                    <form id="formDelete" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>{{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL : Ajouter un pointage manquant ═══ --}}
    <div class="modal fade" id="modalAjoutPointage" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>{{ __('Ajouter un pointage manquant') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('pointages.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ __('Correction manuelle pour') }} : <strong id="ajoutEmployeNom"></strong>
                        </div>

                        {{-- Champs cachés --}}
                        <input type="hidden" name="employee_id" id="ajoutEmployeeId">
                        <input type="hidden" name="type_"       id="ajoutType">
                        <input type="hidden" name="SiegeID"     value="{{ $siegeId }}">
                        <input type="hidden" name="latitude"    value="0">
                        <input type="hidden" name="longitude"   value="0">
                        <input type="hidden" name="synced"      value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Date et heure') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="timestamp_" id="ajoutTimestamp" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('Méthode') }} <span class="text-danger">*</span></label>
                                <select name="auth_method" class="form-select" required>
                                    <option value="pin">{{ __('PIN (correction manuelle)') }}</option>
                                    <option value="rfid">RFID</option>
                                    <option value="face">{{ __('Reconnaissance faciale') }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('Site / Établissement') }} <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-select" required>
                                    <option value="">{{ __('Sélectionner...') }}</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->ID }}">{{ $site->Nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>{{ __('Enregistrer le pointage') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL : Acknowledger (jour férié / weekend) ═══ --}}
    <div class="modal fade" id="modalAcknowledge" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>{{ __('Marquer comme intentionnel') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('evenements.acknowledge') }}">
                    @csrf
                    <div class="modal-body">
                        <p>{{ __('Ce pointage de') }} <strong id="ackEmployeNom"></strong> {{ __('le') }}
                        <strong id="ackDate"></strong> {{ __('sera considéré comme intentionnel (ex: astreinte, heures sup).') }}</p>

                        <input type="hidden" name="employee_id" id="ackEmployeeId">
                        <input type="hidden" name="date"        id="ackDateInput">
                        <input type="hidden" name="error_type"  id="ackErrorType">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('Note (optionnelle)') }}</label>
                            <textarea name="note" class="form-control" rows="2"
                                placeholder="{{ __('Ex : Astreinte weekend, heures supplémentaires validées...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>{{ __('Confirmer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // Modal Supprimer
    document.getElementById('modalDelete').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id = btn.getAttribute('data-id');
        document.getElementById('deleteEmployeNom').textContent = btn.getAttribute('data-employe');
        document.getElementById('deleteHeure').textContent = btn.getAttribute('data-heure');
        document.getElementById('formDelete').action = '/pointages/' + id;
    });

    // Modal Ajouter pointage manquant
    document.getElementById('modalAjoutPointage').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('ajoutEmployeNom').textContent = btn.getAttribute('data-employee-nom');
        document.getElementById('ajoutEmployeeId').value = btn.getAttribute('data-employee-id');
        document.getElementById('ajoutType').value = btn.getAttribute('data-type');

        // Pré-remplir la date avec la date de l'erreur à 08:00
        const date = btn.getAttribute('data-date');
        document.getElementById('ajoutTimestamp').value = date + 'T08:00';
    });

    // Modal Acknowledger
    document.getElementById('modalAcknowledge').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('ackEmployeNom').textContent = btn.getAttribute('data-employee-nom');
        document.getElementById('ackDate').textContent = btn.getAttribute('data-date');
        document.getElementById('ackEmployeeId').value = btn.getAttribute('data-employee-id');
        document.getElementById('ackDateInput').value = btn.getAttribute('data-date');
        document.getElementById('ackErrorType').value = btn.getAttribute('data-error-type');
    });
    </script>
    @endpush

</x-app-layout>
```

**Step 2: Commit**

```bash
git add resources/views/evenements/index.blade.php
git commit -m "feat: add evenements index view with correction modals"
```

---

## Task 8 : Navigation — Ajout du lien

**Files:**
- Modify: `resources/views/layouts/navigation.blade.php`

**Step 1: Ajouter le lien après le lien "Jour férié"**

Trouver le bloc :
```html
                    {{-- Jour férié : Super Admin et Simple Admin uniquement --}}
                    <li class="nav-item">
                        <a href="{{ route('jours-non-travailles.index') }}" ...>
```

Juste AVANT le `@endif` qui ferme le bloc `@if(!auth()->user()->isSeller())`, ajouter :

```html
                    {{-- Événements : Super Admin (lecture) et Simple Admin (correction) --}}
                    @if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
                    <li class="nav-item">
                        <a href="{{ route('evenements.index') }}"
                           class="nav-link {{ request()->routeIs('evenements.*') ? 'active-link' : '' }}">
                            <i class="bi bi-exclamation-triangle"></i> {{ __('Événements') }}
                            @if(auth()->user()->isSimpleAdmin())
                                @php
                                    $navErrCount = app(\App\Services\EventDetectionService::class)
                                        ->countUnresolved(auth()->user()->SiegeID);
                                @endphp
                                @if($navErrCount > 0)
                                    <span class="badge bg-danger rounded-pill"
                                          style="font-size: 0.65rem; vertical-align: middle;">
                                        {{ $navErrCount }}
                                    </span>
                                @endif
                            @endif
                        </a>
                    </li>
                    @endif
```

**Step 2: Vérifier visuellement**

Ouvrir l'application dans le navigateur, se connecter en tant que Simple Admin et vérifier que le lien "Événements" apparaît avec un badge rouge si des erreurs existent.

**Step 3: Commit**

```bash
git add resources/views/layouts/navigation.blade.php
git commit -m "feat: add evenements nav link with error badge"
```

---

## Task 9 : Tests manuels

### Scénario 1 — Simple Admin avec erreurs
1. Se connecter en tant que Simple Admin
2. Aller sur `/evenements` → vérifier que les erreurs s'affichent
3. Cliquer "Supprimer" sur un doublon → vérifier que l'erreur disparaît
4. Cliquer "Ajouter une sortie" sur un manque → remplir le formulaire → vérifier que le pointage est créé et l'erreur disparaît
5. Cliquer "Marquer comme intentionnel" sur un jour férié → vérifier que l'erreur disparaît
6. Aller sur `/reports` → tenter d'exporter → vérifier que l'export est bloqué si des erreurs subsistent
7. Corriger toutes les erreurs → vérifier que l'export fonctionne

### Scénario 2 — Super Admin
1. Se connecter en tant que Super Admin
2. Aller sur `/evenements` → vérifier le badge "Lecture seule"
3. Vérifier l'absence de boutons d'action (Supprimer, Ajouter, Acknowledger)
4. Filtrer par siège → vérifier que les erreurs du siège s'affichent
5. Tenter d'exporter un rapport → vérifier que l'export n'est PAS bloqué

### Scénario 3 — Vendeur
1. Se connecter en tant que Vendeur
2. Tenter d'accéder à `/evenements` → vérifier qu'une erreur 403 s'affiche

### Scénario 4 — Aucune erreur
1. Se connecter en tant que Simple Admin sans erreurs
2. Aller sur `/evenements` → vérifier le message "Aucun événement" et le bouton vers les rapports
3. Vérifier que le badge dans la navigation n'apparaît pas

---

## Récapitulatif des fichiers

| Action | Fichier |
|---|---|
| Créer | `database/migrations/2026_03_09_000000_create_pointage_event_exceptions_table.php` |
| Créer | `app/Models/PointageEventException.php` |
| Créer | `app/Services/EventDetectionService.php` |
| Créer | `app/Http/Controllers/EventPointageController.php` |
| Créer | `resources/views/evenements/index.blade.php` |
| Modifier | `routes/web.php` (3 routes + 1 use) |
| Modifier | `app/Http/Controllers/ReportController.php` (2 guards d'export) |
| Modifier | `resources/views/layouts/navigation.blade.php` (1 bloc nav) |
