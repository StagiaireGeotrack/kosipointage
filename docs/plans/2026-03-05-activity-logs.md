# Activity Logs Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Ajouter un système de logs d'activité non-bloquant qui enregistre toutes les actions utilisateur (CRUD, auth, exports, profil) dans une nouvelle table `activity_logs`, avec une interface lecture-seule réservée aux Super Admins.

**Architecture:** Service central `ActivityLogService` (non-bloquant, try/catch silencieux), trait Eloquent `Loggable` pour la capture automatique du CRUD, listeners d'événements Laravel pour l'auth, appels manuels minimalistes pour les exports/profil/admin. Aucune modification des logiques existantes.

**Tech Stack:** Laravel 12, Eloquent ORM, Blade + Bootstrap (existant), aucun package externe.

---

### Task 1: Migration `create_activity_logs_table`

**Files:**
- Create: `database/migrations/2026_03_05_000000_create_activity_logs_table.php`

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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email');
            $table->string('user_role', 50)->nullable();
            $table->string('action', 50);
            $table->string('model_type', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_label', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->unsignedInteger('SiegeID')->nullable();
            $table->dateTime('created_at');

            $table->index('created_at');
            $table->index('user_id');
            $table->index('SiegeID');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
```

**Step 2: Exécuter la migration**

```bash
php artisan migrate
```

Résultat attendu : `Migrating: 2026_03_05_000000_create_activity_logs_table` puis `Migrated` sans erreur.

---

### Task 2: Modèle `ActivityLog`

**Files:**
- Create: `app/Models/ActivityLog.php`

**Step 1: Créer le modèle**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'user_email',
        'user_role',
        'action',
        'model_type',
        'model_id',
        'model_label',
        'description',
        'ip_address',
        'user_agent',
        'SiegeID',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
```

---

### Task 3: Service `ActivityLogService`

**Files:**
- Create: `app/Services/ActivityLogService.php`

**Step 1: Créer le service**

```php
<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $modelLabel = null,
        ?string $description = null,
        ?string $userEmail = null,
    ): void {
        try {
            $user = Auth::user();

            $userId  = $user?->ID ?? null;
            $email   = $userEmail ?? $user?->Identifiant_email ?? 'unknown';
            $siegeId = $user?->SiegeID ?? null;
            $role    = null;

            if ($user) {
                if ($user->isTrueSuperAdmin())  $role = 'superadmin';
                elseif ($user->isSeller())       $role = 'seller';
                elseif ($user->isSimpleAdmin())  $role = 'simple_admin';
            }

            ActivityLog::create([
                'user_id'     => $userId,
                'user_email'  => $email,
                'user_role'   => $role,
                'action'      => $action,
                'model_type'  => $modelType,
                'model_id'    => $modelId,
                'model_label' => $modelLabel,
                'description' => $description,
                'ip_address'  => Request::ip() ?? '0.0.0.0',
                'user_agent'  => substr(Request::userAgent() ?? '', 0, 500),
                'SiegeID'     => $siegeId,
                'created_at'  => now(),
            ]);

        } catch (\Throwable $e) {
            Log::warning('ActivityLog write failed: ' . $e->getMessage());
        }
    }
}
```

**Principe clé :** Le `try/catch` sur `\Throwable` garantit que même une erreur DB n'interrompt jamais l'application.

---

### Task 4: Trait `Loggable`

**Files:**
- Create: `app/Traits/Loggable.php`

**Step 1: Créer le trait**

```php
<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait Loggable
{
    public string $logLabelField = 'Nom';

    public static function bootLoggable(): void
    {
        static::created(function ($model) {
            ActivityLogService::log(
                action: 'create',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });

        static::updated(function ($model) {
            ActivityLogService::log(
                action: 'update',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });

        static::deleted(function ($model) {
            ActivityLogService::log(
                action: 'delete',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });
    }
}
```

**Note :** Laravel appelle automatiquement `bootLoggable()` lors du boot de tout modèle qui use ce trait.

---

### Task 5: Appliquer le trait `Loggable` sur 6 modèles

⚠️ **NE PAS appliquer à `Administration`** — son CRUD est loggé manuellement dans les contrôleurs (Task 9).

Pour chaque modèle, ajouter UNIQUEMENT les deux lignes indiquées. Ne rien changer d'autre.

**`app/Models/Employe.php`**

Ajouter l'import après les autres `use` :
```php
use App\Traits\Loggable;
```
Ajouter dans la classe (avant les propriétés) :
```php
use Loggable;
```
Label par défaut `'Nom'` — aucune propriété à surcharger.

---

**`app/Models/Entreprise.php`**

Même chose — import + `use Loggable;`.
Label par défaut `'Nom'` ✓

---

**`app/Models/EntrepriseSiege.php`**

Même chose — import + `use Loggable;`.
Label par défaut `'Nom'` ✓

---

**`app/Models/Pointage.php`**

Import + `use Loggable;` + surcharger le champ label :
```php
use Loggable;
public string $logLabelField = 'employee_id';
```

---

**`app/Models/Conge.php`**

Import + `use Loggable;` + surcharger :
```php
use Loggable;
public string $logLabelField = 'employee_id';
```

---

**`app/Models/JourNonTravaille.php`**

Import + `use Loggable;`.
Label par défaut `'Nom'` ✓

---

### Task 6: Listeners pour Login et Logout

**Files:**
- Create: `app/Listeners/LogSuccessfulLogin.php`
- Create: `app/Listeners/LogLogout.php`
- Modify: `app/Providers/AppServiceProvider.php`

**Step 1: Créer `LogSuccessfulLogin.php`**

```php
<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLogService::log(
            action: 'login_success',
            modelType: 'Administration',
            modelId: $event->user->ID,
            modelLabel: $event->user->Identifiant_email,
        );
    }
}
```

**Step 2: Créer `LogLogout.php`**

```php
<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function handle(Logout $event): void
    {
        ActivityLogService::log(
            action: 'logout',
            modelType: 'Administration',
            modelId: $event->user?->ID,
            modelLabel: $event->user?->Identifiant_email,
        );
    }
}
```

**Step 3: Enregistrer les listeners dans `AppServiceProvider.php`**

Ajouter ces imports en haut du fichier (après `namespace`) :
```php
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogLogout;
```

Ajouter ces deux lignes à l'intérieur de `boot()`, après la ligne existante `App::setLocale('fr')` :
```php
Event::listen(Login::class, LogSuccessfulLogin::class);
Event::listen(Logout::class, LogLogout::class);
```

Résultat `boot()` final :
```php
public function boot(): void
{
    \Illuminate\Support\Facades\App::setLocale('fr');
    Event::listen(Login::class, LogSuccessfulLogin::class);
    Event::listen(Logout::class, LogLogout::class);
}
```

---

### Task 7: Log des connexions échouées dans `AuthenticatedSessionController`

**Pourquoi ici et pas via événement :** L'auth est faite manuellement (comparaison SHA1 directe) sans appel à `Auth::attempt()`. L'événement `Auth\Events\Failed` n'est donc jamais déclenché automatiquement.

**File to modify:** `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Step 1: Ajouter l'import**

Ajouter après les `use` existants :
```php
use App\Services\ActivityLogService;
```

**Step 2: Logger "identifiant introuvable" (ligne ~36)**

Localiser le bloc :
```php
if (!$admin) {
    return back()->withErrors([
        'email' => __('Cet identifiant ou e-mail est introuvable.'),
    ])->onlyInput('email');
}
```

Transformer en :
```php
if (!$admin) {
    ActivityLogService::log(
        action: 'login_failed',
        description: 'Identifiant introuvable',
        userEmail: $credentials['Identifiant_email'],
    );
    return back()->withErrors([
        'email' => __('Cet identifiant ou e-mail est introuvable.'),
    ])->onlyInput('email');
}
```

**Step 3: Logger "compte désactivé" (ligne ~42)**

Localiser :
```php
if (isset($admin->Actived) && !$admin->Actived) {
    return back()->withErrors([
        'actived' => __('Votre compte administrateur est désactivé.'),
    ])->onlyInput('email');
}
```

Transformer en :
```php
if (isset($admin->Actived) && !$admin->Actived) {
    ActivityLogService::log(
        action: 'login_failed',
        description: 'Compte désactivé',
        userEmail: $credentials['Identifiant_email'],
    );
    return back()->withErrors([
        'actived' => __('Votre compte administrateur est désactivé.'),
    ])->onlyInput('email');
}
```

**Step 4: Logger "mot de passe incorrect" (ligne ~48)**

Localiser :
```php
if ($admin->Password_ !== sha1($credentials['password'])) {
    return back()->withErrors([
        'password' => __('Le mot de passe saisi est incorrect.'),
    ])->onlyInput('email');
}
```

Transformer en :
```php
if ($admin->Password_ !== sha1($credentials['password'])) {
    ActivityLogService::log(
        action: 'login_failed',
        description: 'Mot de passe incorrect',
        userEmail: $credentials['Identifiant_email'],
    );
    return back()->withErrors([
        'password' => __('Le mot de passe saisi est incorrect.'),
    ])->onlyInput('email');
}
```

---

### Task 8: Log des changements de profil dans `ProfileController`

**File to modify:** `app/Http/Controllers/ProfileController.php`

**Step 1: Ajouter l'import**
```php
use App\Services\ActivityLogService;
```

**Step 2: Dans `update_Identifiant_email()`**

Ajouter AVANT `return back()->with('success', ...)` :
```php
ActivityLogService::log(
    action: 'update_email',
    modelType: 'Administration',
    modelId: $administrateur->ID,
    modelLabel: $administrateur->Identifiant_email,
);
```

**Step 3: Dans `update_Password()`**

Ajouter AVANT `return back()->with('success', ...)` :
```php
ActivityLogService::log(
    action: 'update_password',
    modelType: 'Administration',
    modelId: $administrateur->ID,
    modelLabel: $administrateur->Identifiant_email,
);
```

**Step 4: Dans `destroy()`**

Ajouter AVANT `$user->delete()` :
```php
ActivityLogService::log(
    action: 'delete_account',
    modelType: 'Administration',
    modelId: $user->ID,
    modelLabel: $user->Identifiant_email,
);
```

---

### Task 9: Log du CRUD Administration et Seller

`Administration` n'a pas le trait `Loggable` — son CRUD est capturé manuellement.

**File: `app/Http/Controllers/AdministrationController.php`**

Ajouter import : `use App\Services\ActivityLogService;`

Dans `store()` — après la création réussie, AVANT `return redirect()` :
```php
ActivityLogService::log(
    action: 'create',
    modelType: 'Administration',
    modelId: $administrateur->ID,
    modelLabel: $administrateur->Identifiant_email,
);
```

Dans `update()` — AVANT `return redirect()` :
```php
ActivityLogService::log(
    action: 'update',
    modelType: 'Administration',
    modelId: $administrateur->ID,
    modelLabel: $administrateur->Identifiant_email,
);
```

Dans `destroy()` — AVANT `return redirect()` :
```php
ActivityLogService::log(
    action: 'delete',
    modelType: 'Administration',
    modelId: $administrateur->ID,
    modelLabel: $administrateur->Identifiant_email ?? null,
);
```

---

**File: `app/Http/Controllers/SellerController.php`**

Ajouter import : `use App\Services\ActivityLogService;`

Même pattern dans `store()`, `update()`, `destroy()`.

Dans `toggleActive()` — AVANT `return redirect()` ou `return response()->json()` :
```php
ActivityLogService::log(
    action: 'toggle_active',
    modelType: 'Administration',
    modelId: $seller->ID,
    modelLabel: $seller->Identifiant_email,
);
```

---

### Task 10: Log des exports dans tous les contrôleurs

Ajouter l'import `use App\Services\ActivityLogService;` et **une seule ligne** dans chaque méthode `exportExcel()` / `exportPdf()`, APRÈS la logique existante (juste avant le `return`).

Pattern :
```php
ActivityLogService::log(action: 'export_excel', modelType: 'NomEntite');
// ou
ActivityLogService::log(action: 'export_pdf', modelType: 'NomEntite');
```

Contrôleurs et `modelType` associé :

| Contrôleur | modelType |
|---|---|
| `EmployeController` | `'Employe'` |
| `EntrepriseController` | `'Entreprise'` |
| `EntrepriseSiegeController` | `'EntrepriseSiege'` |
| `PointageController` | `'Pointage'` |
| `CongeController` | `'Conge'` |
| `JourNonTravailleController` | `'JourNonTravaille'` |
| `AdministrationController` | `'Administration'` |
| `SellerController` | `'Seller'` |
| `ReportController` | `'Report'` |

---

### Task 11: Contrôleur `ActivityLogController` + route

**Files:**
- Create: `app/Http/Controllers/ActivityLogController.php`
- Modify: `routes/web.php`

**Step 1: Créer le contrôleur**

```php
<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\EntrepriseSiege;
use App\Scopes\SiegeScope;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only([
            'date_from', 'date_to', 'action', 'user_email', 'SiegeID', 'model_type'
        ]);

        $query = ActivityLog::query()->orderBy('created_at', 'desc');

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        if (!empty($filters['user_email'])) {
            $query->where('user_email', 'like', '%' . $filters['user_email'] . '%');
        }
        if (!empty($filters['SiegeID'])) {
            $query->where('SiegeID', $filters['SiegeID']);
        }
        if (!empty($filters['model_type'])) {
            $query->where('model_type', $filters['model_type']);
        }

        $logs       = $query->paginate(20)->withQueryString();
        $sieges     = EntrepriseSiege::withoutGlobalScope(SiegeScope::class)->get();
        $actions    = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $modelTypes = ActivityLog::select('model_type')->distinct()
                        ->whereNotNull('model_type')->orderBy('model_type')->pluck('model_type');

        return view('activity-logs.index', compact('logs', 'filters', 'sieges', 'actions', 'modelTypes'));
    }
}
```

**Step 2: Ajouter la route dans `routes/web.php`**

Ajouter l'import en haut du fichier avec les autres contrôleurs :
```php
use App\Http\Controllers\ActivityLogController;
```

Ajouter la route dans le groupe `middleware('auth')`, après les routes de profil (ligne ~56) :
```php
// Logs d'activité (Super Admin uniquement)
Route::get('/activity-logs', [ActivityLogController::class, 'index'])
    ->name('activity-logs.index')
    ->middleware('can:superadmin');
```

---

### Task 12: Vue Blade `activity-logs/index.blade.php`

**Files:**
- Create: `resources/views/activity-logs/index.blade.php`

La vue doit respecter le design Bootstrap existant (identique à `employes/index.blade.php`).

```blade
{{-- resources/views/activity-logs/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Logs d\'activité') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Filtres --}}
                <form action="{{ route('activity-logs.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Date début') }}</label>
                            <input type="date" name="date_from" class="form-control"
                                   value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Date fin') }}</label>
                            <input type="date" name="date_to" class="form-control"
                                   value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Email utilisateur') }}</label>
                            <input type="text" name="user_email" class="form-control"
                                   value="{{ $filters['user_email'] ?? '' }}" placeholder="admin@...">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Action') }}</label>
                            <select name="action" class="form-select">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach ($actions as $act)
                                    <option value="{{ $act }}"
                                        {{ ($filters['action'] ?? '') === $act ? 'selected' : '' }}>
                                        {{ $act }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Entité') }}</label>
                            <select name="model_type" class="form-select">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach ($modelTypes as $mt)
                                    <option value="{{ $mt }}"
                                        {{ ($filters['model_type'] ?? '') === $mt ? 'selected' : '' }}>
                                        {{ $mt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Siège') }}</label>
                            <select name="SiegeID" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach ($sieges as $siege)
                                    <option value="{{ $siege->ID }}"
                                        {{ ($filters['SiegeID'] ?? '') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Filtrer') }}</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Compteur --}}
                <p class="text-muted mb-2">
                    {{ $logs->total() }} {{ __('résultat(s) trouvé(s)') }}
                </p>

                {{-- Tableau --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('Date / Heure') }}</th>
                                <th>{{ __('Utilisateur') }}</th>
                                <th>{{ __('Rôle') }}</th>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Entité') }}</th>
                                <th>{{ __('Détails') }}</th>
                                <th>{{ __('IP') }}</th>
                                <th>{{ __('Siège') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>{{ $log->user_email }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($log->user_role) {
                                                'superadmin'   => 'bg-danger',
                                                'simple_admin' => 'bg-primary',
                                                'seller'       => 'bg-success',
                                                default        => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $log->user_role ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $actionClass = match(true) {
                                                str_starts_with($log->action, 'login_failed') => 'text-danger fw-bold',
                                                str_starts_with($log->action, 'delete')       => 'text-danger',
                                                str_starts_with($log->action, 'create')       => 'text-success',
                                                str_starts_with($log->action, 'update')       => 'text-warning',
                                                str_starts_with($log->action, 'export')       => 'text-info',
                                                default                                        => '',
                                            };
                                        @endphp
                                        <span class="{{ $actionClass }}">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        @if ($log->model_type)
                                            {{ $log->model_type }}
                                            @if ($log->model_id)
                                                <small class="text-muted">#{{ $log->model_id }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->model_label ?? $log->description ?? '—' }}</td>
                                    <td class="text-nowrap">{{ $log->ip_address }}</td>
                                    <td>
                                        @if ($log->SiegeID)
                                            {{ $sieges->firstWhere('ID', $log->SiegeID)?->Nom ?? $log->SiegeID }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        {{ __('Aucun log trouvé.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $logs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
```

---

## Récapitulatif des fichiers

| Action | Fichier |
|--------|---------|
| **Créer** | `database/migrations/2026_03_05_000000_create_activity_logs_table.php` |
| **Créer** | `app/Models/ActivityLog.php` |
| **Créer** | `app/Services/ActivityLogService.php` |
| **Créer** | `app/Traits/Loggable.php` |
| **Créer** | `app/Listeners/LogSuccessfulLogin.php` |
| **Créer** | `app/Listeners/LogLogout.php` |
| **Créer** | `app/Http/Controllers/ActivityLogController.php` |
| **Créer** | `resources/views/activity-logs/index.blade.php` |
| **Modifier** | `app/Models/Employe.php` — +2 lignes |
| **Modifier** | `app/Models/Entreprise.php` — +2 lignes |
| **Modifier** | `app/Models/EntrepriseSiege.php` — +2 lignes |
| **Modifier** | `app/Models/Pointage.php` — +3 lignes |
| **Modifier** | `app/Models/Conge.php` — +3 lignes |
| **Modifier** | `app/Models/JourNonTravaille.php` — +2 lignes |
| **Modifier** | `app/Providers/AppServiceProvider.php` — +5 lignes |
| **Modifier** | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — +12 lignes |
| **Modifier** | `app/Http/Controllers/ProfileController.php` — +9 lignes |
| **Modifier** | `app/Http/Controllers/AdministrationController.php` — +import + 3 blocs |
| **Modifier** | `app/Http/Controllers/SellerController.php` — +import + 4 blocs |
| **Modifier** | `app/Http/Controllers/EmployeController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/EntrepriseController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/EntrepriseSiegeController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/PointageController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/CongeController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/JourNonTravailleController.php` — +import + 2 lignes |
| **Modifier** | `app/Http/Controllers/ReportController.php` — +import + 2 lignes |
| **Modifier** | `routes/web.php` — +import + 3 lignes |
