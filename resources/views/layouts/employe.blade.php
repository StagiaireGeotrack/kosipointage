<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KOSI Pointage') }} - Espace Employé</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    {{-- Styles de page spécifiques --}}
    @stack('styles')

    {{-- Vite (CSS + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .employe-app-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f3f4f6; /* gris clair standard de l'app */
        }
        .employe-app-main {
            flex-grow: 1;
            padding-top: 60px; /* Espace pour la topbar fixe */
        }
        .employe-topbar {
            height: 60px;
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .employe-app-content {
            padding: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
    </style>
</head>
<body class="app-body">

    {{-- Wrapper principal --}}
    <div class="employe-app-wrapper">

        {{-- Topbar Employé --}}
        @include('components.employe-topbar')

        {{-- Contenu Principal --}}
        <div class="employe-app-main">
            <main class="employe-app-content">

                {{-- En-tête de page (optionnel via slot $header) --}}
                @if(isset($header))
                    <div class="mb-4">{{ $header }}</div>
                @endif
                
                {{-- Flash Messages --}}
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
                {{ $slot }}

            </main>
        </div>
    </div>

    {{-- Scripts de pages spécifiques --}}
    @stack('scripts')

</body>
</html>
