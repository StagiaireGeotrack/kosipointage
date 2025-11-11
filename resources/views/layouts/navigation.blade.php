{{-- resources/views/layouts/navigation.blade.php --}}
<style>
    /* Navbar principale - FIXE */
    .navbar-custom {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 1px 5px #3F52A4;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        transition: box-shadow 0.3s ease;
    }

    /* Effet d'ombre au scroll */
    .navbar-custom.scrolled {
        box-shadow: 0 4px 12px rgba(63, 82, 164, 0.15);
    }

    /* Logo et titre */
    .navbar-brand {
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
    }

    .navbar-brand h1 {
        color: #3F52A4;
        font-weight: 700;
    }

    /* Navigation links */
    .nav-link {
        position: relative;
        font-weight: 500;
        color: #4a5568 !important;
        transition: all 0.3s ease;
        margin: 0 0.25rem;
    }

    .nav-link:hover {
        color: #3F52A4 !important;
        transform: translateY(-2px);
    }

    /* Soulignement animé pour les liens */
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #3F52A4;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after {
        width: 80%;
    }

    /* Lien actif */
    .nav-link.active-link {
        background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%) !important;
        color: white !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        box-shadow: 0 4px 6px rgba(63, 82, 164, 0.3);
    }

    .nav-link.active-link::after {
        display: none;
    }

    /* Badge personnalisé */
    .badge-custom {
        background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
        box-shadow: 0 2px 4px rgba(63, 82, 164, 0.3);
    }

    /* Badge pour vendeur */
    .badge-seller {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    /* Badge pour simple admin */
    .badge-simple {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
    }

    /* Dropdown user button */
    .user-dropdown-btn {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #3F52A4;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        color: #2d3748 !important;
    }

    .user-dropdown-btn:hover {
        background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%);
        color: white !important;
        box-shadow: 0 4px 8px rgba(63, 82, 164, 0.3);
        transform: translateY(-2px);
    }

    .user-dropdown-btn:hover .badge-custom,
    .user-dropdown-btn:hover .badge-seller,
    .user-dropdown-btn:hover .badge-simple {
        background: white;
        color: #3F52A4;
    }

    /* Dropdown menu */
    .dropdown-menu-custom {
        border: 2px solid #3F52A4;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(63, 82, 164, 0.2);
        margin-top: 0.5rem;
        position: absolute !important;
    }

    .dropdown-item {
        transition: all 0.3s ease;
        padding: 0.75rem 1.25rem;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, #3F52A4 0%, #5a6fb8 100%);
        color: white !important;
        transform: translateX(5px);
    }

    .dropdown-item i {
        margin-right: 0.5rem;
    }

    /* Hamburger button */
    .navbar-toggler {
        border: 2px solid #3F52A4;
        border-radius: 8px;
    }

    .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(63, 82, 164, 0.25);
    }

    /* Animation pour le logo */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .navbar-brand img {
        animation: pulse 3s ease-in-out infinite;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .navbar-brand h1 {
            font-size: 1.25rem;
        }
        
        .nav-link {
            padding: 0.5rem 1rem;
        }

        /* Menu mobile avec scroll si nécessaire */
        .navbar-collapse {
            max-height: calc(100vh - 80px);
            overflow-y: auto;
        }
    }

    /* Backdrop pour mobile quand le menu est ouvert */
    @media (max-width: 575px) {
        .navbar-collapse.show,
        .navbar-collapse.collapsing {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 8px 16px rgba(63, 82, 164, 0.2);
            border-radius: 0 0 10px 10px;
            padding: 1rem;
            margin-top: 0.5rem;
        }
    }
</style>

<nav class="navbar navbar-expand-sm navbar-light navbar-custom">
    <div class="container-fluid">
        <!-- Logo et titre -->
        <a href="@if(auth()->user()->isTrueSuperAdmin()) {{ route('dashboard') }} 
                @elseif(auth()->user()->isSeller()) {{ route('dashboard.seller') }} 
                @else {{ route('dashboard.simple-admin') }} 
                @endif" 
                class="navbar-brand d-flex align-items-center">
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
                
                {{-- Tableau de bord : Seulement pour les vrais Super Admin --}}
                @can('superadmin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                        <i class="bi bi-speedometer2"></i> {{ __('Tableau de bord') }}
                    </a>
                </li>
                @endcan                
                
                {{-- Siège : Visible par tous (Super Admin, Revendeur, Simple Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('sieges.index') }}" class="nav-link {{ request()->routeIs('sieges.*') ? 'active-link' : '' }}">
                        <i class="bi bi-building"></i> {{ __('Siège') }}
                    </a>
                </li>

                {{-- Administrateur et Revendeur : Seulement pour les vrais Super Admin --}}
                @can('superadmin')
                <li class="nav-item">
                    <a href="{{ route('administrateurs.index') }}" class="nav-link {{ request()->routeIs('administrateurs.*') ? 'active-link' : '' }}">
                        <i class="bi bi-person-gear"></i> {{ __('Administrateur') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('sellers.index') }}" class="nav-link {{ request()->routeIs('sellers.*') ? 'active-link' : '' }}">
                        <i class="bi bi-shop-window"></i> {{ __('Revendeur') }}
                    </a>
                </li>
                @endcan
                
                {{-- Entreprises : Visible par tous (Super Admin, Revendeur, Simple Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('entreprises.index') }}" class="nav-link {{ request()->routeIs('entreprises.*') ? 'active-link' : '' }}">
                        <i class="bi bi-shop"></i> {{ __('Site ou établissement') }}
                    </a>
                </li>
                
                {{-- Employés : Visible par tous (Super Admin, Revendeur, Simple Admin) --}}
                <li class="nav-item">
                    <a href="{{ route('employes.index') }}" class="nav-link {{ request()->routeIs('employes.*') ? 'active-link' : '' }}">
                        <i class="bi bi-people"></i> {{ __('Employé') }}
                    </a>
                </li>
                
                {{-- Sections INTERDITES aux vendeurs --}}
                @if(!auth()->user()->isSeller())
                    {{-- Pointage : Super Admin et Simple Admin uniquement --}}
                    <li class="nav-item">
                        <a href="{{ route('pointages.index') }}" class="nav-link {{ request()->routeIs('pointages.*') ? 'active-link' : '' }}">
                            <i class="bi bi-clock-history"></i> {{ __('Pointage') }}
                        </a>
                    </li>
                    
                    {{-- Rapport : Super Admin et Simple Admin uniquement --}}
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active-link' : '' }}">
                            <i class="bi bi-file-earmark-text"></i> {{ __('Rapport') }}
                        </a>
                    </li>

                    {{-- Congé : Super Admin et Simple Admin uniquement --}}
                    <li class="nav-item">
                        <a href="{{ route('conges.index') }}" class="nav-link {{ request()->routeIs('conges.*') ? 'active-link' : '' }}">
                            <i class="bi bi-calendar-x"></i> {{ __('Congé') }}
                        </a>
                    </li>

                    {{-- Jour férié : Super Admin et Simple Admin uniquement --}}
                    <li class="nav-item">
                        <a href="{{ route('jours-non-travailles.index') }}" class="nav-link {{ request()->routeIs('jours-non-travailles.*') ? 'active-link' : '' }}">
                            <i class="bi bi-calendar-event"></i> {{ __('Jour férié') }}
                        </a>
                    </li>
                @endif

            </ul>

            <!-- User Menu Dropdown -->
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn user-dropdown-btn text-decoration-none dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i>
                        <div>{{ Auth::user()->Identifiant_email }}</div>
                        @if(Auth::user()->isTrueSuperAdmin())
                            <span class="badge-custom">{{ __('Super Administrateur') }}</span>
                        @elseif(Auth::user()->isSeller())
                            <span class="badge-seller">{{ __('Revendeur') }}</span>
                        @else
                            <span class="badge-simple">{{ __('Simple Administrateur') }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> {{ __('Identifiant') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" style="border-color: #3F52A4; opacity: 0.3;"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Déconnexion') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Effet d'ombre au scroll pour la navbar fixe
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar-custom');
        if (window.scrollY > 10) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>