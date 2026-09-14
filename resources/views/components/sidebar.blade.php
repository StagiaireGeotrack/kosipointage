{{-- resources/views/components/sidebar.blade.php --}}
<div id="sidebar-overlay" onclick="closeSidebar()" style="display:none; position:fixed; inset:0; z-index:1040; background:rgba(0,0,0,0.5);"></div>

@php

    $user = auth()->user();
    $isAdmin = $user instanceof \App\Models\Administration;
    $isEmploye = $user instanceof \App\Models\Employe;

    // ============================================================
    // NOUVELLE SÉMANTIQUE DES RÔLES
    // ============================================================
    // Super Admin     : IsSuperAdmin=1, IsSeller=0, IsManager=0, IsSupervisor=0, IsMaster=0
    // Revendeur       : IsSuperAdmin=0, IsSeller=1, IsManager=0, IsSupervisor=0, IsMaster=0
    // Simple Admin    : IsSuperAdmin=0, IsSeller=0, IsManager=1, IsSupervisor=0, IsMaster=0
    // Master          : IsSuperAdmin=0, IsSeller=0, IsManager=1, IsSupervisor=0, IsMaster=1
    // Supervisor      : IsSuperAdmin=0, IsSeller=0, IsManager=0, IsSupervisor=1, IsMaster=0
    // ============================================================

    $isSuperAdmin = $isAdmin && $user->IsSuperAdmin == 1 && $user->IsSeller == 0;

    $isSeller = $isAdmin && $user->IsSeller == 1;

    $isSimpleAdminPure = $isAdmin
        && $user->IsSuperAdmin == 0
        && $user->IsSeller == 0
        && $user->IsManager == 1
        && $user->IsSupervisor == 0
        && ($user->IsMaster ?? 0) == 0;

    $isMaster = $isAdmin
        && $user->IsSuperAdmin == 0
        && $user->IsSeller == 0
        && $user->IsManager == 1
        && $user->IsSupervisor == 0
        && ($user->IsMaster ?? 0) == 1;

    $isSupervisor = $isAdmin
        && $user->IsSuperAdmin == 0
        && $user->IsSeller == 0
        && $user->IsManager == 0
        && $user->IsSupervisor == 1;

    // Simple Admin au sens large = Simple Admin PUR OU Master (mêmes droits)
    $isSimpleAdmin = $isSimpleAdminPure || $isMaster;

    // Variable conservée pour la logique "Gestion des Managers" existante
    $isManagerSimpleAdmin = $isSimpleAdmin;
    $isManagerSuperAdmin = $isAdmin && $user->IsSuperAdmin == 1 && ($user->isManager ?? false);
    $isManagerSeller = $isAdmin && $user->IsSeller == 1 && ($user->isManager ?? false);

    // Pour les employés
    $isEmployeMode = $isEmploye;

    // Compteur de notifications non lues
    $unreadNotifications = 0;
    if ($isAdmin) {
        try {
            $unreadNotifications = \DB::table('notifications')
                ->where('user_id', $user->ID)
                ->where('is_read', 0)
                ->count();
        } catch (\Throwable $e) {
            $unreadNotifications = 0;
        }
    }

    if ($isEmploye && $user->ID) {
        try {
            $unreadNotifications = \DB::table('notifications')
                ->where('user_id', $user->ID)
                ->where('is_read', 0)
                ->count();
        } catch (\Throwable $e) {
            $unreadNotifications = 0;
        }
    }

    // Vérifier si des plannings sont en attente (admin non supervisor, non seller)
    $hasPendingPlanning = false;
    if ($isAdmin && !$isSupervisor && !$isSeller) {
        try {
            $hasPendingPlanning = \App\Models\Planning::where('statut', 'genere')->count() > 0;
        } catch (\Throwable $e) {
            $hasPendingPlanning = false;
        }
    }
@endphp

<aside id="app-sidebar" class="app-sidebar collapsed">
    {{-- Logo / Branding --}}
    <a href="@if($isSuperAdmin) {{ route('dashboard') }}
             @elseif($isSeller) {{ route('dashboard.seller') }}
             @elseif($isSupervisor) {{ route('dashboard.simple-admin') }}
             @elseif($isEmploye) {{ route('employe.dashboard') }}
             @else {{ route('dashboard.simple-admin') }}
             @endif"
       class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="KOSI-TIME" height="80">
    </a>

    {{-- Liens de navigation --}}
    <nav class="sidebar-nav">

        {{-- Tableau de bord --}}
        <a href="@if($isSuperAdmin) {{ route('dashboard') }}
                 @elseif($isSeller) {{ route('dashboard.seller') }}
                 @elseif($isSupervisor) {{ route('dashboard.simple-admin') }}
                 @elseif($isEmploye) {{ route('employe.dashboard') }}
                 @else {{ route('dashboard.simple-admin') }}
                 @endif"
           class="sidebar-link {{ request()->routeIs('dashboard*') || request()->routeIs('employe.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>{{ __('Tableau de bord') }}</span>
        </a>

        {{-- Siège : masqué pour Supervisor --}}
        @if(!$isSupervisor)
        <a href="{{ route('sieges.index') }}"
           class="sidebar-link {{ request()->routeIs('sieges.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>{{ __('Siège') }}</span>
        </a>
        @endif

        {{-- ==================== ESPACE EMPLOYÉ ==================== --}}
        @if($isEmploye)
            <a href="{{ route('employe.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('employe.dashboard') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>{{ __('Mes congés') }}</span>
            </a>

            <a href="{{ route('employe.leave-requests.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.leave-requests.*') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i>
                <span>{{ __('Mes demandes') }}</span>
            </a>

            <a href="{{ route('employe.leave-requests.create') }}"
               class="sidebar-link {{ request()->routeIs('employe.leave-requests.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>{{ __('Nouvelle demande') }}</span>
            </a>

            <a href="{{ route('employe.notifications.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i>
                <span>{{ __('Notifications') }}</span>
                @if($unreadNotifications > 0)
                    <span class="badge bg-danger rounded-pill ms-auto" style="font-size:0.65rem;">
                        {{ $unreadNotifications }}
                    </span>
                @endif
            </a>

            <a href="{{ route('employe.leave-calendar.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.leave-calendar.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span>{{ __('Calendrier des congés') }}</span>
            </a>

            <a href="{{ route('employe.planning.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.planning.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range"></i>
                <span>{{ __('Mon planning') }}</span>
            </a>
        @endif

        {{-- ==================== ADMINISTRATEUR ==================== --}}
        {{-- Le Supervisor utilise maintenant les mêmes routes que le Simple Admin --}}
        @if($isAdmin && !$isEmploye)

            {{-- Administrateur & Revendeur : Super Admin uniquement --}}
            @if($isSuperAdmin)
            <a href="{{ route('administrateurs.index') }}"
               class="sidebar-link {{ request()->routeIs('administrateurs.*') && !in_array(request('role'), ['manager_super_admin', 'manager_simple_admin']) ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>{{ __('Administrateur') }}</span>
            </a>

            <a href="{{ route('sellers.index') }}"
               class="sidebar-link {{ request()->routeIs('sellers.*') && request('role') !== 'manager_seller' ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                <span>{{ __('Revendeur') }}</span>
            </a>
            @endif

            {{-- ============================================================ --}}
            {{-- GESTION DU MASTER : uniquement Simple Admin PUR --}}
            {{-- ============================================================ --}}
            @if($isSimpleAdminPure)
            <a href="{{ route('master.show') }}"
               class="sidebar-link {{ request()->routeIs('master.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>{{ __('Master (Adjoint)') }}</span>
            </a>
            @endif

            {{-- ============================================================ --}}
            {{-- GESTION DES RESPONSABLES DE SERVICE : Simple Admin PUR + Master --}}
            {{-- ============================================================ --}}
            @if($isSimpleAdmin)
            <a href="{{ route('supervisors.index') }}"
               class="sidebar-link {{ request()->routeIs('supervisors.*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard"></i>
                <span>{{ __('Responsables de service') }}</span>
            </a>
            @endif

            {{-- ============================================================ --}}
            {{-- GESTION DES MANAGERS : Super Admin voit tout, Revendeur voit --}}
            {{-- uniquement le sous-menu "Admin Simple" --}}
            {{-- ============================================================ --}}
            @if($isSuperAdmin || $isSeller)
            @php
                $isManagerActive = in_array(request('role'), ['manager_super_admin', 'manager_simple_admin', 'manager_seller']);
            @endphp
            <style>
                .sidebar-link[aria-expanded="true"] .chevron-icon { transform: rotate(180deg); }
            </style>
            <a href="#managerSubmenu" data-bs-toggle="collapse" class="sidebar-link {{ $isManagerActive ? '' : 'collapsed' }}" aria-expanded="{{ $isManagerActive ? 'true' : 'false' }}">
                <i class="bi bi-diagram-2"></i>
                <span>{{ __('Gestion des Managers') }}</span>
                <i class="bi bi-chevron-down ms-auto chevron-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <div class="collapse {{ $isManagerActive ? 'show' : '' }} mt-1" id="managerSubmenu">

                {{-- Sous-menu "Super Admin" : UNIQUEMENT pour Super Admin --}}
                @if($isSuperAdmin)
                <a href="{{ route('administrateurs.index', ['role' => 'manager_super_admin']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_super_admin' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-check-fill"></i>
                    <span>{{ __('Super Admin') }}</span>
                </a>
                @endif

                {{-- Sous-menu "Admin Simple" : Super Admin ET Revendeur --}}
                @if($isSuperAdmin || $isSeller)
                <a href="{{ route('administrateurs.index', ['role' => 'manager_simple_admin']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_simple_admin' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-check"></i>
                    <span>{{ __('Admin Simple') }}</span>
                </a>
                @endif

                {{-- Sous-menu "Revendeur" : UNIQUEMENT pour Super Admin --}}
                @if($isSuperAdmin)
                <a href="{{ route('sellers.index', ['role' => 'manager_seller']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_seller' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-rolodex"></i>
                    <span>{{ __('Revendeur') }}</span>
                </a>
                @endif

            </div>
            @endif

            {{-- Entreprises : masqué pour Supervisor --}}
            @if(!$isSupervisor)
            <a href="{{ route('entreprises.index') }}"
               class="sidebar-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>{{ __('Site ou établissement') }}</span>
            </a>
            @endif

            {{-- Employés : VISIBLE pour tous les admins (dont Supervisor) --}}
            <a href="{{ route('employes.index') }}"
               class="sidebar-link {{ request()->routeIs('employes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>{{ __('Employé') }}</span>
            </a>

            {{-- ========================================== --}}
            {{-- PLANNING --}}
            {{-- ========================================== --}}
            @if(!$isSeller)
            @php
                $isPlanningOpen = request()->routeIs('planning.*')
                               || request()->routeIs('planning.horaires-types.*');
            @endphp
            <details class="sidebar-group" {{ $isPlanningOpen ? 'open' : '' }}>
                <summary class="sidebar-link sidebar-group-title {{ $isPlanningOpen ? 'active' : '' }}">
                    <i class="bi bi-calendar-range"></i>
                    <span>{{ __('Planning') }}</span>
                    @if($hasPendingPlanning)
                        <span class="badge bg-warning text-dark rounded-pill ms-auto me-2" style="font-size:0.65rem;">
                            <i class="bi bi-clock"></i>
                        </span>
                    @endif
                    <i class="bi bi-chevron-down small chevron"></i>
                </summary>

                @if(Route::has('planning.index'))
                <a href="{{ route('planning.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('planning.index') || request()->routeIs('planning.calendar') || request()->routeIs('planning.show') ? 'active' : '' }}">
                    <i class="bi bi-eye"></i>
                    <span>{{ __('Voir le planning') }}</span>
                </a>
                @endif

                {{-- Créer un planning : masqué pour Supervisor --}}
                @if(!$isSupervisor && Route::has('planning.create'))
                <a href="{{ route('planning.create') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('planning.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>{{ __('Créer un planning') }}</span>
                </a>
                @endif

                {{-- Horaires types : masqué pour Supervisor --}}
                @if(!$isSupervisor && Route::has('planning.horaires-types.index'))
                <a href="{{ route('planning.horaires-types.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('planning.horaires-types.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>{{ __('Horaires types') }}</span>
                </a>
                @endif
            </details>
            @endif

            {{-- ==================== ORGANISATION RH ==================== --}}
            {{-- Masqué pour Supervisor --}}
            @if(!$isSeller && !$isSupervisor)
            @php
                $isOrgOpen = request()->routeIs('admin.departments.*')
                          || request()->routeIs('admin.job-titles.*')
                          || request()->routeIs('admin.hierarchy-levels.*');
            @endphp
            <details class="sidebar-group" {{ $isOrgOpen ? 'open' : '' }}>
                <summary class="sidebar-link sidebar-group-title {{ $isOrgOpen ? 'active' : '' }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>{{ __('Organisation') }}</span>
                    <i class="bi bi-chevron-down small chevron"></i>
                </summary>

                @if(Route::has('admin.departments.index'))
                <a href="{{ route('admin.departments.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="bi bi-building-check"></i>
                    <span>{{ __('Services') }}</span>
                </a>
                @endif

                @if(Route::has('admin.job-titles.index'))
                <a href="{{ route('admin.job-titles.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('admin.job-titles.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i>
                    <span>{{ __('Postes') }}</span>
                </a>
                @endif

                @if(Route::has('admin.hierarchy-levels.index'))
                <a href="{{ route('admin.hierarchy-levels.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('admin.hierarchy-levels.*') ? 'active' : '' }}">
                    <i class="bi bi-layers"></i>
                    <span>{{ __('Niveaux') }}</span>
                </a>
                @endif
            </details>
            @endif

            {{-- Sections INTERDITES aux vendeurs --}}
            @if(!$isSeller)

            {{-- Pointage : VISIBLE pour tous (dont Supervisor) --}}
            <a href="{{ route('pointages.index') }}"
               class="sidebar-link {{ request()->routeIs('pointages.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>{{ __('Pointage') }}</span>
            </a>

            {{-- Rapport : VISIBLE pour tous (dont Supervisor) --}}
            <a href="{{ route('reports.index') }}"
               class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>{{ __('Rapport') }}</span>
            </a>

            @php
                $isCongeOpen = request()->routeIs('conges.*')
                            || request()->routeIs('conge-validations.*')
                            || request()->routeIs('admin.leave-types.*')
                            || request()->routeIs('admin.leave-policies.*')
                            || request()->routeIs('admin.leave-periods.*')
                            || request()->routeIs('admin.company-holidays.*')
                            || request()->routeIs('admin.leave-workflows.*');

                $pendingCount = 0;
                try {
                    if ($isSuperAdmin) {
                        $pendingCount = (int) \App\Models\CongeValidation::where('status', 'en_cours')->count();
                    } elseif ($isSimpleAdmin && !$isSupervisor) {
                        $pendingCount = (int) \App\Models\CongeValidation::where('SiegeID', $user->SiegeID)
                            ->where('status', 'en_cours')
                            ->count();
                    }
                } catch (\Throwable $e) {
                    $pendingCount = 0;
                }
            @endphp

            <details class="sidebar-group" {{ $isCongeOpen ? 'open' : '' }}>
                <summary class="sidebar-link sidebar-group-title {{ $isCongeOpen ? 'active' : '' }}">
                    <i class="bi bi-calendar-x"></i>
                    <span>{{ __('Congés') }}</span>
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark rounded-pill ms-auto me-2" style="font-size:0.65rem;">
                            {{ $pendingCount }}
                        </span>
                    @endif
                    <i class="bi bi-chevron-down small chevron"></i>
                </summary>

                {{-- Gestion des congés : VISIBLE pour tous --}}
                <a href="{{ route('conges.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('conges.index') ? 'active' : '' }}">
                    <i class="bi bi-list-ul"></i>
                    <span>{{ __('Gestion des Congés') }}</span>
                </a>

                {{-- Validation congé : masqué pour Supervisor --}}
                @if(!$isSupervisor && ($isSuperAdmin || $isSimpleAdmin))
                    <a href="{{ route('conge-validations.index') }}"
                       class="sidebar-link sidebar-sub {{ request()->routeIs('conge-validations.*') ? 'active' : '' }}">
                        <i class="bi bi-patch-check"></i>
                        <span>{{ __('Validation congé') }}</span>
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size:0.65rem;">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                @endif

                {{-- Paramètres congés : masqué pour Supervisor --}}
                @if(!$isSupervisor && ($isSuperAdmin || $isSimpleAdmin))
                    <a href="{{ route('admin.leave-types.index') }}"
                       class="sidebar-link sidebar-sub {{ request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.leave-policies.*') || request()->routeIs('admin.leave-periods.*') || request()->routeIs('admin.company-holidays.*') || request()->routeIs('admin.leave-workflows.*') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>{{ __('Paramètres congés') }}</span>
                    </a>
                @endif
            </details>

            {{-- Jour férié : masqué pour Supervisor --}}
            @if(!$isSupervisor)
            <a href="{{ route('jours-non-travailles.index') }}"
               class="sidebar-link {{ request()->routeIs('jours-non-travailles.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span>{{ __('Jour férié') }}</span>
            </a>
            @endif

            @endif

            {{-- Événements : Super Admin et Simple Admin/Master uniquement (jamais Supervisor) --}}
            @if(!$isSupervisor && ($isSuperAdmin || $isSimpleAdmin))
            <a href="{{ route('evenements.index') }}"
               class="sidebar-link {{ request()->routeIs('evenements.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i>
                <span>{{ __('Événements') }}</span>
                @if($isSimpleAdmin)
                    @php
                        try {
                            $navErrCount = app(\App\Services\EventDetectionService::class)
                                ->countUnresolved($user->SiegeID);
                        } catch (\Throwable $e) {
                            $navErrCount = 0;
                        }
                    @endphp
                    @if($navErrCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size:0.65rem;">
                            {{ $navErrCount }}
                        </span>
                    @endif
                @endif
            </a>
            @endif

        @endif

    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="bi bi-box-arrow-right"></i> {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</aside>