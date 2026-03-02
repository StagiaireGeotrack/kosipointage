<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Détails pointages') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('pointages.index') }}" class="btn btn-secondary d-inline-flex align-items-center">
                        <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Retour à la liste') }}
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pointages.edit', $pointage->ID) }}" class="btn btn-primary d-inline-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ __('Modifier') }}
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="bg-light p-4 rounded shadow-sm">
                            <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Information générale') }}</h3>
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if ($pointage->type_ == 'entry')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success" style="width: 48px; height: 48px;">
                                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 48px; height: 48px;">
                                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="fs-5 fw-semibold mb-1">
                                        @if ($pointage->type_ == 'entry')
                                            {{ __('Entrée') }}
                                        @else
                                            {{ __('Sortie') }}
                                        @endif
                                    </h4>
                                    <p class="small text-muted mb-0">{{ ucfirst($pointage->timestamp_->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column gap-2">
                                
                                <div class="d-flex">
                                    <div class="small fw-medium text-secondary" style="width: 33.33%;">{{ __('N° Matricule') }}</div>
                                    <div class="small text-dark" style="width: 66.67%;">{{ $pointage->employe->num_mat ?? "-" }}</div>
                                </div>
                                
                                <div class="d-flex">
                                    <div class="small fw-medium text-secondary" style="width: 33.33%;">{{ __('Employé') }}</div>
                                    <div class="small text-dark" style="width: 66.67%;">
                                        <a href="{{ route('employes.show', $pointage->employee_id) }}" class="text-primary text-decoration-none">
                                            {{ $pointage->employe->Nom }}
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="d-flex">
                                    <div class="small fw-medium text-secondary" style="width: 33.33%;">{{ __('Siège') }}</div>
                                    <div class="small text-dark" style="width: 66.67%;">{{ $pointage->siege->Nom }}</div>
                                </div>
                                
                                <div class="d-flex">
                                    <div class="small fw-medium text-secondary" style="width: 33.33%;">{{ __('Méthode') }}</div>
                                    <div style="width: 66.67%;">
                                        <span class="badge bg-info">
                                            {{ $pointage->auth_method }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-8">
                        <div class="bg-light p-4 rounded shadow-sm">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Localisation') }}</h3>
                                    <div class="bg-white p-3 rounded shadow-sm mb-3">
                                        <div class="mb-2">
                                            <span class="small fw-medium text-secondary">{{ __('Latitude') }}:</span>
                                            <span class="small text-dark ms-1">{{ $pointage->latitude }}</span>
                                        </div>
                                        <div>
                                            <span class="small fw-medium text-secondary">{{ __('Longitude') }}:</span>
                                            <span class="small text-dark ms-1">{{ $pointage->longitude }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>