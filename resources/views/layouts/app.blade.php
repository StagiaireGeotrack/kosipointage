{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KOSI Pointage') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        /* Compensation pour la navbar fixe */
        body {
            padding-top: 90px; /* Ajustez cette valeur selon la hauteur de votre navbar */
        }

        /* Ajustement responsive */
        @media (max-width: 576px) {
            body {
                padding-top: 80px; /* Légèrement moins sur mobile */
            }
        }
    </style>
    
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-vh-100 bg-light">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white">
                <div class="p-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="m-2">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                   {!! session('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="m-2">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! session('error') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    
    <!-- Scroll to Top Button -->
    <button id="scrollToTop" type="button" title="Retour en haut">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
    
    @stack('scripts')
    
    <script>
        
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        // Show button when user scrolls down 300px
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 50) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });
        
        // Smooth scroll to top when button is clicked
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Convertir toutes les dates en timezone local du navigateur
            document.querySelectorAll('.local-datetime').forEach(function(element) {
                const utcDate = element.getAttribute('data-utc');
                const format = element.getAttribute('data-format');
                
                if (utcDate) {
                    const date = new Date(utcDate);
                    
                    let options;
                    if (format === 'short') {
                        // Format court : "28 jan. 2026, 21:34"
                        options = {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        };
                    } else if (format === 'date-only') {
                        // Date seule : "Mercredi 28 janvier 2026"
                        options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                    } else {
                        // Format complet : "Mercredi 28 janvier 2026 - 21:34:15"
                        options = {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        };
                    }
                    
                    const formatted = date.toLocaleDateString('fr-FR', options);
                    
                    // Formater avec tirets pour le format complet
                    if (format === 'full') {
                        const parts = formatted.split(' à ');
                        if (parts.length === 2) {
                            element.textContent = parts[0].charAt(0).toUpperCase() + parts[0].slice(1) + ' - ' + parts[1];
                        } else {
                            element.textContent = formatted.charAt(0).toUpperCase() + formatted.slice(1);
                        }
                    } else {
                        element.textContent = formatted.charAt(0).toUpperCase() + formatted.slice(1);
                    }
                }
            });
        });

    </script>
    
</body>
</html>