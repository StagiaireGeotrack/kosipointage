# 🛠️ Directive de Refonte UI : Migration vers Sidebar Gauche

> **Projet :** KOSI-POINTAGE
> **Objectif :** Déplacer la navigation principale d'une disposition horizontale (Top-Nav) vers une barre latérale verticale (Sidebar) à gauche.
> **Contrainte stricte :** AUCUNE modification des contrôleurs, routes, middlewares ou logique d'authentification. Uniquement du refactoring Blade/CSS.

---

## 1. Vue d'Ensemble de la Nouvelle Structure

Le layout principal va passer d'une structure empilée (Header > Main) à une structure en grille/flex (Sidebar + Colonne de contenu [Header mobile/profil + Main]). Nous utiliserons **Alpine.js** (déjà présent dans le projet) pour gérer l'ouverture/fermeture de la sidebar sur mobile, sans ajouter de dépendance JS.

### État Actuel (à analyser avant de modifier)

| Fichier | Rôle actuel |
|---------|-------------|
| `resources/views/layouts/app.blade.php` | Layout principal — contient `padding-top: 90px` (compensant la navbar fixe), flash messages, scripts JS inline (dates UTC, scroll-to-top) |
| `resources/views/layouts/navigation.blade.php` | Navbar Bootstrap fixe horizontale — contient TOUTE la logique de liens avec visibilité par rôle |
| `resources/views/components/` | 14 composants Blade (aucun `sidebar.blade.php` ni `topbar.blade.php` n'existe encore) |

---

## 2. Étapes d'Implémentation

### Étape 1 : Création du composant Sidebar

**Fichier à créer :** `resources/views/components/sidebar.blade.php`

Ce fichier reprend **exactement** la logique de visibilité par rôle déjà présente dans `navigation.blade.php`. Couleur de marque : `#3F52A4`.

```html
{{-- resources/views/components/sidebar.blade.php --}}
{{-- Alpine.js gère la visibilité via 'sidebarOpen' du layout parent --}}
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 text-white transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col"
    style="background: linear-gradient(180deg, #3F52A4 0%, #2d3c7a 100%);"
>
    {{-- Logo / Branding --}}
    <a href="@if(auth()->user()->isTrueSuperAdmin()) {{ route('dashboard') }}
             @elseif(auth()->user()->isSeller()) {{ route('dashboard.seller') }}
             @else {{ route('dashboard.simple-admin') }}
             @endif"
       class="flex items-center justify-center gap-2 h-16 border-b border-white border-opacity-20 no-underline">
        <img src="{{ asset('images/logo.png') }}" alt="KOSI-TIME" height="40">
        <span class="text-lg font-bold text-white">KOSI-TIME</span>
    </a>

    {{-- Liens de navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1">

        {{-- Tableau de bord (adapté au rôle) --}}
        <a href="@if(auth()->user()->isTrueSuperAdmin()) {{ route('dashboard') }}
                 @elseif(auth()->user()->isSeller()) {{ route('dashboard.seller') }}
                 @else {{ route('dashboard.simple-admin') }}
                 @endif"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('dashboard*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-speedometer2"></i> {{ __('Tableau de bord') }}
        </a>

        {{-- Siège : Visible par tous --}}
        <a href="{{ route('sieges.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('sieges.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-building"></i> {{ __('Siège') }}
        </a>

        {{-- Administrateur & Revendeur : Super Admin uniquement --}}
        @can('superadmin')
        <a href="{{ route('administrateurs.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('administrateurs.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-person-gear"></i> {{ __('Administrateur') }}
        </a>

        <a href="{{ route('sellers.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('sellers.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-shop-window"></i> {{ __('Revendeur') }}
        </a>
        @endcan

        {{-- Entreprises : Visible par tous --}}
        <a href="{{ route('entreprises.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('entreprises.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-shop"></i> {{ __('Site ou établissement') }}
        </a>

        {{-- Employés : Visible par tous --}}
        <a href="{{ route('employes.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('employes.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-people"></i> {{ __('Employé') }}
        </a>

        {{-- Sections INTERDITES aux vendeurs --}}
        @if(!auth()->user()->isSeller())

        <a href="{{ route('pointages.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('pointages.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-clock-history"></i> {{ __('Pointage') }}
        </a>

        <a href="{{ route('reports.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('reports.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-file-earmark-text"></i> {{ __('Rapport') }}
        </a>

        <a href="{{ route('conges.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('conges.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-calendar-x"></i> {{ __('Congé') }}
        </a>

        <a href="{{ route('jours-non-travailles.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('jours-non-travailles.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-calendar-event"></i> {{ __('Jour férié') }}
        </a>

        @endif

        {{-- Événements : Super Admin et Simple Admin uniquement --}}
        @if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
        <a href="{{ route('evenements.index') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-white text-opacity-90 hover:bg-white hover:bg-opacity-20 transition-all
                  {{ request()->routeIs('evenements.*') ? 'bg-white bg-opacity-20 font-semibold' : '' }}">
            <i class="bi bi-exclamation-triangle"></i> {{ __('Événements') }}
            {{-- Badge compteur anomalies (Simple Admin uniquement) --}}
            @if(auth()->user()->isSimpleAdmin())
                @php
                    try {
                        $navErrCount = app(\App\Services\EventDetectionService::class)
                            ->countUnresolved(auth()->user()->SiegeID);
                    } catch (\Throwable $e) {
                        $navErrCount = 0;
                    }
                @endphp
                @if($navErrCount > 0)
                    <span class="ms-auto badge bg-danger rounded-pill" style="font-size:0.65rem;">
                        {{ $navErrCount }}
                    </span>
                @endif
            @endif
        </a>
        @endif

    </nav>

    {{-- Pied de sidebar : email + déconnexion --}}
    <div class="border-t border-white border-opacity-20 p-3">
        <div class="text-xs text-white text-opacity-60 mb-2 truncate px-1">
            {{ Auth::user()->Identifiant_email }}
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 rounded-lg text-white text-opacity-80 hover:bg-white hover:bg-opacity-20 transition-all text-sm">
                <i class="bi bi-box-arrow-right"></i> {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</aside>

{{-- Overlay sombre pour mobile --}}
<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
    style="display: none;"
></div>
```

---

### Étape 2 : Création du composant Topbar

**Fichier à créer :** `resources/views/components/topbar.blade.php`

Ne conserver que : hamburger mobile, sélecteur de langue (si présent), menu profil/déconnexion, logs d'activité.

```html
{{-- resources/views/components/topbar.blade.php --}}
<header class="flex items-center justify-between h-14 px-4 bg-white border-b shadow-sm flex-shrink-0">

    {{-- Bouton Hamburger (mobile uniquement) --}}
    <button @click="sidebarOpen = !sidebarOpen"
            class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
        <i class="bi bi-list text-2xl"></i>
    </button>

    {{-- Espace gauche sur desktop --}}
    <div class="hidden md:block"></div>

    {{-- Zone droite : badge rôle + menu profil --}}
    <div class="flex items-center gap-3">

        {{-- Badge rôle --}}
        @if(Auth::user()->isTrueSuperAdmin())
            <span class="hidden sm:inline text-xs font-semibold px-2 py-1 rounded-full text-white"
                  style="background: linear-gradient(135deg, #3F52A4, #5a6fb8);">
                {{ __('Super Administrateur') }}
            </span>
        @elseif(Auth::user()->isSeller())
            <span class="hidden sm:inline text-xs font-semibold px-2 py-1 rounded-full text-white"
                  style="background: linear-gradient(135deg, #10b981, #059669);">
                {{ __('Revendeur') }}
            </span>
        @else
            <span class="hidden sm:inline text-xs font-semibold px-2 py-1 rounded-full text-white"
                  style="background: linear-gradient(135deg, #6b7280, #4b5563);">
                {{ __('Simple Administrateur') }}
            </span>
        @endif

        {{-- Menu dropdown profil --}}
        <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-1"
                    style="border: 2px solid #3F52A4; border-radius: 8px;"
                    type="button" id="topbarUserDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-sm-inline small">{{ Auth::user()->Identifiant_email }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="topbarUserDropdown"
                style="border: 2px solid #3F52A4; border-radius: 10px;">
                @can('superadmin')
                <li>
                    <a class="dropdown-item" href="{{ route('activity-logs.index') }}">
                        <i class="bi bi-journal-text me-2"></i> {{ __("Logs d'activité") }}
                    </a>
                </li>
                @endcan
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i> {{ __('Identifiant') }}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> {{ __('Déconnexion') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
```

---

### Étape 3 : Refactorisation du Layout Principal

**Fichier à modifier :** `resources/views/layouts/app.blade.php`

Points critiques à ne **pas oublier** (déjà présents dans l'actuel) :
- ⚠️ Supprimer `padding-top: 90px` (n'est plus nécessaire sans navbar fixe)
- ✅ Conserver le bloc JS de conversion des dates UTC (`.local-datetime`)
- ✅ Conserver le bouton **Scroll to Top** (`#scrollToTop`)
- ✅ Conserver `@stack('styles')` et `@stack('scripts')`
- ✅ Conserver le `meta name="csrf-token"`
- ✅ Conserver le Favicon

```html
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KOSI Pointage') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    {{-- Styles de page spécifiques --}}
    @stack('styles')

    {{-- Vite (CSS + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Scroll to top button */
        #scrollToTop {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3F52A4;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(63,82,164,0.3);
        }
        #scrollToTop.show { display: flex; align-items: center; justify-content: center; }
        #scrollToTop svg { width: 20px; height: 20px; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">

    {{-- Wrapper principal Flexbox avec état Alpine pour le mobile --}}
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- 1. Sidebar Gauche --}}
        @include('components.sidebar')

        {{-- 2. Colonne Droite (Topbar + Contenu) --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Contenu Principal --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6">

                {{-- En-tête de page (optionnel via slot $header) --}}
                @if(isset($header))
                    <div class="mb-4">{{ $header }}</div>
                @endif

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! session('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! session('error') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                {{-- Vue --}}
                {{ $slot }}

            </main>
        </div>
    </div>

    {{-- Scroll to Top Button --}}
    <button id="scrollToTop" type="button" title="Retour en haut">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        // Scroll to top
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 50) scrollToTopBtn.classList.add('show');
            else scrollToTopBtn.classList.remove('show');
        }, { passive: true });
        scrollToTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Conversion des dates UTC en heure locale
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.local-datetime').forEach(function (element) {
                const utcDate = element.getAttribute('data-utc');
                const format = element.getAttribute('data-format') || 'full';
                if (!utcDate) return;
                const date = new Date(utcDate);
                let options;
                if (format === 'short') {
                    options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                } else if (format === 'date-only') {
                    options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                } else {
                    options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                }
                const formatted = date.toLocaleDateString('fr-FR', options);
                if (!format || format === 'full') {
                    const parts = formatted.split(' à ');
                    element.textContent = parts.length === 2
                        ? parts[0].charAt(0).toUpperCase() + parts[0].slice(1) + ' - ' + parts[1]
                        : formatted.charAt(0).toUpperCase() + formatted.slice(1);
                } else {
                    element.textContent = formatted.charAt(0).toUpperCase() + formatted.slice(1);
                }
            });
        });
    </script>

    {{-- Scripts de pages spécifiques --}}
    @stack('scripts')

</body>
</html>
```

---

### Étape 4 : Mise à jour du fichier `navigation.blade.php`

**Fichier à modifier :** `resources/views/layouts/navigation.blade.php`

Ce fichier n'est **plus utilisé** après la migration. Deux options :
- **Option A (recommandée) :** Vider le fichier et ajouter un commentaire d'archivage.
- **Option B :** Supprimer le fichier (après vérification qu'aucune vue ne l'inclut encore).

```blade
{{-- resources/views/layouts/navigation.blade.php --}}
{{-- ⚠️ ARCHIVÉ — Navigation migrée vers resources/views/components/sidebar.blade.php --}}
{{-- Ce fichier peut être supprimé après vérification complète de la migration. --}}
```

---

## 3. Points Techniques Critiques

| Point | Détail |
|-------|--------|
| ⚠️ `padding-top: 90px` | **À supprimer** de `app.blade.php` — était compensé par la navbar Bootstrap fixe |
| ✅ Scroll to Top | Conserver le bouton `#scrollToTop` et son script |
| ✅ JS dates UTC | Conserver le bloc `document.querySelectorAll('.local-datetime')` |
| ✅ `@stack('styles')` | Doit rester **avant** `@vite(...)` |
| ✅ `@stack('scripts')` | Doit rester **à la fin** du `<body>` |
| ✅ Compteur Événements | Badge `bg-danger` dans la sidebar (Simple Admin uniquement) — **à conserver** |
| ✅ Logs d'activité | Lien dans la topbar sous `@can('superadmin')` |
| ⚠️ Bootstrap dropdown | Le menu profil utilise `data-bs-toggle="dropdown"` — Bootstrap JS doit rester chargé |
| ⚠️ Tailwind + Bootstrap | Les deux coexistent — utiliser les classes Tailwind pour la sidebar et Bootstrap pour les dropdowns |

---

## 4. Liste de Contrôle (Checklist)

### Création des fichiers
- [ ] Créer `resources/views/components/sidebar.blade.php` avec la logique de rôle complète
- [ ] Créer `resources/views/components/topbar.blade.php` avec le menu profil et hamburger
- [ ] Archiver (vider) `resources/views/layouts/navigation.blade.php`

### Modification de `app.blade.php`
- [ ] Remplacer `@include('layouts.navigation')` par la structure `<div x-data ...>`
- [ ] Supprimer le CSS `body { padding-top: 90px; }`
- [ ] Vérifier que `@stack('styles')` est avant `@vite(...)`
- [ ] Vérifier que `@stack('scripts')` est en fin de `<body>`
- [ ] Conserver le bouton `#scrollToTop` et son script
- [ ] Conserver le script de conversion des dates UTC

### Validation fonctionnelle
- [ ] La sidebar est visible sur desktop, cachée sur mobile
- [ ] Le bouton hamburger affiche/cache la sidebar sur mobile
- [ ] L'overlay sombre ferme la sidebar au clic
- [ ] Le lien actif est mis en évidence (`bg-white bg-opacity-20`)
- [ ] Le badge rouge du compteur d'anomalies s'affiche pour les Simple Admin
- [ ] `@can('superadmin')` cache correctement les liens Administrateur, Revendeur, Logs
- [ ] `@if(!auth()->user()->isSeller())` cache Pointage, Rapport, Congé, JNT aux vendeurs
- [ ] Le menu profil (dropdown Bootstrap) fonctionne dans la topbar
- [ ] La déconnexion fonctionne depuis la sidebar ET depuis la topbar
- [ ] Confirmer qu'**aucune modification** n'a été apportée aux Controllers, routes ou SiegeScope