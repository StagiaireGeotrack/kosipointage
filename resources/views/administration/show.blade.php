<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark">
                {{ __('Détails administrateur') }}
            </h2>
            <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    @if ( auth()->id() !== $administrateur->ID )
                        <div class="d-flex gap-2">
                            <a href="{{ route('administrateurs.edit', $administrateur->ID) }}" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="bg-light p-4 rounded shadow-sm">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Information générale') }}</h3>
                            <dl class="mb-0">
                                <div class="bg-white p-3 rounded mb-2">
                                    <dt class="small fw-medium text-secondary">{{ __('E-mail') }}</dt>
                                    <dd class="mb-0 small text-dark">{{ $administrateur->Identifiant_email }}</dd>
                                </div>
                                <div class="bg-white p-3 rounded mb-2">
                                    <dt class="small fw-medium text-secondary">{{ __('Type') }}</dt>
                                    <dd class="mb-0 small text-dark">
                                        @if ($administrateur->IsSuperAdmin)
                                            <span class="badge bg-primary">
                                                {{ __('Super administrateur') }}
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                {{ __('Administrateur simple') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div class="col-12 col-md-6">
                            <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('Information supplémentaire') }}</h3>
                            <dl class="mb-0">
                                @if ( !$administrateur->IsSuperAdmin)
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('Siège') }}</dt>
                                        @if ($administrateur->siege)                                            
                                        <dd class="mb-0 small text-dark">{{ $administrateur->siege->Nom }}</dd>
                                        @endif
                                    </div>
                                @endif

                                @if ($administrateur->created_at)
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('Date de création') }}</dt>
                                        <dd class="mb-0 small text-dark"><x-local-date-time :datetime="$administrateur->created_at"/></dd>
                                    </div>
                                @endif

                                @if ($administrateur->updated_at)
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('Dernière mise à jour') }}</dt>
                                        <dd class="mb-0 small text-dark"><x-local-date-time :datetime="$administrateur->updated_at"/></dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>