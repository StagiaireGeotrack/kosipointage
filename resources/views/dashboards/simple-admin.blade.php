{{-- resources/views/dashboards/simple-admin.blade.php --}}
<x-app-layout>
    @push('styles')
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }
        
        .stats-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stats-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card.purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card.red { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stats-card.teal { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
        
        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }
        
        .badge-new {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Tableau de bord') }} - {{ $siege->Nom }}
            </h2>
            <div class="text-muted small">
                <i class="bi bi-calendar3"></i> {{ Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        {{-- Statistiques principales --}}
        <div class="row g-2 mb-3">
            <div class="col-lg-2 col-md-4">
                <div class="stats-card blue">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Entreprises') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $totalEntreprises }}</h3>
                            @if($entreprisesCeMois > 0)
                                <small class="badge-new mt-1">+{{ $entreprisesCeMois }}</small>
                            @endif
                        </div>
                        <i class="bi bi-shop fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card green">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Employés') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $totalEmployes }}</h3>
                            @if($employesCeMois > 0)
                                <small class="badge-new mt-1">+{{ $employesCeMois }}</small>
                            @endif
                        </div>
                        <i class="bi bi-people fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card orange">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Aujourd\'hui') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $pointagesToday }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-clock-history fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card purple">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Semaine') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $pointagesWeek }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-calendar-week fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card red">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Ce mois') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $pointagesMonth }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ __('pointages') }}</small>
                        </div>
                        <i class="bi bi-calendar3 fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card teal">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Présence') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $tauxPresence }}%</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">{{ $employesPresentsToday }}/{{ $totalEmployes }}</small>
                        </div>
                        <i class="bi bi-person-check fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques principaux en 2 colonnes --}}
        <div class="row g-2 mb-2">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-graph-up text-primary"></i> {{ __('Évolution Pointages (30 jours)') }}
                    </h6>
                    <canvas id="pointagesEvolutionChart" height="120"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-clock text-info"></i> {{ __('Pointages par Heure (Aujourd\'hui)') }}
                    </h6>
                    <canvas id="pointagesByHourChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts en 3 colonnes --}}
        <div class="row g-2 mb-2">
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-arrow-left-right text-success"></i> {{ __('Type Pointages (Semaine)') }}
                    </h6>
                    <canvas id="typeChart" height="140"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-person-check text-info"></i> {{ __('Statut Employés') }}
                    </h6>
                    <canvas id="employesChart" height="140"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-fingerprint text-primary"></i> {{ __('Méthodes Auth. (Semaine)') }}
                    </h6>
                    <canvas id="methodChart" height="140"></canvas>
                </div>
            </div>
        </div>

        {{-- Top employés --}}
        <div class="row g-2">
            <div class="col-12">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-award text-warning"></i> {{ __('Top 10 Employés (Ce mois)') }}
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('Employé') }}</th>
                                    <th class="text-center">{{ __('Pointages') }}</th>
                                    <th>{{ __('Progression') }}</th>
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
                                    <td class="fw-bold small">{{ $emp['name'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $emp['total'] }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 16px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ ($emp['total'] / max($topEmployes->first()['total'], 1)) * 100 }}%">
                                                <small style="font-size: 0.7rem;">{{ round(($emp['total'] / max($topEmployes->first()['total'], 1)) * 100, 1) }}%</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted small">
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
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 10;
            
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
                        backgroundColor: colors.primary.replace('0.8', '0.1'),
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
                    scales: { 
                        y: { beginAtZero: true, ticks: { font: { size: 9 } } }, 
                        x: { ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 45 } } 
                    }
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
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, ticks: { font: { size: 9 } } }, 
                        x: { ticks: { font: { size: 9 } } } 
                    }
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
                            labels: { font: { size: 9 }, boxWidth: 12, padding: 8 }
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
                            labels: { font: { size: 9 }, boxWidth: 12, padding: 8 }
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
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, ticks: { font: { size: 9 } } }, 
                        x: { ticks: { font: { size: 9 } } } 
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>