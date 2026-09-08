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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    

    {{-- Styles de page spécifiques --}}
    @stack('styles')

    {{-- Vite (CSS + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/conges-settings.css') }}">

</head>
<body class="app-body">

    {{-- Wrapper principal --}}
    <div class="app-wrapper">

        {{-- 1. Sidebar Gauche --}}
        @include('components.sidebar')

        {{-- 2. Colonne Droite (Topbar + Contenu) --}}
        <div class="app-main">

            {{-- Topbar --}}
            @include('components.topbar')

            {{-- Contenu Principal --}}
            <main class="app-content">

                {{-- En-tête de page (optionnel via slot $header) --}}
                @if(isset($header))
                    <div class="mb-4">{{ $header }}</div>
                @endif
                
                {{-- Bandeau d'impersonation (Se connecter en tant que) --}}
                @if(session()->has('impersonator_id'))
                    <div class="mb-3">
                        <div class="alert alert-info d-flex align-items-center justify-content-between mb-0 shadow-sm" role="alert" style="border-left: 4px solid #0dcaf0;">
                            <div class="d-flex align-items-center">
                                <svg class="bi me-2" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                  <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5"/>
                                  <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                <span>
                                    <strong>{{ __('Mode usurpation :') }}</strong> {{ __('Vous naviguez actuellement en tant que') }} <strong>{{ auth()->user()->Identifiant_email }}</strong>.
                                </span>
                            </div>
                            <form action="{{ route('impersonate.leave') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-info fw-bold d-flex align-items-center">
                                    <svg class="bi me-2" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                      <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                      <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                    </svg>
                                    {{ __('Revenir à mon compte') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Flash Messages (gérés uniquement ici, pas dans les vues) --}}
                @if(session('success'))
                    <div class="mb-3">
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
                            <div>{!! session('success') !!}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-3">
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                            <div>{!! session('error') !!}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-3">
                        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill mt-1 flex-shrink-0"></i>
                            <div>{!! session('warning') !!}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-3">
                        <div class="alert alert-info alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <div>{!! session('info') !!}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-3">
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>Des erreurs ont été détectées :</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif


                {{-- Vue --}}
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot }}
                @endif

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
        // Sidebar toggle : collapse desktop / overlay mobile+tablette
        function toggleSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const isDesktop = window.innerWidth >= 992;

            if (isDesktop) {
                // Desktop : collapse/expand via classe .collapsed
                sidebar.classList.toggle('collapsed');
            } else {
                // Mobile/tablette : overlay
                sidebar.classList.toggle('open');
                overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
            }
        }
        function closeSidebar() {
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('open');
            sidebar.classList.remove('collapsed');
            overlay.style.display = 'none';
        }

        // Scroll to top
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', function () {
            if ((document.querySelector('.app-content') || window).scrollTop > 50 || window.pageYOffset > 50) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        }, { passive: true });
        scrollToTopBtn.addEventListener('click', function () {
            document.querySelector('.app-content').scrollTop = 0;
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