{{-- resources/views/components/sidebar.blade.php --}}
<div id="sidebar-overlay" onclick="closeSidebar()" style="display:none; position:fixed; inset:0; z-index:1040; background:rgba(0,0,0,0.5);"></div>

<aside id="app-sidebar" class="app-sidebar collapsed">
    {{-- Logo / Branding (texte supprimé, logo agrandi) --}}
    <a href="@if(auth()->user()->isTrueSuperAdmin()) {{ route('dashboard') }}
             @elseif(auth()->user()->isSeller()) {{ route('dashboard.seller') }}
             @else {{ route('dashboard.simple-admin') }}
             @endif"
       class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="KOSI-TIME" height="80">
    </a>

    {{-- Liens de navigation --}}
    <nav class="sidebar-nav">

        {{-- Tableau de bord --}}
        <a href="@if(auth()->user()->isTrueSuperAdmin()) {{ route('dashboard') }}
                 @elseif(auth()->user()->isSeller()) {{ route('dashboard.seller') }}
                 @else {{ route('dashboard.simple-admin') }}
                 @endif"
           class="sidebar-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>{{ __('Tableau de bord') }}</span>
        </a>

        {{-- Siège : Visible par tous --}}
        <a href="{{ route('sieges.index') }}"
           class="sidebar-link {{ request()->routeIs('sieges.*') ? 'active' : '' }}">
            <i class="bi bi-building"></i>
            <span>{{ __('Siège') }}</span>
        </a>

        {{-- Administrateur & Revendeur : Super Admin uniquement --}}
        @can('superadmin')
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
        @endcan

        {{-- Gestion des Managers (Affiché pour Super Admin, Simple Admin (non-manager) et Vendeur (non-manager)) --}}
        @if(Auth::user()->isTrueSuperAdmin() || (Auth::user()->isSimpleAdmin() && !Auth::user()->isManagerSimpleAdmin()) || (Auth::user()->isSeller() && !Auth::user()->isManagerSeller()))
        @php
            $isManagerActive = in_array(request('role'), ['manager_super_admin', 'manager_simple_admin', 'manager_seller']);
        @endphp
        <style>
            .sidebar-link[aria-expanded="true"] .chevron-icon { transform: rotate(180deg); }
        </style>
        <a href="#managerSubmenu" data-bs-toggle="collapse" class="sidebar-link {{ $isManagerActive ? '' : 'collapsed' }}" aria-expanded="{{ $isManagerActive ? 'true' : 'false' }}">
            <i class="bi bi-person-vcard"></i>
            <span>{{ __('Gestion des Managers') }}</span>
            <i class="bi bi-chevron-down ms-auto chevron-icon" style="font-size: 0.8rem; transition: transform 0.3s;"></i>
        </a>
        <div class="collapse {{ $isManagerActive ? 'show' : '' }} mt-1" id="managerSubmenu">
            @if(Auth::user()->isTrueSuperAdmin())
            <a href="{{ route('administrateurs.index', ['role' => 'manager_super_admin']) }}"
               class="sidebar-link ms-3 {{ request('role') === 'manager_super_admin' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                <i class="bi bi-person-check-fill"></i>
                <span>{{ __('Super Admin') }}</span>
            </a>
            @endif
            
            @if(Auth::user()->isTrueSuperAdmin() || (Auth::user()->isSimpleAdmin() && !Auth::user()->isManagerSimpleAdmin()))
            <a href="{{ route('administrateurs.index', ['role' => 'manager_simple_admin']) }}"
               class="sidebar-link ms-3 {{ request('role') === 'manager_simple_admin' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                <i class="bi bi-person-check"></i>
                <span>{{ __('Admin Simple') }}</span>
            </a>
            @endif
            
            @if(Auth::user()->isTrueSuperAdmin() || (Auth::user()->isSeller() && !Auth::user()->isManagerSeller()))
            <a href="{{ route('sellers.index', ['role' => 'manager_seller']) }}"
               class="sidebar-link ms-3 {{ request('role') === 'manager_seller' ? 'active' : '' }}" style="padding-top: 0.35rem; padding-bottom: 0.35rem; font-size: 0.9rem;">
                <i class="bi bi-person-rolodex"></i>
                <span>{{ __('Revendeur') }}</span>
            </a>
            @endif
        </div>
        @endif

        {{-- Entreprises : Visible par tous --}}
        <a href="{{ route('entreprises.index') }}"
           class="sidebar-link {{ request()->routeIs('entreprises.*') ? 'active' : '' }}">
            <i class="bi bi-shop"></i>
            <span>{{ __('Site ou établissement') }}</span>
        </a>

        {{-- Employés : Visible par tous --}}
        <a href="{{ route('employes.index') }}"
           class="sidebar-link {{ request()->routeIs('employes.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>{{ __('Employé') }}</span>
        </a>

        {{-- Sections INTERDITES aux vendeurs --}}
        @if(!auth()->user()->isSeller())

        <a href="{{ route('pointages.index') }}"
           class="sidebar-link {{ request()->routeIs('pointages.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>{{ __('Pointage') }}</span>
        </a>

        <a href="{{ route('reports.index') }}"
           class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>{{ __('Rapport') }}</span>
        </a>

        <a href="{{ route('conges.index') }}"
           class="sidebar-link {{ request()->routeIs('conges.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-x"></i>
            <span>{{ __('Congé') }}</span>
        </a>

        {{-- Validation congé : Super Admin et Simple Admin --}}
        @if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
        <a href="{{ route('conge-validations.index') }}"
           class="sidebar-link {{ request()->routeIs('conge-validations.*') ? 'active' : '' }}">
            <i class="bi bi-patch-check"></i>
            <span>{{ __('Validation congé') }}</span>
            @php
                try {
                    $navCongeCount = auth()->user()->isTrueSuperAdmin()
                        ? \App\Models\CongeValidation::where('status', 'en_cours')->count()
                        : \App\Models\CongeValidation::where('SiegeID', auth()->user()->SiegeID)->where('status', 'en_cours')->count();
                } catch (\Throwable $e) {
                    $navCongeCount = 0;
                }
            @endphp
            @if($navCongeCount > 0)
                <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size:0.65rem;">
                    {{ $navCongeCount }}
                </span>
            @endif
        </a>
        @endif

        {{-- Paramètres congés : Super Admin et Simple Admin --}}
      

@if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
    <a href="{{ route('admin.leave-policies') }}"
       class="sidebar-link {{ request()->routeIs('admin.leave-policies') ? 'active' : '' }}">
        <i class="bi bi-sliders"></i>
        <span>{{ __('Paramètres congés') }}</span>
    </a>
@endif


        <a href="{{ route('jours-non-travailles.index') }}"
           class="sidebar-link {{ request()->routeIs('jours-non-travailles.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event"></i>
            <span>{{ __('Jour férié') }}</span>
        </a>

        @endif

        {{-- Événements : Super Admin et Simple Admin uniquement --}}
        @if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
        <a href="{{ route('evenements.index') }}"
           class="sidebar-link {{ request()->routeIs('evenements.*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-triangle"></i>
            <span>{{ __('Événements') }}</span>
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
                    <span class="badge bg-danger rounded-pill ms-auto" style="font-size:0.65rem;">
                        {{ $navErrCount }}
                    </span>
                @endif
            @endif
        </a>
        @endif

    </nav>

    {{-- Pied de sidebar : email + déconnexion --}}
    {{-- Pied de sidebar : déconnexion uniquement --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="bi bi-box-arrow-right"></i> {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</aside>
