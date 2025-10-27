{{-- resources/views/sieges/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ $siege->Nom }}
            </h2>
            <div class="d-flex gap-2">
                @can('superadmin')
                    <a href="{{ route('sieges.edit', $siege->ID) }}" class="btn btn-primary">
                        {{ __('app.edit') }}
                    </a>
                @endcan
                <a href="{{ route('sieges.index') }}" class="btn btn-secondary">
                    {{ __('app.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <!-- Informations du siège -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="fs-5 fw-semibold mb-3">{{ __('app.office_details') }}</h3>
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <p class="small text-muted mb-1">{{ __('app.name') }}</p>
                            <p class="fw-medium mb-0">{{ $siege->Nom }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="small text-muted mb-1">{{ __('app.location') }}</p>
                            <p class="fw-medium mb-0">{{ $siege->Nom_Lieu_Ville ?: __('app.not_specified') }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="small text-muted mb-1">{{ __('app.status') }}</p>
                            <p class="fw-medium mb-0">
                                @if ($siege->Actived)
                                    <span class="badge bg-success">
                                        {{ __('app.active') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        {{ __('app.inactive') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p class="small text-muted mb-1">{{ __('app.id') }}</p>
                            <p class="fw-medium mb-0">{{ $siege->ID }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Entreprises associées -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fs-5 fw-semibold mb-0">{{ __('app.companies') }}</h3>
                        <a href="{{ route('entreprises.create', ['SiegeID' => $siege->ID]) }}" class="btn btn-primary btn-sm">
                            {{ __('app.add_company') }}
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.name') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.location') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.status') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($entreprises as $entreprise)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $entreprise->Nom }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $entreprise->Nom_Lieu_Ville }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($entreprise->Actived)
                                                <span class="badge bg-success">
                                                    {{ __('app.active') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    {{ __('app.inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('entreprises.show', $entreprise->ID) }}" class="text-primary" title="Voir">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            {{ __('app.no_companies') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($entreprises->hasPages())
                        <div class="mt-3">
                            {{ $entreprises->links() }}
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('entreprises.index', ['SiegeID' => $siege->ID]) }}" class="text-primary text-decoration-none">
                            {{ __('app.view_all_companies') }}
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Employés associés -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fs-5 fw-semibold mb-0">{{ __('app.employees') }}</h3>
                        <a href="{{ route('employes.create', ['SiegeID' => $siege->ID]) }}" class="btn btn-primary btn-sm">
                            {{ __('app.add_employee') }}
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.name') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.badge_id') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.status') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employes as $employe)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $employe->Nom }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $employe->BadgeID }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($employe->Actived)
                                                <span class="badge bg-success">
                                                    {{ __('app.active') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    {{ __('app.inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('employes.show', $employe->ID) }}" class="text-primary" title="Voir">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            {{ __('app.no_employees') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($employes->hasPages())
                        <div class="mt-3">
                            {{ $employes->links() }}
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('employes.index', ['SiegeID' => $siege->ID]) }}" class="text-primary text-decoration-none">
                            {{ __('app.view_all_employees') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>