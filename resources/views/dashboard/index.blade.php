{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>

    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <!-- Sélecteur de période -->
        <div class="card shadow-sm mb-2">
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <label for="period" class="form-label small fw-medium mb-1">{{ __('Période') }}</label>
                        <select id="period" name="period" class="form-select" onchange="toggleCustomDates()">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('Cette semaine') }}</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('Ce mois') }}</option>
                            <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>{{ __('Entre deux dates') }}</option>
                        </select>
                    </div>
                    
                    <div id="customDates" class="d-flex gap-3 {{ $period !== 'custom' ? 'd-none' : '' }}">
                        <div>
                            <label for="start_date" class="form-label small fw-medium mb-1">{{ __('Date de début') }}</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="form-control">
                        </div>
                        
                        <div>
                            <label for="end_date" class="form-label small fw-medium mb-1">{{ __('Date de fin') }}</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="form-control">
                        </div>
                    </div>
                    
                    <div class="align-self-end pb-1">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Rechercher') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row g-3 mb-2">
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Employés') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['total_employees'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Sites ou établissements') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['total_companies'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Siège') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['total_sieges'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Pointages') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['pointages_period'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Pointages (Entrée)') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['entries_period'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-6 fw-medium text-dark">{{ __('Pointages (Sortie)') }}</h6>
                        <p class="mt-1 display-10 fw-semibold text-primary mb-0">{{ $kpis['exits_period'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row g-3">

            <!-- Employés par siège -->
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-15 fw-medium text-dark">{{ __('Date d\'ajout sites ou établissements') }}</h6>
                        <canvas id="siegeCreatedAtChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Employés par siège -->
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-15 fw-medium text-dark">{{ __('Employés par siège') }}</h6>
                        <canvas id="employeesBySiegeChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Entreprises par statut -->
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-15 fw-medium text-dark">{{ __('Sites ou établissements') }}</h6>
                        <canvas id="companiesByStatusChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Pointages par jour -->
            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fs-15 fw-medium text-dark">{{ __('Pointages par journée') }}</h6>
                        <canvas id="pointagesByDayChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle des dates personnalisées
        function toggleCustomDates() {
            const periodSelect = document.getElementById('period');
            const customDates = document.getElementById('customDates');
            
            if (periodSelect.value === 'custom') {
                customDates.classList.remove('d-none');
            } else {
                customDates.classList.add('d-none');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Données pour les graphiques
            const employeesByCompany = @json($employeesByCompany);
            const employeesBySiege = @json($employeesBySiege);
            const companiesByStatus = @json($companiesByStatus);
            const pointagesByDay = @json($pointagesByDay);
            const sieges = @json($sieges);
            
            const employeesByDate = @json($employeesByDate);
            
            // Graphique des employés par siège
            const employeesBySiegeCtx = document.getElementById('employeesBySiegeChart').getContext('2d');
            new Chart(employeesBySiegeCtx, {
                type: 'line',
                data: {
                    labels: employeesBySiege.map(item => item.Nom),
                    datasets: [{
                        label: '{{ __("Total employés") }}',
                        data: employeesBySiege.map(item => item.total),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4, // Courbe lisse
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            title: {
                                display: true,
                                text: '{{ __("Nombre d\'employés") }}'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Sièges") }}'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                }
            });
            
            // Graphique des entreprises par statut
            const companiesByStatusCtx = document.getElementById('companiesByStatusChart').getContext('2d');
            new Chart(companiesByStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __("Activé") }}', '{{ __("Désactivé") }}'],
                    datasets: [{
                        data: [companiesByStatus.active, companiesByStatus.inactive],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(255, 99, 132, 0.5)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
            
            // Graphique des pointages par jour
            const pointagesByDayCtx = document.getElementById('pointagesByDayChart').getContext('2d');
            new Chart(pointagesByDayCtx, {
                type: 'line',
                data: {
                    labels: pointagesByDay.map(item => item.period),
                    datasets: [
                        {
                            label: '{{ __("Entrée") }}',
                            data: pointagesByDay.map(item => item.entries),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            fill: true
                        },
                        {
                            label: '{{ __("Sortie") }}',
                            data: pointagesByDay.map(item => item.exits),
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.1)',
                            fill: true
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graphique des sièges par date de création
            const siegesByDate = sieges.reduce((acc, item) => {
            const date = new Date(item.CreatedAt).toLocaleDateString('fr-FR');
                acc[date] = (acc[date] || 0) + 1;
                return acc;
            }, {});

            // Trier les dates
            const sortedDates = Object.keys(siegesByDate).sort((a, b) => {
                return new Date(a.split('/').reverse().join('-')) - new Date(b.split('/').reverse().join('-'));
            });

            const siegeCreatedAt = document.getElementById('siegeCreatedAtChart').getContext('2d');
            new Chart(siegeCreatedAt, {
                type: 'line',
                data: {
                    labels: sortedDates,
                    datasets: [
                        {
                            label: '{{ __("Nombre de sièges créés") }}',
                            data: sortedDates.map(date => siegesByDate[date]),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.1)',
                            fill: true,
                            tension: 0.4 // Courbe lisse
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>