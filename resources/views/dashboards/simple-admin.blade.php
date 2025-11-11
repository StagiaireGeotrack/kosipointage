{{-- resources/views/dashboards/simple-admin.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Tableau de bord') }} - {{ $siege->Nom }}
        </h2>
    </x-slot>

    <div class="p-2">
        {{-- Statistiques générales --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-shop fs-1 text-success mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $totalEntreprises }}</h3>
                        <p class="text-muted mb-0">{{ __('Entreprises') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-people fs-1 text-info mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $totalEmployes }}</h3>
                        <p class="text-muted mb-0">{{ __('Employés') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history fs-1 text-primary mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $pointagesToday }}</h3>
                        <p class="text-muted mb-0">{{ __('Pointages Aujourd\'hui') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-week fs-1 text-warning mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $pointagesWeek }}</h3>
                        <p class="text-muted mb-0">{{ __('Pointages cette Semaine') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques --}}
        <div class="row g-3 mb-4">
            {{-- Pointages 7 derniers jours --}}
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('Pointages des 7 derniers jours') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="pointagesChart"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- Statut Employés --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">{{ __('Statut Employés') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="employesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            {{-- Pointages par type --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">{{ __('Pointages d\'aujourd\'hui') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- Pointages par méthode --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">{{ __('Méthodes d\'authentification') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="methodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Graphique pointages 7 jours
        const pointagesCtx = document.getElementById('pointagesChart').getContext('2d');
        new Chart(pointagesCtx, {
            type: 'line',
            data: {
                labels: @json($last7Days),
                datasets: [{
                    label: 'Pointages',
                    data: @json($pointagesLast7Days),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique Employés
        const employesCtx = document.getElementById('employesChart').getContext('2d');
        new Chart(employesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Actifs', 'Inactifs'],
                datasets: [{
                    data: [{{ $employesActifs }}, {{ $employesInactifs }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Graphique par type
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'pie',
            data: {
                labels: ['Entrées', 'Sorties'],
                datasets: [{
                    data: [{{ $pointagesEntree }}, {{ $pointagesSortie }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });

        // Graphique par méthode
        const methodCtx = document.getElementById('methodChart').getContext('2d');
        new Chart(methodCtx, {
            type: 'bar',
            data: {
                labels: @json($pointagesByMethod->pluck('auth_method')),
                datasets: [{
                    label: 'Pointages',
                    data: @json($pointagesByMethod->pluck('count')),
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>