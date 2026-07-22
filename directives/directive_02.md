# 📱 Directive d'Audit et de Correction : Responsivité UI/UX

> **Projet :** KOSI-POINTAGE
> **Objectif :** Standardiser l'affichage sur mobiles et tablettes, résoudre les chevauchements de textes et fiabiliser les grilles de données.
> **Contexte technique :** Bootstrap 5.3 uniquement + CSS vanilla custom (Tailwind CSS est configuré mais NON actif — aucun `@tailwind` dans `app.css`).

> ⚠️ **Modification v2 :** La directive originale référençait Tailwind CSS comme actif. Ceci est incorrect. Tous les exemples de code ont été mis à jour pour utiliser exclusivement Bootstrap 5 et le CSS vanilla custom déjà en place dans `resources/css/app.css`.

---

## 1. Protocole d'Audit (Vérification)

Avant de modifier le code, il faut isoler les pages problématiques. L'audit doit se concentrer sur les composants critiques de l'application.

1.  **Exposer l'environnement local :** Utilisez `ngrok http 8000` (ou les DevTools du navigateur en mode responsive) pour simuler mobile et tablette.
2.  **Parcours critique à tester :**
    *   Dashboard principal (Graphiques et KPIs).
    *   Liste des pointages (Tableaux de données denses).
    *   Fiche Employé (Modales de modification, photos).
    *   Rapports journaliers (Filtres de dates et exports).

> ✅ **État actuel :** Les tableaux utilisent déjà `table-responsive` et les grilles Bootstrap. L'audit doit se concentrer sur les textes longs (emails, noms) et les boutons d'action sur mobile.

---

## 2. Typologie des Bugs de Texte et Leurs Correctifs

Les textes mal affichés (qui sortent de l'écran, se chevauchent ou sont coupés) sont généralement causés par des conteneurs rigides. Voici les règles de correction à appliquer **avec Bootstrap 5 et CSS vanilla**.

### A. Débordement de textes longs (Emails, Noms, Adresses)
**Problème :** Une adresse ou un email long casse la mise en page car il ne va pas naturellement à la ligne.
**Action :** Tronquer avec les utilitaires Bootstrap 5.

*   **Option 1 : Tronquer proprement (Bootstrap 5)**
    ```html
    <!-- Le texte sera coupé avec "..." : "jean.dupont@entreprise-tre..." -->
    <span class="d-inline-block text-truncate" style="max-width: 200px;"
          title="jean.dupont@entreprise-tres-longue.com">
        jean.dupont@entreprise-tres-longue.com
    </span>
    ```

*   **Option 2 : Forcer le retour à la ligne (CSS vanilla)**
    ```html
    <p style="word-break: break-word; overflow-wrap: break-word; hyphens: auto;">
        Adresse extrêmement longue sans espace...
    </p>
    ```

*   **Option 3 : Classe CSS custom dans `app.css`**
    ```css
    /* À ajouter dans resources/css/app.css */
    .text-break-word {
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }
    ```

### B. Typographie Responsive

**Problème :** Les titres `h1` ou `h2` sont trop gros sur mobile et prennent toute la place.
**Action :** Utiliser les classes `fs-` de Bootstrap 5 avec des media queries CSS.

```html
<!-- Bootstrap 5 : classes de taille de police responsive -->
<h2 class="fw-semibold fs-5 fs-md-4 fs-lg-3 text-dark mb-0">
    Tableau de Bord
</h2>
```

```css
/* Alternative CSS vanilla dans app.css */
@media (max-width: 576px) {
    .page-title {
        font-size: 1.1rem;
    }
}
@media (min-width: 768px) {
    .page-title {
        font-size: 1.35rem;
    }
}
```

---

## 3. Correction des Composants Structurels

### A. Les Tableaux de Données

Les tableaux (ex: Liste des employés ou pointages) détruisent le layout mobile s'ils ne sont pas confinés.

> ✅ **Déjà appliqué dans le projet :** Tous les tableaux existants utilisent `<div class="table-responsive">`. Aucune intervention requise sur ce point.

**Règle d'or (Bootstrap 5) :** Tout `<table>` doit être wrappé dans `.table-responsive`. Masquer les colonnes non essentielles sur mobile avec les classes Bootstrap.

```html
<!-- 1. Le wrapper Bootstrap pour le scroll horizontal -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Nom</th>
                <th>Pointage</th>
                <!-- 2. Masquer les infos secondaires sur mobile -->
                <th class="d-none d-md-table-cell">Dispositif d'Auth</th>
                <th class="d-none d-lg-table-cell">Position GPS</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Lignes de données -->
        </tbody>
    </table>
</div>
```

### B. Grilles de KPI (Dashboard)

Les cartes du dashboard (Statistiques, Compteurs) doivent s'empiler sur mobile et se mettre en grille sur tablette/desktop.

> ✅ **Déjà appliqué dans le projet :** Le dashboard utilise `col-lg-2 col-md-4`. Aucune intervention requise.

**Modèle Bootstrap 5 de référence :**

```html
<!-- 1 colonne sur mobile, 2 sur tablette (sm), 3 sur md, 4 sur desktop -->
<div class="row g-3">
    <!-- Card KPI -->
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <span class="text-muted small">Employés Présents</span>
                <p class="fs-3 fw-bold mb-0">142</p>
            </div>
        </div>
    </div>
    <!-- ... -->
</div>
```

### C. Gestion des Boutons d'Action

Sur mobile, regrouper les multiples boutons d'actions (Éditer, Supprimer, Voir) ou utiliser des icônes seules pour libérer l'espace visuel.

```html
<!-- Bootstrap 5 : Texte visible sur md+, icône seule sur mobile -->
<button class="btn btn-primary">
    <i class="bi bi-pencil"></i>
    <span class="d-none d-md-inline ms-1">Modifier</span>
</button>

<!-- Alternative : Dropdown d'actions sur mobile -->
<div class="dropdown d-md-none">
    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Voir</a></li>
        <li><a class="dropdown-item text-danger" href="#">Supprimer</a></li>
    </ul>
</div>
<div class="d-none d-md-flex gap-2">
    <!-- Boutons complets sur desktop -->
</div>
```

---

## 4. Stack CSS — Règles d'utilisation

Puisque le projet utilise **uniquement Bootstrap 5** (Tailwind n'est pas actif), appliquer ces règles strictes :

1. **Bootstrap 5 pour tout le layout et la responsivité :** `row`, `col-*`, `d-none d-md-block`, `table-responsive`, `text-truncate`.
2. **CSS vanilla dans `app.css` pour les styles custom :** Utiliser des classes nommées (`.page-title`, `.text-break-word`) plutôt que des styles inline.
3. **Ne jamais utiliser de classes Tailwind** dans les vues Blade — elles ne sont pas compilées et n'auront aucun effet.
4. **Ne jamais mélanger les breakpoints :** Utiliser exclusivement les breakpoints Bootstrap (`col-sm-*`, `col-md-*`, `col-lg-*`, `d-md-*`).

### Classes Bootstrap 5 utiles pour la responsivité

| Besoin | Classe Bootstrap 5 |
|--------|--------------------|
| Cacher sur mobile | `d-none d-md-block` |
| Cacher sur desktop | `d-md-none` |
| Colonne cellule de table cachée | `d-none d-md-table-cell` |
| Texte tronqué | `text-truncate` (avec largeur définie) |
| Flex responsive | `d-flex flex-column flex-md-row` |
| Scroll horizontal table | `table-responsive` |
| Texte cassant | CSS : `word-break: break-word` |

---
