<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Détails employé') }}
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
                            {{ __('Retour') }}
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('employes.edit', $employe->ID) }}" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Modifier') }}
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
                                            {{ __('Empreinte') }}
                                        </span>
                                    @endif
                                    
                                    @if ($employe->HasFaceSetup)
                                        <span class="badge bg-primary">
                                            {{ __('Face image') }}
                                        </span>
                                    @endif
                                    
                                    @if ($employe->Pin)
                                        <span class="badge bg-warning text-dark">
                                            {{ __('Code PIN') }}
                                        </span>
                                    @endif

                                    @if ($employe->Actived)
                                        <span class="badge bg-success">
                                            {{ __('Activé') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('Désactivé') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-8">
                            <div class="bg-light p-4 rounded shadow-sm">
                                <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Information') }}</h3>
                                
                                <dl class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('ID') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->ID }}</dd>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('Siège') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->siege->Nom }}</dd>
                                        </div>
                                    </div>
                                    
                                    @if ($employe->CreatedAt)
                                    <div class="col-12 col-md-6">
                                        <div class="bg-white p-3 rounded">
                                            <dt class="small fw-medium text-secondary">{{ __('Date de création') }}</dt>
                                            <dd class="mb-0 small text-dark">{{ $employe->CreatedAt->format('d/m/Y H:i') }}</dd>
                                        </div>
                                    </div>   
                                    @endif
                                </dl>
                                
                                <h3 class="fs-5 fw-medium text-dark mt-4 mb-3">{{ __('Pointages récents') }}</h3>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('Date') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('Type') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('Méthode') }}
                                                </th>
                                                <th class="text-uppercase small fw-semibold text-secondary">
                                                    {{ __('Actions') }}
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
                                                                {{ __('Entrée) }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger">
                                                                {{ __('Sortie') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        @switch($pointage->auth_method)
                                                            @case('badge')
                                                                <span class="badge bg-info">
                                                                    {{ __('Badge') }}
                                                                </span>
                                                                @break
                                                            @case('face')
                                                                <span class="badge bg-primary">
                                                                    {{ __('Face image') }}
                                                                </span>
                                                                @break
                                                            @case('pin')
                                                                <span class="badge bg-warning text-dark">
                                                                    {{ __('PIN') }}
                                                                </span>
                                                                @break
                                                            @case('admin')
                                                                <span class="badge bg-secondary">
                                                                    {{ __('Administrateur') }}
                                                                </span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ route('pointages.show', $pointage->ID) }}" class="text-primary text-decoration-none">
                                                            {{ __('Détails') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        {{ __('Aucun pointage pour le moment') }}
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