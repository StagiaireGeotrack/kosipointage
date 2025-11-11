{{-- resources/views/dashboards/seller.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="p-2">
        {{-- Statistiques générales --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-building fs-1 text-primary mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $totalSieges }}</h3>
                        <p class="text-muted mb-0">{{ __('Sièges') }}</p>
                    </div>
                </div>
            </div>
            
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
                        <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                        <h3 class="fw-bold mb-0">{{ $employesActifs }}</h3>
                        <p class="text-muted mb-0">{{ __('Employés Actifs') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graphiques --}}
        <div class="row g-3 mb-4">
            {{-- Graphique par siège --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('Statistiques par Siège') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="siegesChart"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- Graphique Employés --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">{{ __('Statut des Employés') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="employesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des sièges --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">{{ __('Mes Sièges') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Nom du Siège') }}</th>
                                        <th>{{ __('Localisation') }}</th>
                                        <th>{{ __('Entreprises') }}</th>
                                        <th>{{ __('Employés') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sieges as $siege)
                                        <tr>
                                            <td class="fw-bold">{{ $siege->Nom }}</td>
                                            <td>{{ $siege->Nom_Lieu_Ville }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $siege->entreprises_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $siege->employes_count }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('sieges.show', $siege->ID) }}" class="btn btn-sm btn-primary">
                                                    {{ __('Voir détails') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                {{ __('Aucun siège assigné') }}
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
    </div>

    @push('scripts')
    <script>
        // Graphique par siège
        const siegesCtx = document.getElementById('siegesChart').getContext('2d');
        new Chart(siegesCtx, {
            type: 'bar',
            data: {
                labels: @json($siegesData['labels']),
                datasets: [
                    {
                        label: 'Entreprises',
                        data: @json($siegesData['entreprises']),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Employés',
                        data: @json($siegesData['employes']),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
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
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
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
    </script>
    @endpush
</x-app-layout>