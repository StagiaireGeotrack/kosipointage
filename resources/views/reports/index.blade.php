{{-- resources/views/reports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Rapports') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4 mb-3">
                    <!-- Rapport quotidien -->
                    <div class="col-12 col-md-12">
                        <div class="card h-100 shadow-sm border">                            
                            <p class="fs-5 fw-medium text-dark pt-3 px-3">{{ __('Rapports disponibles envoyés en e-mail de tous les employés avec les jours de congés') }}</p>
                            <div class="card-body p-3">
                                <form method="POST" action="{{ route('reports.rapport-auto') }}" id="rapportForm">
                                    @csrf

                                    <div class="row g-3 mb-4">
                                        <div class="col-lg-6">
                                            <x-input-label for="SiegeID" :value="__('Siège')" /><span class="text-danger">*</span>
                                            <select id="SiegeID" name="SiegeID" class="form-select mt-1" required>
                                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                                @foreach($sieges as $siege)
                                                    <option value="{{ $siege->ID }}" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == $siege->ID ? 'selected' : '' }}>
                                                        {{ $siege->Nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                                        </div>
                                        <div class="col-lg-6">
                                            <x-input-label for="email" :value="__('Envoyer à l\'E-mail')" />
                                            <x-text-input id="email" name="email" type="email" class="form-control mt-1"/>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            <small class="form-text text-muted">
                                                Si non renseigné, le rapport sera envoyé à "{{ Auth::user()->Identifiant_email }}"
                                            </small>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-lg-6">
                                            <x-input-label for="mois" :value="__('Mois')" /><span class="text-danger">*</span>
                                            <select id="mois" name="mois" class="form-select mt-1" required>
                                                <option value="">{{ __('Sélectionnez un mois') }}</option>
                                                @foreach($mois as $m)
                                                    <option value="{{ $m['numero'] }}" {{ $m['numero'] == now()->month ? 'selected' : '' }}>
                                                        {{  ucfirst($m['nom']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('mois')" class="mt-2" />
                                        </div>
                                        <div class="col-lg-6">
                                            <x-input-label for="annee" :value="__('Année')" /><span class="text-danger">*</span>
                                            <select id="annee" name="annee" class="form-select mt-1" required>
                                                <option value="">{{ __('Sélectionnez une année') }}</option>
                                                @foreach($annees as $a)
                                                    <option value="{{ $a }}" {{ $a == now()->year ? 'selected' : '' }}>
                                                        {{ $a }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('annee')" class="mt-2" />
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <x-primary-button class="btn btn-primary" id="submitBtn" type="submit">
                                            <span id="btnText">{{ __('Envoyer') }}</span>
                                            <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4 mt-3">
                    <!-- Rapport quotidien -->
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-body p-3">
                                <h4 class="fs-5 fw-semibold mb-2">{{ __('Rapports (JOUR) sans les jours de congés') }}</h4>
                                <a href="{{ route('reports.daily') }}" class="btn btn-primary text-uppercase small fw-semibold">
                                    {{ __('Voir') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rapport jour/nuit -->
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-body p-3">
                                <h4 class="fs-5 fw-semibold mb-2">{{ __('Rapports (JOUR et NUIT) sans les jours de congés') }}</h4>
                                <a href="{{ route('reports.day-night') }}" class="btn btn-primary text-uppercase small fw-semibold">
                                    {{ __('Voir') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques globales -->
                <div class="mt-4">
                    <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Statistiques') }}</h3>
                    
                    <div class="row g-3">
                        <!-- Nombre total d'employés -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card shadow-sm border h-100">
                                <div class="card-body p-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-3" style="width: 56px; height: 56px;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="small text-muted mb-1">{{ __('Nombre d\'employés') }}</p>
                                    <h4 class="fs-3 fw-bold text-dark mb-0">{{ $stats['employees_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nombre total de pointages -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card shadow-sm border h-100">
                                <div class="card-body p-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-3" style="width: 56px; height: 56px;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="small text-muted mb-1">{{ __('Nombre de pointages') }}</p>
                                    <h4 class="fs-3 fw-bold text-dark mb-0">{{ $stats['pointages_count'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Nombre de pointages aujourd'hui -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card shadow-sm border h-100">
                                <div class="card-body p-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning mb-3" style="width: 56px; height: 56px;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="small text-muted mb-1">{{ __('Nombre de pointages ce jour') }}</p>
                                    <h4 class="fs-3 fw-bold text-dark mb-0">{{ $stats['today_pointages'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Employés actifs -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card shadow-sm border h-100">
                                <div class="card-body p-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info mb-3" style="width: 56px; height: 56px;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="small text-muted mb-1">{{ __('Nombre de pointages activés') }}</p>
                                    <h4 class="fs-3 fw-bold text-dark mb-0">{{ $stats['active_employees'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.getElementById('rapportForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            // Désactiver le bouton
            submitBtn.disabled = true;
            
            // Afficher le loader et masquer le texte
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
            
            // Optionnel : changer le texte
            btnText.textContent = 'Envoi en cours...';
        });
    </script>
    @endpush
</x-app-layout>