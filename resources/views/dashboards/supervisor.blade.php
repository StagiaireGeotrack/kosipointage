<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Tableau de bord - Responsable de service') }}</h2>
    </x-slot>

    <div class="p-2">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-primary">
                    <div class="card-body">
                        <div class="text-muted small">{{ __('Employés de mes services') }}</div>
                        <h3 class="fw-bold mb-0">{{ $totalEmployes }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-success">
                    <div class="card-body">
                        <div class="text-muted small">{{ __('Actifs') }}</div>
                        <h3 class="fw-bold mb-0">{{ $employesActifs }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-info">
                    <div class="card-body">
                        <div class="text-muted small">{{ __('Présents aujourd\'hui') }}</div>
                        <h3 class="fw-bold mb-0">{{ $employesPresentsToday }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="text-muted small">{{ __('Taux de présence') }}</div>
                        <h3 class="fw-bold mb-0">{{ $tauxPresence }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="bi bi-building-check"></i> {{ __('Mes services') }}</h6>
            </div>
            <div class="card-body">
                @if($services->isEmpty())
                    <div class="alert alert-warning mb-0">
                        {{ __('Aucun service affecté. Contactez votre administrateur.') }}
                    </div>
                @else
                    <ul class="mb-0">
                        @foreach($services as $service)
                            <li>
                                <strong>{{ $service->name }}</strong>
                                @if($service->code)<small class="text-muted">({{ $service->code }})</small>@endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>