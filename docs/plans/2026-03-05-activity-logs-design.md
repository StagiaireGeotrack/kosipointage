# Design — Système de Logs d'Activité

**Date :** 2026-03-05
**Projet :** kosi-pointage
**Statut :** Approuvé

---

## Objectif

Ajouter un système de logs d'activité stockés en base de données couvrant toutes les actions de l'application web : connexions (succès et échecs), CRUD sur toutes les entités, exports Excel/PDF, et changements de profil/mot de passe.

Les logs sont consultables uniquement par les Super Admins via une page dédiée avec filtres.

**Contrainte principale :** Ne pas modifier les logiques existantes qui fonctionnent.

---

## Table `activity_logs`

```sql
CREATE TABLE activity_logs (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       BIGINT UNSIGNED NULL,
    user_email    VARCHAR(255) NOT NULL,
    user_role     VARCHAR(50) NULL,          -- superadmin | simple_admin | seller | unknown
    action        VARCHAR(50) NOT NULL,      -- login_success | login_failed | logout |
                                             -- create | update | delete |
                                             -- export_excel | export_pdf |
                                             -- update_email | update_password | delete_account
    model_type    VARCHAR(100) NULL,         -- Employe | Entreprise | Pointage | ...
    model_id      BIGINT UNSIGNED NULL,
    model_label   VARCHAR(255) NULL,         -- valeur lisible ex: "Jean Dupont"
    description   TEXT NULL,                 -- détails supplémentaires optionnels
    ip_address    VARCHAR(45) NOT NULL,      -- IPv4 ou IPv6
    user_agent    VARCHAR(500) NULL,
    SiegeID       INT UNSIGNED NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_user_id (user_id),
    INDEX idx_SiegeID (SiegeID),
    INDEX idx_action (action)
);
```

Pas de `updated_at` — les logs sont immuables.

---

## Architecture & Composants

### 1. `ActivityLog` Model (`app/Models/ActivityLog.php`)

- `$fillable` complet sur tous les champs
- Pas de `SiegeScope` (les Super Admins voient tout)
- `$timestamps = false` (on gère `created_at` manuellement)

### 2. `ActivityLogService` (`app/Services/ActivityLogService.php`)

Service central d'écriture des logs.

```php
ActivityLogService::log(
    action: string,
    modelType: string|null = null,
    modelId: int|null = null,
    modelLabel: string|null = null,
    description: string|null = null,
    userEmail: string|null = null,   // pour login_failed (pas d'auth)
)
```

- Récupère automatiquement depuis la requête : `user_id`, `user_email`, `user_role`, `ip_address`, `user_agent`, `SiegeID`
- Enveloppé dans `try/catch` silencieux — jamais bloquant
- En cas d'erreur : log fichier Laravel uniquement

### 3. Trait `Loggable` (`app/Traits/Loggable.php`)

Trait à ajouter sur les modèles Eloquent concernés. Utilise `static::created()`, `static::updated()`, `static::deleted()`.

Propriété configurable par modèle :
```php
protected string $logLabelField = 'Nom'; // champ utilisé comme model_label
```

Modèles concernés :
- `Employe` — label: `Nom`
- `Entreprise` — label: `Nom`
- `EntrepriseSiege` — label: `Nom`
- `Pointage` — label: ID (pas de nom direct)
- `Conge` — label: employee + dates
- `JourNonTravaille` — label: `Nom`
- `Administration` — label: `Identifiant_email`

### 4. Event Listeners (connexions)

Enregistrés dans `EventServiceProvider` — **aucune modification des contrôleurs Auth**.

| Événement | Listener | Action |
|-----------|----------|--------|
| `Auth\Events\Login` | `LogSuccessfulLogin` | `login_success` |
| `Auth\Events\Failed` | `LogFailedLogin` | `login_failed` |
| `Auth\Events\Logout` | `LogLogout` | `logout` |

### 5. Exports — Ajout d'une ligne par méthode

Dans chaque méthode `exportExcel()` / `exportPdf()` des contrôleurs, ajout d'une seule ligne après la logique existante :

```php
ActivityLogService::log(action: 'export_excel', modelType: 'Employe');
```

Contrôleurs concernés : `EmployeController`, `EntrepriseController`, `EntrepriseSiegeController`, `PointageController`, `CongeController`, `JourNonTravailleController`, `AdministrationController`, `SellerController`, `ReportController`.

### 6. Profil — Ajout d'une ligne par méthode

Dans `ProfileController`, ajout d'une ligne après chaque opération réussie :
- `update_Identifiant_email()` → `update_email`
- `update_Password()` → `update_password`
- `destroy()` → `delete_account`

### 7. Page de consultation (`/activity-logs`)

- Route `GET /activity-logs` — middleware `auth` + gate `superadmin`
- `ActivityLogController::index()` — pagination 20 par page
- Filtres : `date_from`, `date_to`, `action`, `user_email`, `SiegeID`, `model_type`
- Vue Blade cohérente avec le design existant
- Lecture seule — pas de suppression via UI

---

## Flux de données

### Connexion échouée
```
POST /login → Auth\Events\Failed → LogFailedLoginListener → ActivityLogService::log(login_failed)
```

### CRUD Employé (update)
```
PUT /employes/{id} → EmployeController::update() [inchangé] → Eloquent:updated → Loggable trait → ActivityLogService::log(update, Employe, 42)
```

### Export Excel
```
GET /employes-export/excel → EmployeController::exportExcel() → [logique existante] + ActivityLogService::log(export_excel, Employe)
```

---

## Gestion des erreurs

- Le service est **non-bloquant** : `try/catch` silencieux sur tout
- Erreurs loggées dans `storage/logs/laravel.log` uniquement
- L'application ne plante jamais à cause d'un log manquant

---

## Performance

- Index sur `created_at`, `user_id`, `SiegeID`, `action`
- Écritures synchrones (INSERT simple — très rapide)
- Pas de queue pour l'instant

---

## Sécurité

- Page protégée par gate `superadmin` existant
- Logs immuables depuis l'UI
- `user_agent` tronqué à 500 caractères
