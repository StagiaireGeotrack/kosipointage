{{-- resources/views/components/topbar.blade.php --}}
<header class="app-topbar">

    {{-- Bouton Hamburger (visible uniquement sur mobile/tablette) --}}
    <button class="hamburger-btn" onclick="toggleSidebar()" type="button" title="Ouvrir le menu">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
    </button>

    {{-- Spacer desktop --}}
    <div class="topbar-spacer"></div>

    {{-- Zone droite : badge rôle + menu profil --}}
    <div class="d-flex align-items-center gap-3">

        {{-- Badge rôle --}}
        @if(Auth::user()->isTrueSuperAdmin() && !Auth::user()->isManagerSuperAdmin())
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #3F52A4, #5a6fb8);">
                {{ __('Super Administrateur') }}
            </span>
        @elseif(Auth::user()->isManagerSuperAdmin())
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #3F52A4, #5a6fb8); opacity: 0.85;">
                {{ __('Manager Super Admin') }}
            </span>
        @elseif(Auth::user()->isManagerSeller())
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #10b981, #059669); opacity: 0.85;">
                {{ __('Manager Revendeur') }}
            </span>
        @elseif(Auth::user()->isSeller())
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #10b981, #059669);">
                {{ __('Revendeur') }}
            </span>
        @elseif(Auth::user()->isManagerSimpleAdmin())
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #6b7280, #4b5563); opacity: 0.85;">
                {{ __('Manager Admin Simple') }}
            </span>
        @else
            <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #6b7280, #4b5563);">
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
