{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>    

    @push('styles')
    <style>
        /* ============================================
           KPI CARDS STYLE IMAGE
           ============================================ */
        
        .kpi-card {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            overflow: hidden;
            color: white;
            min-height: 100px;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Couleurs des cards */
        .kpi-card.blue {
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }

        .kpi-card.green {
            --gradient-start: #11998e;
            --gradient-end: #38ef7d;
        }

        .kpi-card.purple {
            --gradient-start: #8b5cf6;
            --gradient-end: #6d28d9;
        }

        .kpi-card.orange {
            --gradient-start: #f59e0b;
            --gradient-end: #fb923c;
        }

        .kpi-card.pink {
            --gradient-start: #ec4899;
            --gradient-end: #f472b6;
        }

        .kpi-card.teal {
            --gradient-start: #14b8a6;
            --gradient-end: #06b6d4;
        }

        .kpi-card .card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Titre en haut */
        .kpi-card .kpi-title {
            font-size: 0.8rem;
            font-weight: 600;
            opacity: 0.95;
            margin-bottom: 0.5rem;
            text-transform: capitalize;
        }

        /* Valeur principale au centre */
        .kpi-card .kpi-value {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            margin: 0.5rem 0;
        }

        /* Info secondaire en bas */
        .kpi-card .kpi-info {
            font-size: 0.75rem;
            font-weight: 500;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        /* Cards des graphiques */
        .chart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .chart-card .card-body {
            padding: 1rem;
        }

        /* Titres des graphiques avec couleurs */
        .chart-title {
            padding: 0.75rem 1rem;
            margin: -1rem -1rem 1rem -1rem;
            border-bottom: 2px solid;
            font-weight: 700;
            color: white;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-title.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #1e40af;
        }

        .chart-title.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #047857;
        }

        .chart-title.orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #b45309;
        }

        .chart-title.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            border-color: #5b21b6;
        }

        /* Conteneur du canvas */
        .chart-container {
            position: relative;
            padding: 0.75rem 0;
        }

        /* Grand graphique */
        .chart-card-large {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .chart-card-large .card-body {
            padding: 1.25rem;
        }

        .chart-card-large .chart-title {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-color: #4338ca;
            font-size: 0.95rem;
            padding: 1rem 1.25rem;
            margin: -1.25rem -1.25rem 1.25rem -1.25rem;
        }

        /* Formulaire de filtres stylisé */
        .filter-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filter-card .card-body {
            padding: 1rem;
        }

        .filter-card label {
            color: #475569;
            font-weight: 600;
        }

        .filter-card .form-select,
        .filter-card .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .filter-card .form-select:focus,
        .filter-card .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-card .btn-primary {
            padding: 0.5rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .filter-card .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
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

        .kpi-card, .chart-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Délai d'animation */
        .kpi-card:nth-child(1) { animation-delay: 0.05s; }
        .kpi-card:nth-child(2) { animation-delay: 0.1s; }
        .kpi-card:nth-child(3) { animation-delay: 0.15s; }
        .kpi-card:nth-child(4) { animation-delay: 0.2s; }
        .kpi-card:nth-child(5) { animation-delay: 0.25s; }
        .kpi-card:nth-child(6) { animation-delay: 0.3s; }

        .chart-card:nth-child(1) { animation-delay: 0.1s; }
        .chart-card:nth-child(2) { animation-delay: 0.2s; }
        .chart-card:nth-child(3) { animation-delay: 0.3s; }
        .chart-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <!-- Sélecteur de période stylisé -->
        <div class="card filter-card shadow-sm mb-2">
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div>
                                <label for="siege" class="form-label small mb-1">{{ __('Siège') }}</label>
                                <select id="siege" name="siege" class="form-select">
                                    <option value="">Séléctionner un siège</option>
                                    @foreach($sieges_ as $siege)
                                        <option value="{{ $siege->ID }}" {{ $siege_filter == $siege->ID ? 'selected' : '' }}>
                                            {{ $siege->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">                        
                            <label for="period" class="form-label small mb-1">{{ __('Période') }}</label>
                            <select id="period" name="period" class="form-select" onchange="toggleCustomDates()">
                                <option value="day" {{ $period == 'day' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('Cette semaine') }}</option>
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('Ce mois') }}</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>{{ __('Entre deux dates') }}</option>
                            </select>
                        </div>
                        <div id="customDates"  class="col-md-4 d-flex gap-2 {{ $period !== 'custom' ? 'd-none' : '' }}">
                            <div>
                                <label for="start_date" class="form-label small mb-1">{{ __('Date de début') }}</label>
                                <input type="date" id="start_date" name="start_date" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                            
                            <div>
                                <label for="end_date" class="form-label small mb-1">{{ __('Date de fin') }}</label>
                                <input type="date" id="end_date" name="end_date" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="align-self-end mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> {{ __('Valider') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- KPI Cards style image -->
        <div class="row g-2 mb-2">
            <div class="col-md-2">
                <div class="kpi-card purple">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Sites ou établissements') }}</div>
                        <div class="kpi-value">{{ $kpis['total_companies'] }}</div>
                        <div class="kpi-info">{{ __('Total') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="kpi-card purple">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Sites ou établissements') }}</div>
                        <div class="kpi-value">{{ $kpis['total_companies'] }}</div>
                        @php
                            $actifs = \App\Models\Entreprise::where('Actived', 1)->count();
                            $inactifs = \App\Models\Entreprise::where('Actived', 0)->count();
                        @endphp
                        <div class="kpi-info">{{ $actifs }} {{ __('actifs') }} / {{ $inactifs }} {{ __('inactifs') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="kpi-card green">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Employés') }}</div>
                        <div class="kpi-value">{{ $kpis['total_employees'] }}</div>
                        <div class="kpi-info">{{ __('Total') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="kpi-card green">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Employés') }}</div>
                        <div class="kpi-value">{{ $kpis['total_employees'] }}</div>
                        @php
                            $empActifs = \App\Models\Employe::where('Actived', 1)->count();
                            $empInactifs = \App\Models\Employe::where('Actived', 0)->count();
                        @endphp
                        <div class="kpi-info">{{ $empActifs }} {{ __('actifs') }} / {{ $empInactifs }} {{ __('inactifs') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="kpi-card pink">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Pointages') }}</div>
                        <div class="kpi-value">{{ $kpis['pointages_period'] }}</div>
                        <div class="kpi-info">{{ __('Total') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="kpi-card orange">
                    <div class="card-body">
                        <div class="kpi-title">{{ __('Pointages Entrée / Sortie') }}</div>
                        <div class="kpi-value">{{ $kpis['pointages_period'] }}</div>
                        <div class="kpi-info">{{ "Entrée " . $kpis['entries_period'] . " / Sortie " . $kpis['exits_period'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grand graphique -->
        <div class="row g-2 mb-2">
            <div class="col-lg-12">
                <div class="chart-card-large">
                    <div class="card-body">
                        <h6 class="chart-title">
                            <i class="bi bi-graph-up"></i>
                            {{ __('Pointages par journée') }}
                        </h6>
                        <div class="chart-container">
                            <canvas id="pointagesByDayChart" height="45"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques en 4 colonnes -->
        <div class="row g-2">
            <!-- Employés par date -->
            <div class="col-lg-3">
                <div class="chart-card">
                    <div class="card-body">
                        <h6 class="chart-title green">
                            <i class="bi bi-calendar-plus"></i>
                            {{ __('Ajout employés') }}
                        </h6>
                        <div class="chart-container">
                            <canvas id="employeeCreatedAtChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sites par date -->
            <div class="col-lg-3">
                <div class="chart-card">
                    <div class="card-body">
                        <h6 class="chart-title orange">
                            <i class="bi bi-calendar-check"></i>
                            {{ __('Ajout sites') }}
                        </h6>
                        <div class="chart-container">
                            <canvas id="siegeCreatedAtChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Employés par siège -->
            <div class="col-lg-3">
                <div class="chart-card">
                    <div class="card-body">
                        <h6 class="chart-title blue">
                            <i class="bi bi-diagram-3"></i>
                            {{ __('Par siège') }}
                        </h6>
                        <div class="chart-container">
                            <canvas id="employeesBySiegeChart" height="220"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statuts -->
            <div class="col-lg-3">
                <div class="chart-card">
                    <div class="card-body">
                        <h6 class="chart-title purple">
                            <i class="bi bi-pie-chart"></i>
                            {{ __('Statuts') }}
                        </h6>
                        <div class="chart-container">
                            <canvas id="companiesByStatusChart" height="220"></canvas>
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
            // Configuration globale
            Chart.defaults.font.family = "'Inter', 'Segoe UI', 'Roboto', sans-serif";
            Chart.defaults.font.size = 10;
            Chart.defaults.plugins.legend.display = true;
            Chart.defaults.plugins.legend.position = 'bottom';
            Chart.defaults.plugins.legend.labels.padding = 10;
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.font = {
                size: 10,
                weight: '600'
            };

            // Options communes
            const commonTooltipOptions = {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#3b82f6',
                borderWidth: 2,
                padding: 10,
                displayColors: true,
                cornerRadius: 8,
                titleFont: { size: 12, weight: 'bold' },
                bodyFont: { size: 11 }
            };

            const commonAnimationOptions = {
                duration: 1200,
                easing: 'easeInOutQuart'
            };

            // Données
            const employeesByCompany = @json($employeesByCompany);
            const employeesBySiege = @json($employeesBySiege);
            const companiesByStatus = @json($companiesByStatus);
            const pointagesByDay = @json($pointagesByDay);
            const sieges = @json($sieges);            
            const employeesByDate = @json($employeesByDate);

            // Graphique employés par date
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
                        label: '{{ __("Total employés") }}',
                        data: sortedEmployeeDates.map(date => employeesGroupedByDate[date]),
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 9, weight: '600' } },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: { maxRotation: 45, minRotation: 45, font: { size: 9 } },
                            grid: { display: false }
                        }
                    }
                }
            });
            
            // Graphique employés par siège
            const employeesBySiegeCtx = document.getElementById('employeesBySiegeChart').getContext('2d');
            
            function getInitials(name) {
                return name.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
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
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                                    return siegeNames[context[0].dataIndex];
                                },
                                label: function(context) {
                                    return '{{ __("Employés") }}: ' + context.parsed.y;
                                }
                            }
                        },
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 9, weight: '600' } },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: { maxRotation: 45, minRotation: 45, font: { size: 9 } },
                            grid: { display: false }
                        }
                    }
                }
            });
            
            // Graphique statuts
            const companiesByStatusCtx = document.getElementById('companiesByStatusChart').getContext('2d');
            new Chart(companiesByStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ __("Activé") }}', '{{ __("Désactivé") }}'],
                    datasets: [{
                        data: [companiesByStatus.active, companiesByStatus.inactive],
                        backgroundColor: ['rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                        borderColor: ['rgba(16, 185, 129, 1)', 'rgba(239, 68, 68, 1)'],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: { animateRotate: true, animateScale: true, duration: 1200 },
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: { display: true, position: 'bottom' }
                    }
                }
            });
            
            // Graphique pointages par jour
            const pointagesByDayCtx = document.getElementById('pointagesByDayChart').getContext('2d');
            
            const maxEntries = Math.max(...pointagesByDay.map(item => item.entries), 0);
            const maxExits = Math.max(...pointagesByDay.map(item => item.exits), 0);
            const maxValue = Math.max(maxEntries, maxExits);
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
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: '{{ __("Sortie") }}',
                            data: pointagesByDay.map(item => item.exits),
                            borderColor: 'rgba(239, 68, 68, 1)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: suggestedMax,
                            ticks: {
                                stepSize: 5,
                                font: { size: 10, weight: '600' },
                                callback: function(value) { return Math.round(value); }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: { maxRotation: 45, minRotation: 45, font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Graphique sièges par date
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
                        label: '{{ __("Sièges créés") }}',
                        data: sortedDates.map(date => siegesByDate[date]),
                        borderColor: 'rgba(245, 158, 11, 1)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    animation: commonAnimationOptions,
                    plugins: {
                        tooltip: commonTooltipOptions,
                        legend: { display: true }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 9, weight: '600' } },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: { maxRotation: 45, minRotation: 45, font: { size: 9 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>