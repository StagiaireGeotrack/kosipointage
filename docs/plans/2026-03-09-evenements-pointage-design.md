# Design — Module "Événements d'erreur de pointage"
**Date :** 2026-03-09
**Application :** KOSI-TIME Administration (Laravel)

---

## Contexte

Ajout d'un module de détection et correction des erreurs de pointage pour l'application KOSI-TIME. Ce module permet aux Simple Admins de détecter, visualiser et corriger les anomalies dans les pointages de leur siège avant de pouvoir exporter les rapports.

---

## Rôles & Accès

| Rôle | Accès | Correction | Export bloqué |
|---|---|---|---|
| **Simple Admin** | Erreurs de son siège uniquement | Oui (add/edit/delete + acknowledge) | Oui, si erreurs non résolues |
| **Super Admin** | Vue lecture seule, tous les sièges | Non | Jamais |
| **Vendeur** | Aucun accès | Non | Non concerné |

---

## Types d'erreurs détectées

### Erreurs de données (calcul en temps réel, auto-résolues)

| Code | Nom | Condition de détection |
|---|---|---|
| `doublon_entree` | Doublon d'entrée | 2+ pointages `type_='entry'` pour le même employé le même jour |
| `doublon_sortie` | Doublon de sortie | 2+ pointages `type_='exit'` pour le même employé le même jour |
| `manque_sortie` | Manque de sortie | Entrée sans sortie le même jour (jours passés uniquement) |
| `manque_entree` | Manque d'entrée | Sortie sans entrée le même jour (jours passés uniquement) |

> Ces erreurs disparaissent automatiquement dès que les données sont corrigées (pas de stockage).

### Erreurs contextuelles (stockées dans `pointage_event_exceptions`)

| Code | Nom | Condition de détection |
|---|---|---|
| `pointage_jour_ferie` | Pointage jour férié | Pointage sur une date présente dans `jours_non_travailles` |
| `pointage_weekend` | Pointage weekend | Pointage un samedi ou dimanche |

> Ces erreurs nécessitent un acknowledgment explicite ("Ce pointage est intentionnel") ou une suppression du pointage.

---

## Base de données

### Nouvelle table : `pointage_event_exceptions`

```sql
CREATE TABLE pointage_event_exceptions (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    employee_id     INT NOT NULL,
    date            DATE NOT NULL,
    error_type      ENUM('pointage_jour_ferie', 'pointage_weekend') NOT NULL,
    SiegeID         INT NOT NULL,
    acknowledged_by INT NOT NULL,         -- ID de l'admin qui a acknowledger
    acknowledged_at DATETIME NOT NULL,
    note            TEXT NULL,
    UNIQUE KEY unique_exception (employee_id, date, error_type),
    FOREIGN KEY (employee_id) REFERENCES Employes(ID),
    FOREIGN KEY (SiegeID) REFERENCES Entreprises_sieges(ID),
    FOREIGN KEY (acknowledged_by) REFERENCES administration(ID)
);
```

> Aucune autre table existante n'est modifiée.

---

## Architecture des nouveaux fichiers

```
app/
├── Http/Controllers/EventPointageController.php   (nouveau)
└── Services/EventDetectionService.php             (nouveau)

database/migrations/
└── xxxx_create_pointage_event_exceptions_table.php (nouveau)

resources/views/evenements/
├── index.blade.php          (page principale — liste des erreurs)
└── _correction_panel.blade.php (panel latéral slide-over)
```

> Aucun fichier existant n'est supprimé. Les modifications aux fichiers existants sont minimes et ciblées.

---

## Routes

Ajoutées dans le groupe `siege.access` + `block.sellers` existant dans `routes/web.php` :

```php
Route::middleware('block.sellers')->group(function () {
    Route::get('/evenements', [EventPointageController::class, 'index'])
        ->name('evenements.index');
    // Simple Admin uniquement (check dans le controller)
    Route::post('/evenements/acknowledge', [EventPointageController::class, 'acknowledge'])
        ->name('evenements.acknowledge');
    Route::delete('/evenements/acknowledge/{id}', [EventPointageController::class, 'removeAcknowledge'])
        ->name('evenements.remove-acknowledge');
});
```

---

## Service : `EventDetectionService`

Méthodes principales :

```php
// Retourne toutes les erreurs non résolues pour un siège
public function detect(int $siegeId): Collection

// Compte les erreurs non résolues (pour blocage export)
public function countUnresolved(int $siegeId): int
```

Logique de détection :
- Requêtes SQL directes sur `Pointages` groupées par `employee_id` + `DATE(timestamp_)`
- Jointure avec `jours_non_travailles` pour les jours fériés
- Vérification du jour de semaine (`DAYOFWEEK`) pour les weekends
- Exclusion des exceptions déjà acknowledged (`pointage_event_exceptions`)

---

## Controller : `EventPointageController`

```php
// index()     — liste des erreurs (Simple Admin = son siège, Super Admin = tous les sièges, lecture seule)
// acknowledge() — enregistre un acknowledgment (Simple Admin uniquement)
// removeAcknowledge() — supprime un acknowledgment (Simple Admin uniquement)
```

---

## Blocage des exports (modification minimale de `ReportController`)

Dans `exportExcel()` et `exportPdf()`, ajout **uniquement pour Simple Admin** :

```php
if (auth()->user()->isSimpleAdmin()) {
    $count = app(EventDetectionService::class)->countUnresolved(auth()->user()->SiegeID);
    if ($count > 0) {
        return redirect()->route('evenements.index')
            ->with('error', "Vous avez {$count} événement(s) non résolu(s). Corrigez-les avant d'exporter.");
    }
}
```

> La logique existante de `ReportController` n'est pas modifiée — seule cette vérification est ajoutée en tête des deux méthodes d'export.

---

## Interface utilisateur

### Page `/evenements`

- **Bandeau d'alerte** (rouge) si exports bloqués avec lien vers la page
- **Onglets de filtrage** : Tous / Doublons / Manques / Jours fériés & Weekends
- **Filtre par période** (date_from → date_to)
- **Filtre par employé**
- Chaque erreur affiche : nom employé, matricule, date, type d'erreur, pointages concernés

### Actions par type d'erreur (Simple Admin uniquement)

| Type | Actions disponibles |
|---|---|
| Doublon entrée/sortie | Voir chaque pointage + Supprimer le doublon |
| Manque de sortie | Ajouter une sortie (formulaire pré-rempli) |
| Manque d'entrée | Ajouter une entrée (formulaire pré-rempli) |
| Jour férié / Weekend | Acknowledger (avec note optionnelle) OU Supprimer le pointage |

### Panel de correction (slide-over)

- S'ouvre sans quitter la page
- Affiche les pointages concernés avec leurs détails (timestamp, photo, méthode auth, GPS)
- Formulaires inline pour add/edit/delete
- Après chaque action : rafraîchissement automatique de la liste via redirect

### Vue Super Admin

- Même page `/evenements` mais sans boutons d'action
- Filtre supplémentaire par siège
- Badge "Lecture seule" visible

---

## Contraintes respectées

- Aucune logique métier existante n'est modifiée
- Le `SiegeScope` global continue de s'appliquer automatiquement
- Les routes, middleware et contrôleurs existants sont inchangés
- Seuls 2 fichiers existants sont légèrement modifiés : `ReportController.php` (2 ajouts) et `routes/web.php` (ajout de routes)
