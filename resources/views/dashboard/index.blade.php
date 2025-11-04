{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>    

    @push('styles')
    <style>
        /* ============================================
           STYLES POUR LES CHARTS
           ============================================ */
        
        /* Cards des graphiques */
        .chart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 2px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .chart-card .card-body {
            padding: 1.5rem;
        }

        /* Titres des graphiques */
        .chart-title {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 1rem 1.5rem;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            border-bottom: 2px solid #e5e7eb;
            font-weight: 700;
            color: #1f2937;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-title::before {
            content: "📊";
            font-size: 1.2rem;
        }

        /* Conteneur du canvas */
        .chart-container {
            position: relative;
            padding: 1rem 0;
        }

        /* Animation d'entrée */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chart-card {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Délai d'animation pour chaque carte */
        .chart-card:nth-child(1) { animation-delay: 0.1s; }
        .chart-card:nth-child(2) { animation-delay: 0.2s; }
        .chart-card:nth-child(3) { animation-delay: 0.3s; }
        .chart-card:nth-child(4) { animation-delay: 0.4s; }

        /* Grand graphique */
        .chart-card-large {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
        }

        .chart-card-large .card-body {
            padding: 2rem;
        }

        /* KPI Cards avec animation */
        .kpi-card {
            transition: all 0.3s ease;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .kpi-card .card-body {
            text-align: center;
        }

        .kpi-card h6 {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .kpi-card p {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Loader pour les graphiques */
        .chart-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
    @endpush

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
                            <i class="bi bi-search"></i> {{ __('Rechercher') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row g-3 mb-2">
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Employés') }}</h6>
                        <p class="mb-0">{{ $kpis['total_employees'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Sites ou établissements') }}</h6>
                        <p class="mb-0">{{ $kpis['total_companies'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Siège') }}</h6>
                        <p class="mb-0">{{ $kpis['total_sieges'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Pointages') }}</h6>
                        <p class="mb-0">{{ $kpis['pointages_period'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Pointages (Entrée)') }}</h6>
                        <p class="mb-0">{{ $kpis['entries_period'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="card shadow-sm kpi-card">
                    <div class="card-body">
                        <h6>{{ __('Pointages (Sortie)') }}</h6>
                        <p class="mb-0">{{ $kpis['exits_period'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row g-3">
            <!-- Pointages par jour -->
            <div class="col-lg-12">
                <div class="card chart-card-large">
                    <div class="card-body">
                        <h6 class="chart-title">{{ __('Pointages par journée') }}</h6>
                        <div class="chart-container">
                            <canvas id="pointagesByDayChart" height="50"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Graphiques -->
        <div class="row g-3">
            <!-- Employés par date de création -->
            <div class="col-lg-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h6 class="chart-title">{{ __('Date d\'ajout des employés') }}</h6>
                        <div class="chart-container">
                            <canvas id="employeeCreatedAtChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sites par date de création -->
            <div class="col-lg-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h6 class="chart-title">{{ __('Date d\'ajout sites ou établissements') }}</h6>
                        <div class="chart-container">
                            <canvas id="siegeCreatedAtChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employés par siège -->
            <div class="col-lg-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h6 class="chart-title">{{ __('Employés par siège') }}</h6>
                        <div class="chart-container">
                            <canvas id="employeesBySiegeChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Entreprises par statut -->
            <div class="col-lg-3">
                <div class="card chart-card">
                    <div class="card-body">
                        <h6 class="chart-title">{{ __('Sites ou établissements') }}</h6>
                        <div class="chart-container">
                            <canvas id="companiesByStatusChart" height="300"></canvas>
                        </div>
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
            // Configuration globale pour tous les graphiques
            Chart.defaults.font.family = "'Inter', 'Segoe UI', 'Roboto', sans-serif";
            Chart.defaults.plugins.legend.display = true;
            Chart.defaults.plugins.legend.position = 'bottom';
            Chart.defaults.plugins.legend.labels.padding = 15;
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.font = {
                size: 12,
                weight: '600'
            };

            // Options communes pour les tooltips
            const commonTooltipOptions = {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#3b82f6',
                borderWidth: 2,
                padding: 12,
                displayColors: true,
                cornerRadius: 8,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                }
            };

            // Options communes pour les animations
            const commonAnimationOptions = {
                duration: 1500,
                easing: 'easeInOutQuart'
            };

            // Données pour les graphiques
            const employeesByCompany = @json($employeesByCompany);
            const employeesBySiege = @json($employeesBySiege);
            const companiesByStatus = @json($companiesByStatus);
            const pointagesByDay = @json($pointagesByDay);
            const sieges = @json($sieges);            
            const employeesByDate = @json($employeesByDate);

            // Graphique des employés par date de création
            const employeesGroupedByDate = employeesByDate.reduce((acc, item) => {
                const date = new Date(item.CreatedAt).toLocaleDateString('fr-FR');
                acc[date] = (acc[date] || 0) + 1;
                return acc;
            }, {});

            const sortedEmployeeDates = Object.keys(employeesGroupedByDate).sort((a, b) => {
                return new Date(a.split('/').reverse().join('-')) - new Date(b.split('/').reverse().join('-'));
            });

            const employeeCreatedAt = document.getElementById('employeeCreatedAtChart').getContext('2d');
            new Chart(employeeCreatedAt, {
                type: 'line',
                data: {
                    labels: sortedEmployeeDates,
                    datasets: [{
                        label: '{{ __("Total employés créés") }}',
                        data: sortedEmployeeDates.map(date => employeesGroupedByDate[date]),
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { weight: '600' }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Date de création") }}',
                                font: { weight: '700', size: 13 }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: { weight: '600' }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            
            // Graphique des employés par siège
            const employeesBySiegeCtx = document.getElementById('employeesBySiegeChart').getContext('2d');
            
            function getInitials(name) {
                return name
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase())
                    .join('');
            }

            const siegeNames = employeesBySiege.map(item => item.Nom);
            const siegeInitials = siegeNames.map(name => getInitials(name));
            const siegeTotals = employeesBySiege.map(item => item.total);

            new Chart(employeesBySiegeCtx, {
                type: 'line',
                data: {
                    labels: siegeInitials,
                    datasets: [{
                        label: '{{ __("Total employés") }}',
                        data: siegeTotals,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: {
                            ...commonTooltipOptions,
                            callbacks: {
                                title: function(context) {
                                    const index = context[0].dataIndex;
                                    return siegeNames[index];
                                },
                                label: function(context) {
                                    return '{{ __("Employés") }}: ' + context.parsed.y;
                                }
                            }
                        },
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { weight: '600' }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Sièges") }}',
                                font: { weight: '700', size: 13 }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: { weight: '600' }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
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
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 3,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500
                    },
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Graphique des pointages par jour avec axe Y arrondi
            const pointagesByDayCtx = document.getElementById('pointagesByDayChart').getContext('2d');
            
            // Trouver le max pour arrondir l'axe Y
            const maxEntries = Math.max(...pointagesByDay.map(item => item.entries), 0);
            const maxExits = Math.max(...pointagesByDay.map(item => item.exits), 0);
            const maxValue = Math.max(maxEntries, maxExits);
            
            // Arrondir au multiple de 5 supérieur (ou minimum 10)
            const suggestedMax = maxValue === 0 ? 10 : Math.ceil(maxValue / 5) * 5 + 5;
            
            new Chart(pointagesByDayCtx, {
                type: 'line',
                data: {
                    labels: pointagesByDay.map(item => item.period),
                    datasets: [
                        {
                            label: '{{ __("Entrée") }}',
                            data: pointagesByDay.map(item => item.entries),
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 8
                        },
                        {
                            label: '{{ __("Sortie") }}',
                            data: pointagesByDay.map(item => item.exits),
                            borderColor: 'rgba(239, 68, 68, 1)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: suggestedMax,
                            ticks: {
                                stepSize: 5,
                                font: { weight: '600' },
                                callback: function(value) {
                                    return Math.round(value); // Assurer que les valeurs sont des entiers
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Date de pointage") }}',
                                font: { weight: '700', size: 14 }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: { weight: '600' }
                            },
                            grid: {
                                display: false
                            }
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

            const sortedDates = Object.keys(siegesByDate).sort((a, b) => {
                return new Date(a.split('/').reverse().join('-')) - new Date(b.split('/').reverse().join('-'));
            });

            const siegeCreatedAt = document.getElementById('siegeCreatedAtChart').getContext('2d');
            new Chart(siegeCreatedAt, {
                type: 'line',
                data: {
                    labels: sortedDates,
                    datasets: [{
                        label: '{{ __("Nombre de sièges créés") }}',
                        data: sortedDates.map(date => siegesByDate[date]),
                        borderColor: 'rgba(245, 158, 11, 1)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { weight: '600' }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: '{{ __("Date de création") }}',
                                font: { weight: '700', size: 13 }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: { weight: '600' }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>