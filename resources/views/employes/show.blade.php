<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('app.employee_details') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <a href="{{ route('employes.index') }}" class="btn btn-secondary d-inline-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('app.back') }}
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('employes.edit', $employe->ID) }}" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('app.edit') }}
                            </a>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <div class="bg-light p-4 rounded shadow-sm text-center">
                                @if ($employe->HasFaceSetup)
                                    <img src="{{ route('employes.face', $employe->ID) }}" alt="{{ $employe->Nom }}" class="rounded-circle mx-auto d-block" style="width: 192px; height: 192px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center" style="width: 192px; height: 192px;">
                                        <span class="display-1 text-white">{{ substr($employe->Nom, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h3 class="fs-4 fw-semibold mt-3">{{ $employe->Nom }}</h3>
                                <p class="text-muted">{{ $employe->BadgeID }}</p>

                                <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                    @if ($employe->HasBiometricSetup)
                                        <span class="badge bg-info">
                                            {{ __('app.biometric') }}
                                        </span>
                                    @endif
                                    
                                    @if ($employe->HasFaceSetup)
                                        <span class="badge bg-primary">
                                            {{ __('app.face') }}
                                        </span>
                                    @endif
                                    
                                    @if ($employe->Pin)
                                        <span class="badge bg-warning text-dark">
                                            {{ __('app.pin') }}
                                        </span>
                                    @endif

                                    @if ($employe->Actived)
                                        <span class="badge bg-success">
                                            {{ __('app.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('app.inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-8">
                            <div class="bg-light p-4 rounded shadow-sm">
                                <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('app.employee_information') }}</h3>
                                
                                <dl class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('app.id') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->ID }}</dd>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('app.office') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->siege->Nom }}</dd>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('app.created_at') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->CreatedAt->format('d/m/Y H:i') }}</dd>
                                        </div>
                                    </div>
                                </dl>
                                
                                <h3 class="fs-5 fw-medium text-dark mt-4 mb-3">{{ __('app.recent_clock_ins') }}</h3>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('app.date') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('app.type') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('app.auth_method') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('app.actions') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pointages as $pointage)
                                                <tr>
                                                    <td class="align-middle small">
                                                        {{ $pointage->timestamp_->format('d/m/Y H:i:s') }}
                                                    </td>
                                                    <td class="align-middle">
                                                        @if ($pointage->type_ == 'entry')
                                                            <span class="badge bg-success">
                                                                {{ __('app.entry') }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger">
                                                                {{ __('app.exit') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @switch($pointage->auth_method)
                                                            @case('badge')
                                                                <span class="badge bg-info">
                                                                    {{ __('app.badge') }}
                                                                </span>
                                                                @break
                                                            @case('face')
                                                                <span class="badge bg-primary">
                                                                    {{ __('app.face') }}
                                                                </span>
                                                                @break
                                                            @case('pin')
                                                                <span class="badge bg-warning text-dark">
                                                                    {{ __('app.pin') }}
                                                                </span>
                                                                @break
                                                            @case('admin')
                                                                <span class="badge bg-secondary">
                                                                    {{ __('app.admin') }}
                                                                </span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('pointages.show', $pointage->ID) }}" class="text-primary text-decoration-none">
                                                            {{ __('app.view_details') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        {{ __('app.no_records') }}
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
        </div>
    </div>
</x-app-layout>