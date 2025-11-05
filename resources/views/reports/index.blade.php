{{-- resources/views/reports/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Statistique rapport') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Rapports disponibles') }}</h3>
                
                <div class="row g-4 mb-5">
                    <!-- Rapport quotidien -->
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-body p-4">
                                <h4 class="fs-5 fw-semibold mb-2">{{ __('Rapports (JOUR)') }}</h4>
                                <p class="text-muted mb-3">{{ __('Rapport incluant le jour seulement') }}</p>
                                <a href="{{ route('reports.daily') }}" class="btn btn-primary text-uppercase small fw-semibold">
                                    {{ __('Voir') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rapport jour/nuit -->
                    <div class="col-12 col-md-6">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-body p-4">
                                <h4 class="fs-5 fw-semibold mb-2">{{ __('Rapports (NUIT)') }}</h4>
                                <p class="text-muted mb-3">{{ __('Rapport incluant le jour et nuit') }}</p>
                                <a href="{{ route('reports.day-night') }}" class="btn btn-primary text-uppercase small fw-semibold">
                                    {{ __('Voir') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistiques globales -->
                <div class="mt-5">
                    <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Statistiques') }}</h3>
                    
                    <div class="row g-3">
                        <!-- Nombre total d'employés -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card shadow-sm border h-100">
                                <div class="card-body p-4 text-center">
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
                                <div class="card-body p-4 text-center">
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
                                <div class="card-body p-4 text-center">
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
                                <div class="card-body p-4 text-center">
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
</x-app-layout>