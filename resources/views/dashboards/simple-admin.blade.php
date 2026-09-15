{{-- resources/views/dashboards/simple-admin.blade.php --}}
<x-app-layout>
    @php
        $isSupervisor = auth()->user()->isSupervisor();
    @endphp

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
        .stats-card.green   { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stats-card.blue    { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stats-card.orange  { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stats-card.purple  { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .stats-card.red     { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .stats-card.teal    { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); }
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
                {{ __('Tableau de bord') }}
            </h2>
            <div class="text-muted small">
                <i class="bi bi-calendar3"></i> {{ ucfirst(Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY')) }}
            </div>
        </div>
    </x-slot>

    <div class="p-2">

        {{-- ============================================================ --}}
        {{-- Bandeau contexte Supervisor --}}
        {{-- ============================================================ --}}
        @if($isSupervisor)
        <div class="alert alert-info d-flex align-items-center mb-3 shadow-sm">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div>
                <strong>{{ __('Mode Responsable de service') }}</strong> — {{ __('Vous voyez uniquement les données de vos services affectés.') }}
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- STATISTIQUES PRINCIPALES --}}
        {{-- ============================================================ --}}
        <div class="row g-2 mb-3">

            @unless($isSupervisor)
            {{-- Carte Entreprises : masquée pour Supervisor --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card green">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Sites ou établissements') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $totalEntreprises }}</h3>
                            <small class="badge-new mt-1 text-white">Total</small>
                        </div>
                        <i class="bi bi-shop fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- Carte Entreprises actives : masquée pour Supervisor --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card red">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Établissements actifs') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $entreprisesActives }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">
                                {{ round(($entreprisesActives / max($totalEntreprises, 1)) * 100, 1) }}%
                            </small>
                        </div>
                        <i class="bi bi-check2-square fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
            @endunless

            {{-- Employés --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card orange">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Employés') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $totalEmployes }}</h3>
                            <small class="badge-new mt-1 text-white">
                                {{ $isSupervisor ? 'Mes services' : 'Total' }}
                            </small>
                        </div>
                        <i class="bi bi-people fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- Employés actifs --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card purple">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Employés actifs') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $employesActifs }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">
                                {{ round(($employesActifs / max($totalEmployes, 1)) * 100, 1) }}%
                            </small>
                        </div>
                        <i class="bi bi-check-circle fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- Taux de présence --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card blue">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Taux de présence') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $tauxPresence }}%</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">
                                {{ $employesPresentsToday }} / {{ $totalEmployes }}
                            </small>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- Pointages du jour --}}
            <div class="col-lg-2 col-md-4">
                <div class="stats-card teal">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">{{ __('Pointages aujourd\'hui') }}</div>
                            <h3 class="fw-bold mt-1 mb-0">{{ $pointagesToday }}</h3>
                            <small class="opacity-75" style="font-size: 0.7rem;">
                                Sem. : {{ $pointagesWeek }} · Mois : {{ $pointagesMonth }}
                            </small>
                        </div>
                        <i class="bi bi-clock-history fs-2 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LIGNE 2 : GRAPHIQUES --}}
        {{-- ============================================================ --}}
        <div class="row g-2 mb-3">

            {{-- Pointages 7 derniers jours --}}
            <div class="col-lg-{{ $isSupervisor ? 6 : 4 }}">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-graph-up text-primary"></i> {{ __('Pointages — 7 derniers jours') }}
                    </h6>
                    <canvas id="pointagesLast7DaysChart" height="140"></canvas>
                </div>
            </div>

            @unless($isSupervisor)
            {{-- Pointages 30 derniers jours : pour Simple Admin seulement --}}
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-graph-up text-success"></i> {{ __('Pointages — 30 derniers jours') }}
                    </h6>
                    <canvas id="pointagesLast30DaysChart" height="140"></canvas>
                </div>
            </div>
            @endunless

            {{-- Pointages par méthode --}}
            <div class="col-lg-{{ $isSupervisor ? 6 : 4 }}">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-pie-chart text-warning"></i> {{ __('Pointages par méthode') }}
                    </h6>
                    <canvas id="pointagesByMethodChart" height="140"></canvas>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LIGNE 3 : POINTAGES PAR HEURE + TOP EMPLOYÉS --}}
        {{-- ============================================================ --}}
        <div class="row g-2 mb-3">

            {{-- Pointages par heure (aujourd'hui) --}}
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-bar-chart text-info"></i> {{ __('Répartition horaire — Aujourd\'hui') }}
                    </h6>
                    <canvas id="pointagesParHeureChart" height="120"></canvas>
                </div>
            </div>

            {{-- Top 10 employés --}}
            <div class="col-lg-6">
                <div class="chart-card">
                    <h6 class="fw-bold mb-2 small">
                        <i class="bi bi-trophy text-warning"></i> {{ __('Top 10 employés — Pointages ce mois') }}
                    </h6>
                    <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="text-center" style="width: 40px;">#</th>
                                    <th>{{ __('Employé') }}</th>
                                    <th class="d-none d-md-table-cell">{{ __('Matricule') }}</th>
                                    <th class="text-center">{{ __('Pointages') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topEmployes as $index => $emp)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge {{ $index < 3 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="small fw-semibold">{{ $emp['name'] }}</td>
                                        <td class="small d-none d-md-table-cell">{{ $emp['num_mat'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $emp['total'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            {{ __('Aucune donnée') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- LIGNE 4 : STATS DÉTAILLÉES --}}
        {{-- ============================================================ --}}
        <div class="row g-2">

            {{-- Entrées / Sorties --}}
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3 small">
                        <i class="bi bi-arrow-left-right text-primary"></i> {{ __('Entrées / Sorties (semaine)') }}
                    </h6>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Entrées') }}</div>
                            <h4 class="fw-bold text-success mb-0">{{ $pointagesEntree }}</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Sorties') }}</div>
                            <h4 class="fw-bold text-danger mb-0">{{ $pointagesSortie }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Moyenne pointages / jour --}}
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3 small">
                        <i class="bi bi-calculator text-success"></i> {{ __('Statistiques du mois') }}
                    </h6>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Moy. / jour') }}</div>
                            <h4 class="fw-bold text-primary mb-0">{{ $avgPointagesPerDay }}</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Total mois') }}</div>
                            <h4 class="fw-bold text-info mb-0">{{ $pointagesMonth }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            @unless($isSupervisor)
            {{-- Nouveaux employés / entreprises --}}
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3 small">
                        <i class="bi bi-plus-circle text-warning"></i> {{ __('Nouveautés ce mois') }}
                    </h6>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Employés') }}</div>
                            <h4 class="fw-bold text-primary mb-0">{{ $employesCeMois }}</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-muted small">{{ __('Établissements') }}</div>
                            <h4 class="fw-bold text-warning mb-0">{{ $entreprisesCeMois }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            @endunless

            @if($isSupervisor)
            {{-- Pour le Supervisor : bloc remplacement (Nouveaux employés uniquement) --}}
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3 small">
                        <i class="bi bi-plus-circle text-warning"></i> {{ __('Nouveautés ce mois') }}
                    </h6>
                    <div class="text-center">
                        <div class="text-muted small">{{ __('Nouveaux employés dans mes services') }}</div>
                        <h4 class="fw-bold text-primary mb-0">{{ $employesCeMois }}</h4>
                    </div>
                </div>
            </div>
            @endif

        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- SCRIPTS — Graphiques Chart.js --}}
    {{-- ============================================================ --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 10;

            const colors = {
                primary: 'rgba(59, 130, 246, 0.8)',
                success: 'rgba(16, 185, 129, 0.8)',
                warning: 'rgba(245, 158, 11, 0.8)',
                info:    'rgba(99, 102, 241, 0.8)',
                danger:  'rgba(239, 68, 68, 0.8)',
            };

            /* ------------------------------
               Graphique : pointages 7 jours
            ------------------------------ */
            const ctx7 = document.getElementById('pointagesLast7DaysChart');
            if (ctx7) {
                new Chart(ctx7, {
                    type: 'line',
                    data: {
                        labels: @json($last7Days),
                        datasets: [{
                            label: '{{ __("Pointages") }}',
                            data: @json($pointagesLast7Days),
                            borderColor: colors.primary,
                            backgroundColor: colors.primary.replace('0.8', '0.1'),
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
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
            }

            /* ------------------------------
               Graphique : pointages 30 jours (Simple Admin)
            ------------------------------ */
            const ctx30 = document.getElementById('pointagesLast30DaysChart');
            if (ctx30) {
                new Chart(ctx30, {
                    type: 'line',
                    data: {
                        labels: @json($datesLast30Days),
                        datasets: [{
                            label: '{{ __("Pointages") }}',
                            data: @json($pointagesLast30Days),
                            borderColor: colors.success,
                            backgroundColor: colors.success.replace('0.8', '0.1'),
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
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
            }

            /* ------------------------------
               Graphique : méthode d'authentification
            ------------------------------ */
            const ctxMethod = document.getElementById('pointagesByMethodChart');
            if (ctxMethod) {
                const methodLabels = @json($pointagesByMethod->pluck('auth_method'));
                const methodData   = @json($pointagesByMethod->pluck('count'));

                new Chart(ctxMethod, {
                    type: 'doughnut',
                    data: {
                        labels: methodLabels.length ? methodLabels : ['{{ __("Aucune donnée") }}'],
                        datasets: [{
                            data: methodData.length ? methodData : [1],
                            backgroundColor: [colors.primary, colors.success, colors.warning, colors.info, colors.danger]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 8 }, boxWidth: 10, padding: 8 }
                            }
                        }
                    }
                });
            }

            /* ------------------------------
               Graphique : répartition horaire
            ------------------------------ */
            const ctxHour = document.getElementById('pointagesParHeureChart');
            if (ctxHour) {
                new Chart(ctxHour, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($pointagesParHeure)->pluck('hour')),
                        datasets: [{
                            label: '{{ __("Pointages") }}',
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
            }
        });
    </script>
    @endpush
</x-app-layout>
