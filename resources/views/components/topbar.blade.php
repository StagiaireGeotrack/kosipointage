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

        @php
            $user = Auth::user();
            $isAdmin = $user instanceof \App\Models\Administration;
            $isEmploye = $user instanceof \App\Models\Employe;
            
            // Rôle de l'utilisateur
            $roleLabel = 'Employé';
            $roleColor = 'linear-gradient(135deg, #4f8a8b, #3d6b6c)';
            
            if ($isAdmin) {
                if ($user->IsSuperAdmin == 1 && !($user->isManager ?? false)) {
                    $roleLabel = 'Super Administrateur';
                    $roleColor = 'linear-gradient(135deg, #3F52A4, #5a6fb8)';
                } elseif ($user->IsSuperAdmin == 1 && ($user->isManager ?? false)) {
                    $roleLabel = 'Manager Super Admin';
                    $roleColor = 'linear-gradient(135deg, #3F52A4, #5a6fb8)';
                } elseif ($user->IsSeller == 1 && ($user->isManager ?? false)) {
                    $roleLabel = 'Manager Revendeur';
                    $roleColor = 'linear-gradient(135deg, #10b981, #059669)';
                } elseif ($user->IsSeller == 1) {
                    $roleLabel = 'Revendeur';
                    $roleColor = 'linear-gradient(135deg, #10b981, #059669)';
                } elseif ($user->isManager ?? false) {
                    $roleLabel = 'Manager Admin Simple';
                    $roleColor = 'linear-gradient(135deg, #6b7280, #4b5563)';
                } else {
                    $roleLabel = 'Simple Administrateur';
                    $roleColor = 'linear-gradient(135deg, #6b7280, #4b5563)';
                }
            } elseif ($isEmploye) {
                $roleLabel = 'Employé';
                $roleColor = 'linear-gradient(135deg, #4f8a8b, #3d6b6c)';
            }
        @endphp

        {{-- Badge rôle --}}
        <span class="topbar-role-badge d-none d-sm-inline" style="background: {{ $roleColor }};">
            {{ __($roleLabel) }}
        </span>

        {{-- Menu dropdown profil --}}
        <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-1"
                    style="border: 2px solid #3F52A4; border-radius: 8px;"
                    type="button" id="topbarUserDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-sm-inline small">
                    @if($isAdmin)
                        {{ $user->Identifiant_email ?? $user->email ?? 'Admin' }}
                    @elseif($isEmploye)
                        {{ $user->email ?? $user->Nom ?? 'Employé' }}
                    @else
                        {{ 'Utilisateur' }}
                    @endif
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="topbarUserDropdown"
                style="border: 2px solid #3F52A4; border-radius: 10px;">
                
                {{-- Logs d'activité (Super Admin uniquement) --}}
                @if($isAdmin && $user->IsSuperAdmin == 1)
                <li>
                    <a class="dropdown-item" href="{{ route('activity-logs.index') }}">
                        <i class="bi bi-journal-text me-2"></i> {{ __("Logs d'activité") }}
                    </a>
                </li>
                @endif

                {{-- Profil Admin --}}
                @if($isAdmin)
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i> {{ __('Identifiant') }}
                    </a>
                </li>
                @endif

                {{-- Profil Employé --}}
                @if($isEmploye)
                <li>
                    <a class="dropdown-item" href="{{ route('employe.profile') }}">
                        <i class="bi bi-person me-2"></i> {{ __('Mon profil') }}
                    </a>
                </li>
                @endif

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