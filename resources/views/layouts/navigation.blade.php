{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <!-- Logo et titre -->
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="KOSI-TIME Logo" height="60" class="me-2">
            <h1 class="fs-4 mb-0">{{ __('KOSI-TIME') }}</h1>
        </a>
        
        <!-- Hamburger Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <ul class="navbar-nav me-auto">
                
                @can('superadmin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Tableau de bord
                    </a>
                </li>
                @endcan                
                
                <li class="nav-item">
                    <a href="{{ route('sieges.index') }}" class="nav-link {{ request()->routeIs('sieges.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Siège
                    </a>
                </li>

                @can('superadmin')
                <li class="nav-item">
                    <a href="{{ route('administrateurs.index') }}" class="nav-link {{ request()->routeIs('administrateurs.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Administrateur
                    </a>
                </li>
                @endcan
                
                <li class="nav-item">
                    <a href="{{ route('entreprises.index') }}" class="nav-link {{ request()->routeIs('entreprises.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Site ou établissement
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employes.index') }}" class="nav-link {{ request()->routeIs('employes.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Employé
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('pointages.index') }}" class="nav-link {{ request()->routeIs('pointages.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Pointage
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'rounded bg-secondary text-white px-2' : '' }}">
                        Rapport
                    </a>
                </li>

            </ul>

            <!-- User Menu Dropdown -->
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center text-dark" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div>{{ Auth::user()->Identifiant_email }}</div>
                        @if(Auth::user()->IsSuperAdmin)
                            <span class="badge bg-primary m-1">Super Administrateur</span>
                        @else
                            <span class="badge bg-primary m-1">Simple Administrateur</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <!-- <li><hr class="dropdown-divider"></li> -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> Identifiant
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>