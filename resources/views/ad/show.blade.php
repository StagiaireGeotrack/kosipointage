<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('app.admin_details') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary d-inline-flex align-items-center">
                            <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('app.back') }}
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('administrateurs.edit', $administrateur->ID) }}" class="btn btn-primary d-inline-flex align-items-center">
                                <svg class="me-2" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                {{ __('app.edit') }}
                            </a>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded shadow-sm">
                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('app.general_information') }}</h3>
                                <dl class="mb-0">
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.id') }}</dt>
                                        <dd class="mb-0 small text-dark">{{ $administrateur->ID }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.email') }}</dt>
                                        <dd class="mb-0 small text-dark">{{ $administrateur->Identifiant_email }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.admin_type') }}</dt>
                                        <dd class="mb-0 small text-dark">
                                            @if ($administrateur->IsSuperAdmin)
                                                <span class="badge bg-primary">
                                                    {{ __('app.super_admin') }}
                                                </span>
                                            @else
                                                <span class="badge bg-info">
                                                    {{ __('app.standard_admin') }}
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="col-12 col-md-6">
                                <h3 class="fs-5 fw-medium text-dark mb-3">{{ __('app.additional_information') }}</h3>
                                <dl class="mb-0">
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.office') }}</dt>
                                        <dd class="mb-0 small text-dark">{{ $administrateur->siege ? $administrateur->siege->Nom : 'N/A' }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.created_at') }}</dt>
                                        <dd class="mb-0 small text-dark">{{ $administrateur->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded mb-2">
                                        <dt class="small fw-medium text-secondary">{{ __('app.last_updated') }}</dt>
                                        <dd class="mb-0 small text-dark">{{ $administrateur->updated_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>