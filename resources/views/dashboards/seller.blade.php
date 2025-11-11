{{-- resources/views/dashboards/seller.blade.php --}}
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
    </style>
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Tableau de bord Vendeur') }}
            </h2>
            <div class="text-muted">
                <i class="bi bi-calendar3"></i> {{ Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        {{-- Statistiques principales --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4">
                <div class="stats-card blue">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Sièges') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $totalSieges }}</h2>
                        </div>
                        <i class="bi bi-building fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card green">
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
                <div class="stats-card orange">
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
                <div class="stats-card purple">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Employés Actifs') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $employesActifs }}</h2>
                            <small class="opacity-75">{{ round(($employesActifs / max($totalEmployes, 1)) * 100, 1) }}%</small>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card red">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Ent. Actives') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $entreprisesActives }}</h2>
                            <small class="opacity-75">{{ round(($entreprisesActives / max($totalEntreprises, 1)) * 100, 1) }}%</small>
                        </div>
                        <i class="bi bi-check2-square fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <div class="stats-card teal">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Cette Semaine') }}</div>
                            <h2 class="fw-bold mt-2 mb-0">{{ $employesCetteSemaine }}</h2>
                            <small class="opacity-75">{{ __('nouveaux employés') }}</small>
                        </div>
                        <i class="bi bi-calendar-week fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 5 Sièges --}}
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-trophy text-warning"></i> {{ __('Sièges') }}
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center text-dark">#</th>
                                    <th class="text-dark">{{ __('Siège') }}</th>
                                    <th class="text-dark">{{ __('Localisation') }}</th>
                                    <th class="text-center text-dark">{{ __('Employés') }}</th>
                                    <th class="text-center text-dark">{{ __('Entreprises') }}</th>
                                    <th class="text-center text-dark">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSieges as $index => $siege)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge {{ $index == 0 ? 'bg-warning' : ($index == 1 ? 'bg-secondary' : 'bg-info') }}">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">{{ $siege->Nom }}</td>
                                    <td>
                                        <i class="bi bi-geo-alt text-muted"></i> {{ $siege->Nom_Lieu_Ville }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $siege->employes_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $siege->entreprises_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sieges.show', $siege->ID) }}" class="btn btn-sm btn-outline-primary">
                                            <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques d'évolution --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-graph-up text-success"></i> {{ __('Évolution Totale des Employés (12 mois)') }}
                    </h5>
                    <canvas id="employesEvolutionChart" height="280"></canvas>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-graph-up text-primary"></i> {{ __('Évolution Totale des Entreprises (12 mois)') }}
                    </h5>
                    <canvas id="entreprisesEvolutionChart" height="280"></canvas>
                </div>
            </div>
        </div>

        {{-- Graphiques d'ajouts mensuels --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart text-info"></i> {{ __('Employés Ajoutés par Mois (6 derniers mois)') }}
                    </h5>
                    <canvas id="employesParMoisChart" height="280"></canvas>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart text-warning"></i> {{ __('Entreprises Ajoutées par Mois (6 derniers mois)') }}
                    </h5>
                    <canvas id="entreprisesParMoisChart" height="280"></canvas>
                </div>
            </div>
        </div>

        {{-- Répartitions --}}
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-pie-chart text-info"></i> {{ __('Répartition des Employés par Siège') }}
                    </h5>
                    <canvas id="employesBySiegeChart" height="280"></canvas>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-pie-chart text-warning"></i> {{ __('Répartition des Entreprises par Siège') }}
                    </h5>
                    <canvas id="entreprisesBySiegeChart" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            
            const colors = {
                primary: 'rgba(59, 130, 246, 0.8)',
                success: 'rgba(16, 185, 129, 0.8)',
                warning: 'rgba(245, 158, 11, 0.8)',
                info: 'rgba(99, 102, 241, 0.8)',
            };
            
            // Évolution totale employés
            new Chart(document.getElementById('employesEvolutionChart'), {
                type: 'line',
                data: {
                    labels: @json(collect($employesEvolution)->pluck('month')),
                    datasets: [{
                        label: 'Total Employés',
                        data: @json(collect($employesEvolution)->pluck('count')),
                        borderColor: colors.success,
                        backgroundColor: colors.success.replace('0.8', '0.2'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Évolution totale entreprises
            new Chart(document.getElementById('entreprisesEvolutionChart'), {
                type: 'line',
                data: {
                    labels: @json(collect($entreprisesEvolution)->pluck('month')),
                    datasets: [{
                        label: 'Total Entreprises',
                        data: @json(collect($entreprisesEvolution)->pluck('count')),
                        borderColor: colors.primary,
                        backgroundColor: colors.primary.replace('0.8', '0.2'),
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Employés ajoutés par mois
            new Chart(document.getElementById('employesParMoisChart'), {
                type: 'bar',
                data: {
                    labels: @json(collect($employesParMois)->pluck('month')),
                    datasets: [{
                        label: 'Employés Ajoutés',
                        data: @json(collect($employesParMois)->pluck('count')),
                        backgroundColor: colors.info,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Entreprises ajoutées par mois
            new Chart(document.getElementById('entreprisesParMoisChart'), {
                type: 'bar',
                data: {
                    labels: @json(collect($entreprisesParMois)->pluck('month')),
                    datasets: [{
                        label: 'Entreprises Ajoutées',
                        data: @json(collect($entreprisesParMois)->pluck('count')),
                        backgroundColor: colors.warning,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
            
            // Répartition employés
            new Chart(document.getElementById('employesBySiegeChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($employesBySiege->pluck('siege')),
                    datasets: [{
                        data: @json($employesBySiege->pluck('total')),
                        backgroundColor: [colors.primary, colors.success, colors.warning, colors.info, 'rgba(239, 68, 68, 0.8)']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
            
            // Répartition entreprises
            new Chart(document.getElementById('entreprisesBySiegeChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($entreprisesBySiege->pluck('siege')),
                    datasets: [{
                        data: @json($entreprisesBySiege->pluck('total')),
                        backgroundColor: [colors.warning, colors.info, colors.primary, colors.success, 'rgba(239, 68, 68, 0.8)']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>