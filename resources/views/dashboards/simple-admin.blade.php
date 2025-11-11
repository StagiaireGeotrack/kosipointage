{{-- resources/views/dashboards/simple-admin.blade.php --}}
<x-app-layout>
    @push('styles')
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stats-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card.purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card.red { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stats-card.teal { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .badge-new {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Tableau de bord') }} - {{ $siege->Nom }}
            </h2>
            <div class="text-muted">
                <i class="bi bi-calendar3"></i> {{ Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        {{-- Formulaire de filtres --}}
        <div class="filter-card">
            <form method="GET" action="{{ route('dashboard.simple-admin') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="period" class="form-label small fw-medium">{{ __('Période pour Pointages') }}</label>
                    <select name="period" id="period" class="form-select" onchange="toggleCustomDates()">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>{{ __('Aujourd\'hui') }}</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('Cette semaine') }}</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('Ce mois') }}</option>
                        <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>{{ __('Personnalisée') }}</option>
                    </select>
                </div>
                
                <div id="customDates" class="col-md-6 {{ $period !== 'custom' ? 'd-none' : '' }}">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label small fw-medium">{{ __('Date début') }}</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $filterStartDate ? $filterStartDate->format('Y-m-d') : '' }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label small fw-medium">{{ __('Date fin') }}</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $filterEndDate ? $filterEndDate->format('Y-m-d') : '' }}" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> {{ __('Filtrer') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Statistiques principales --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4">
                <div class="stats-card blue">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Entreprises') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalEntreprises }}</h2>
                            @if($entreprisesCeMois > 0)
                                <small class="badge-new mt-2">+{{ $entreprisesCeMois }} ce mois</small>
                            @endif
                        </div>
                        <i class="bi bi-shop fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card green">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Employés') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalEmployes }}</h2>
                            @if($employesCeMois > 0)
                                <small class="badge-new mt-2">+{{ $employesCeMois }} ce mois</small>
                            @endif
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card orange">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Période filtrée') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $pointagesPeriode }}</h2>
                            <small class="opacity-75">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-filter fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card purple">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Cette semaine') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $pointagesWeek }}</h2>
                            <small class="opacity-75">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-calendar-week fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card red">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Ce mois') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $pointagesMonth }}</h2>
                            <small class="opacity-75">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-calendar3 fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card teal">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Taux présence') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $tauxPresence }}%</h2>
                            <small class="opacity-75">{{ $employesPresentsToday }}/{{ $totalEmployes }}</small>
                        </div>
                        <i class="bi bi-person-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques principaux --}}
        <div class="row g-3 mb-3">
            <div class="col-lg-12">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-graph-up text-primary"></i> {{ __('Évolution des Pointages (30 derniers jours)') }}
                    </h6>
                    <canvas id="pointagesEvolutionChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-12">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-clock text-info"></i> {{ __('Pointages par Heure (Aujourd\'hui)') }}
                    </h6>
                    <canvas id="pointagesByHourChart" height="130"></canvas>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-arrow-left-right text-success"></i> {{ __('Type de Pointages') }}
                    </h6>
                    <canvas id="typeChart" height="180"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-person-check text-info"></i> {{ __('Statut Employés') }}
                    </h6>
                    <canvas id="employesChart" height="180"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-fingerprint text-primary"></i> {{ __('Méthodes Auth.') }}
                    </h6>
                    <canvas id="methodChart" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- Top 10 employés --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-award text-warning"></i> {{ __('Employés (Ce mois)') }}
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center text-dark">#</th>
                                    <th class="text-center text-dark">{{ __('Employé') }}</th>
                                    <th  class="text-center text-dark">{{ __('Total Pointages') }}</th>
                                    <th class="text-center text-dark">{{ __('Progression') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topEmployes as $index => $emp)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-secondary' : 'bg-info') }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $emp['name'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $emp['total'] }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 18px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ ($emp['total'] / max($topEmployes->first()['total'], 1)) * 100 }}%">
                                                {{ round(($emp['total'] / max($topEmployes->first()['total'], 1)) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        {{ __('Aucun pointage ce mois') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleCustomDates() {
            const period = document.getElementById('period').value;
            const customDates = document.getElementById('customDates');
            
            if (period === 'custom') {
                customDates.classList.remove('d-none');
            } else {
                customDates.classList.add('d-none');
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 11;
            
            const colors = {
                primary: 'rgba(59, 130, 246, 0.8)',
                success: 'rgba(16, 185, 129, 0.8)',
                warning: 'rgba(245, 158, 11, 0.8)',
                danger: 'rgba(239, 68, 68, 0.8)',
                info: 'rgba(99, 102, 241, 0.8)',
            };
            
            // Évolution pointages 30 jours
            new Chart(document.getElementById('pointagesEvolutionChart'), {
                type: 'line',
                data: {
                    labels: @json($datesLast30Days),
                    datasets: [{
                        label: 'Pointages',
                        data: @json($pointagesLast30Days),
                        backgroundColor: colors.primary.replace('0.8', '0.2'),
                        borderColor: colors.primary,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Pointages par heure
            new Chart(document.getElementById('pointagesByHourChart'), {
                type: 'bar',
                data: {
                    labels: @json(collect($pointagesParHeure)->pluck('hour')),
                    datasets: [{
                        label: 'Pointages',
                        data: @json(collect($pointagesParHeure)->pluck('count')),
                        backgroundColor: colors.info,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Type pointages
            new Chart(document.getElementById('typeChart'), {
                type: 'pie',
                data: {
                    labels: ['Entrées', 'Sorties'],
                    datasets: [{
                        data: [{{ $pointagesEntree }}, {{ $pointagesSortie }}],
                        backgroundColor: [colors.success, colors.danger]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { font: { size: 10 } }
                        } 
                    }
                }
            });
            
            // Statut employés
            new Chart(document.getElementById('employesChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Actifs', 'Inactifs'],
                    datasets: [{
                        data: [{{ $employesActifs }}, {{ $employesInactifs }}],
                        backgroundColor: [colors.success, colors.danger]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { font: { size: 10 } }
                        } 
                    }
                }
            });
            
            // Méthodes
            new Chart(document.getElementById('methodChart'), {
                type: 'bar',
                data: {
                    labels: @json($pointagesByMethod->pluck('auth_method')),
                    datasets: [{
                        label: 'Pointages',
                        data: @json($pointagesByMethod->pluck('count')),
                        backgroundColor: colors.primary,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>