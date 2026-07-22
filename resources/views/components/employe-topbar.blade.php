<header class="employe-topbar">

    {{-- Logo / Marque (Zone gauche) --}}
    <div class="d-flex align-items-center gap-4">
        <a href="{{ route('employe.dashboard') }}" class="text-decoration-none d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 45px; width: auto;" class="me-2">
            <span class="fw-bold fs-5 text-dark d-none d-sm-inline">{{ config('app.name', 'KOSI Pointage') }}</span>
        </a>

        {{-- Menu de Navigation --}}
        <nav class="d-none d-md-flex align-items-center gap-3">
            <a href="{{ route('employe.pointages') }}" class="text-decoration-none text-dark fw-medium {{ request()->routeIs('employe.pointages') ? 'text-success border-bottom border-success' : 'text-muted' }}">
                <i class="bi bi-clock-history me-1"></i> Pointages
            </a>
            <a href="{{ route('employe.rapports') }}" class="text-decoration-none text-dark fw-medium {{ request()->routeIs('employe.rapports') ? 'text-success border-bottom border-success' : 'text-muted' }}">
                <i class="bi bi-journal-text me-1"></i> Rapports
            </a>
            <a href="{{ route('employe.conges.index') }}" class="text-decoration-none text-dark fw-medium {{ request()->routeIs('employe.conges.*') ? 'text-success border-bottom border-success' : 'text-muted' }}">
                <i class="bi bi-calendar-event me-1"></i> Congés
            </a>
        </nav>
    </div>

    {{-- Zone droite : badge rôle + menu profil --}}
    <div class="d-flex align-items-center gap-3">

        {{-- Badge rôle --}}
        @if(session()->has('impersonator_admin_id'))
            <form method="POST" action="{{ route('impersonate.employe.leave') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger rounded-pill me-2">
                    <i class="bi bi-box-arrow-left"></i> Quitter l'impersonation
                </button>
            </form>
        @endif
        
        <span class="topbar-role-badge d-none d-sm-inline" style="background: linear-gradient(135deg, #10b981, #059669); padding: 5px 10px; border-radius: 20px; color: white; font-size: 0.8rem; font-weight: 500;">
            {{ __('Employé') }}
        </span>

        {{-- Menu dropdown profil --}}
        <div class="dropdown">
            <button class="btn btn-sm dropdown-toggle d-flex align-items-center gap-1"
                    style="border: 2px solid #10b981; border-radius: 8px;"
                    type="button" id="topbarUserDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-sm-inline small">{{ Auth::guard('employe')->user()->Nom }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="topbarUserDropdown"
                style="border: 2px solid #10b981; border-radius: 10px;">
                <li>
                    <a class="dropdown-item d-md-none" href="{{ route('employe.pointages') }}">
                        <i class="bi bi-clock-history me-2"></i> {{ __('Pointages') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-md-none" href="{{ route('employe.rapports') }}">
                        <i class="bi bi-journal-text me-2"></i> {{ __('Rapports') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-md-none" href="{{ route('employe.conges.index') }}">
                        <i class="bi bi-calendar-event me-2"></i> {{ __('Congés') }}
                    </a>
                </li>
                <li class="d-md-none"><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('employe.profile') }}">
                        <i class="bi bi-person me-2"></i> {{ __('Mon Profil') }}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('employe.logout') }}">
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
