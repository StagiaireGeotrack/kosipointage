<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'KOSI Pointage') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Instrument Sans', system-ui, -apple-system, sans-serif;
            }
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            .feature-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            }
            .navbar-custom {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold fs-4" href="/">
                    {{ config('app.name', 'KOSI Pointage') }}
                </a>
                
                @if (Route::has('login'))
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                {{ __('Log in') }}
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h1 class="display-3 fw-bold mb-4">
                            Gestion de Pointage Moderne
                        </h1>
                        <p class="lead mb-4">
                            Solution complète pour la gestion des présences, pointages et rapports de vos employés.
                        </p>
                        @guest
                            <div class="d-flex gap-3">
                                <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                    Commencer maintenant
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                    Se connecter
                                </a>
                            </div>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg">
                                Accéder au Dashboard
                            </a>
                        @endguest
                    </div>
                    <div class="col-lg-6">
                        <div class="text-center">
                            <svg class="img-fluid" style="max-width: 400px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 17L12 22L22 17" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M2 12L12 17L22 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5 bg-light">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Fonctionnalités Principales</h2>
                    <p class="lead text-muted">Tout ce dont vous avez besoin pour gérer vos pointages efficacement</p>
                </div>

                <div class="row g-4">
                    <!-- Feature 1 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-3">Gestion Employés</h5>
                                <p class="text-muted small">Gérez facilement vos employés, badges et informations</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-3">Pointages</h5>
                                <p class="text-muted small">Enregistrez les entrées et sorties avec précision</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-3">Rapports</h5>
                                <p class="text-muted small">Générez des rapports détaillés quotidiens et mensuels</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-3">Multi-sites</h5>
                                <p class="text-muted small">Gérez plusieurs sièges et entreprises</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-dark text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="fw-bold">{{ config('app.name', 'KOSI Pointage') }}</h5>
                        <p class="text-muted small mb-0">Solution de gestion de pointage professionnelle</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted small mb-0">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>