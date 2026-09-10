{{-- resources/views/components/sidebar.blade.php --}}
<div id="sidebar-overlay" onclick="closeSidebar()" style="display:none; position:fixed; inset:0; z-index:1040; background:rgba(0,0,0,0.5);"></div>

@php
    $user = auth()->user();
    $isAdmin = $user instanceof \App\Models\Administration;
    $isEmploye = $user instanceof \App\Models\Employe;

    // Rôles
    $isSuperAdmin = $isAdmin && $user->IsSuperAdmin == 1;
    $isSimpleAdmin = $isAdmin && $user->IsSuperAdmin == 0 && $user->IsSeller == 0;
    $isSeller = $isAdmin && $user->IsSeller == 1;
    $isSupervisor = $isAdmin && $user->IsSupervisor == 1;

    // ✅ Manager = admin du siège (IsManager=1) qui n'est PAS un Supervisor
    $isManagerOfSiege = $isAdmin
        && $user->IsManager == 1
        && $user->IsSuperAdmin == 0
        && $user->IsSeller == 0
        && !$isSupervisor;

    // (ancien code conservé pour compatibilité)
    $isManagerSuperAdmin = $isAdmin && $user->IsSuperAdmin == 1 && ($user->isManager ?? false);
    $isManagerSimpleAdmin = $isManagerOfSiege;
    $isManagerSeller = $isAdmin && $user->IsSeller == 1 && ($user->isManager ?? false);

    $isEmployeMode = $isEmploye;

    // Notifications
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

    // Plannings en attente
    $hasPendingPlanning = false;
    if ($isAdmin) {
        try {
            $hasPendingPlanning = \App\Models\Planning::where('statut', 'genere')->count() > 0;
        } catch (\Throwable $e) {
            $hasPendingPlanning = false;
        }
    }

    // ✅ États "ouverts" des sous-menus (calculés une seule fois, en haut)
    $isManagerActive = in_array(request('role'), ['manager_super_admin', 'manager_simple_admin', 'manager_seller']);
    $isSupervisorMenuOpen = request()->routeIs('supervisors.*');
@endphp

{{-- ✅ Style commun pour la rotation du chevron (hors de toute condition) --}}
<style>
    .sidebar-link[aria-expanded="true"] .chevron-icon { transform: rotate(180deg); }
</style>

<aside id="app-sidebar" class="app-sidebar collapsed">
    {{-- Logo / Branding --}}
    <a href="@if($isSuperAdmin) {{ route('dashboard') }}
             @elseif($isSeller) {{ route('dashboard.seller') }}
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
                 @elseif($isEmploye) {{ route('employe.dashboard') }}
                 @else {{ route('dashboard.simple-admin') }}
                 @endif"
           class="sidebar-link {{ request()->routeIs('dashboard*') || request()->routeIs('employe.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>{{ __('Tableau de bord') }}</span>
        </a>

        {{-- Siège : caché pour le Supervisor --}}
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

        {{-- ADMINISTRATEUR --}}
        @if($isAdmin)

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

            {{-- Gestion des Managers --}}
            @if(($isSuperAdmin || ($isSimpleAdmin && !$isManagerOfSiege) || ($isSeller && !$isManagerSeller)) && !$isSupervisor)
            <a href="#managerSubmenu"
               data-bs-toggle="collapse"
               class="sidebar-link {{ $isManagerActive ? '' : 'collapsed' }}"
               aria-expanded="{{ $isManagerActive ? 'true' : 'false' }}">
                <i class="bi bi-person-vcard"></i>
                <span>{{ __('Gestion des Managers') }}</span>
                <i class="bi bi-chevron-down ms-auto chevron-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
            </a>
            <div class="collapse {{ $isManagerActive ? 'show' : '' }} mt-1" id="managerSubmenu">
                @if($isSuperAdmin)
                <a href="{{ route('administrateurs.index', ['role' => 'manager_super_admin']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_super_admin' ? 'active' : '' }}"
                   style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-check-fill"></i>
                    <span>{{ __('Super Admin') }}</span>
                </a>
                @endif
                @if($isSuperAdmin || ($isSimpleAdmin && !$isManagerOfSiege))
                <a href="{{ route('administrateurs.index', ['role' => 'manager_simple_admin']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_simple_admin' ? 'active' : '' }}"
                   style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-check"></i>
                    <span>{{ __('Admin Simple') }}</span>
                </a>
                @endif
                @if($isSuperAdmin || ($isSeller && !$isManagerSeller))
                <a href="{{ route('sellers.index', ['role' => 'manager_seller']) }}"
                   class="sidebar-link ms-3 {{ request('role') === 'manager_seller' ? 'active' : '' }}"
                   style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                    <i class="bi bi-person-rolodex"></i>
                    <span>{{ __('Revendeur') }}</span>
                </a>
                @endif
            </div>
            @endif

            {{-- ✅ Responsables de service : uniquement pour le Manager du siège --}}
            {{-- ✅ Responsables de service : uniquement pour le Manager du siège --}}
@if($isManagerOfSiege)
    @php
        $isSupervisorOpen = request()->routeIs('supervisors.*');
    @endphp
    <details class="sidebar-group" {{ $isSupervisorOpen ? 'open' : '' }}>
        <summary class="sidebar-link sidebar-group-title {{ $isSupervisorOpen ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>{{ __('Responsables de service') }}</span>
            <i class="bi bi-chevron-down small chevron"></i>
        </summary>

        <a href="{{ route('supervisors.index') }}"
           class="sidebar-link sidebar-sub {{ request()->routeIs('supervisors.index') ? 'active' : '' }}">
            <i class="bi bi-list-ul"></i>
            <span>{{ __('Liste des responsables') }}</span>
        </a>

        <a href="{{ route('supervisors.create') }}"
           class="sidebar-link sidebar-sub {{ request()->routeIs('supervisors.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i>
            <span>{{ __('Créer un responsable') }}</span>
        </a>
    </details>
@endif

            {{-- Site ou établissement : caché pour le Supervisor --}}
            @if(!$isSupervisor)
            <a href="{{ route('entreprises.index') }}"
               class="sidebar-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>{{ __('Site ou établissement') }}</span>
            </a>
            @endif

            {{-- Employé : visible pour tous --}}
            <a href="{{ route('employes.index') }}"
               class="sidebar-link {{ request()->routeIs('employes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>{{ __('Employé') }}</span>
            </a>

            {{-- 📅 PLANNING --}}
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

                @if(Route::has('planning.create') && !$isSupervisor)
                <a href="{{ route('planning.create') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('planning.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>{{ __('Créer un planning') }}</span>
                </a>
                @endif

                @if(Route::has('planning.horaires-types.index') && !$isSupervisor)
                <a href="{{ route('planning.horaires-types.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('planning.horaires-types.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>{{ __('Horaires types') }}</span>
                </a>
                @endif
            </details>
            @endif

            {{-- Organisation RH : cachée pour le Supervisor --}}
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

            {{-- Pointage --}}
            <a href="{{ route('pointages.index') }}"
               class="sidebar-link {{ request()->routeIs('pointages.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>{{ __('Pointage') }}</span>
            </a>

            {{-- Rapport --}}
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
                    } elseif ($isSimpleAdmin) {
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

                <a href="{{ route('conges.index') }}"
                   class="sidebar-link sidebar-sub {{ request()->routeIs('conges.index') ? 'active' : '' }}">
                    <i class="bi bi-list-ul"></i>
                    <span>{{ __('Gestion des Congés') }}</span>
                </a>

                @if($isSuperAdmin || $isSimpleAdmin)
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

                @if(($isSuperAdmin || $isSimpleAdmin) && !$isSupervisor)
                    <a href="{{ route('admin.leave-types.index') }}"
                       class="sidebar-link sidebar-sub {{ request()->routeIs('admin.leave-types.*') || request()->routeIs('admin.leave-policies.*') || request()->routeIs('admin.leave-periods.*') || request()->routeIs('admin.company-holidays.*') || request()->routeIs('admin.leave-workflows.*') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>{{ __('Paramètres congés') }}</span>
                    </a>
                @endif
            </details>

            {{-- Jour férié : caché pour le Supervisor --}}
            @if(!$isSupervisor)
            <a href="{{ route('jours-non-travailles.index') }}"
               class="sidebar-link {{ request()->routeIs('jours-non-travailles.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i>
                <span>{{ __('Jour férié') }}</span>
            </a>
            @endif

            @endif

            {{-- Événements : caché pour le Supervisor --}}
            @if(($isSuperAdmin || $isSimpleAdmin) && !$isSupervisor)
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