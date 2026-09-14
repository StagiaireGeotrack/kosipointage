{{-- resources/views/supervisors/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark">
                {{ __('Modifier le responsable de service') }}
            </h2>
            <a href="{{ route('supervisors.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('supervisors.update', $supervisor->ID) }}">
                    @csrf
                    @method('PUT')

                    <!-- Identifiant_email -->
                    <div class="mb-3">
                        <x-input-label for="Identifiant_email" :value="__('Identifiant ou E-mail')" />
                        <x-text-input id="Identifiant_email" class="form-control mt-1" type="email"
                            name="Identifiant_email"
                            :value="old('Identifiant_email', $supervisor->Identifiant_email)" required />
                        <x-input-error :messages="$errors->get('Identifiant_email')" class="mt-2" />
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Nouveau mot de passe')" />
                        <x-text-input id="password" class="form-control mt-1" type="password"
                            name="password" autocomplete="new-password" />
                        <small class="text-muted">{{ __('Laisser vide pour conserver le mot de passe actuel') }}</small>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="password_confirmation" class="form-control mt-1" type="password"
                            name="password_confirmation" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Services -->
                    <div class="mb-3">
                        <x-input-label :value="__('Services affectés')" />
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @forelse($services as $service)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="service_ids[]"
                                        id="service_{{ $service->id }}"
                                        value="{{ $service->id }}"
                                        {{ in_array($service->id, old('service_ids', $assignedServiceIds)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                        {{ $service->name }}
                                        @if($service->code)
                                            <small class="text-muted">({{ $service->code }})</small>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted mb-0">
                                    {{ __('Aucun service disponible pour votre entreprise.') }}
                                </p>
                            @endforelse
                        </div>
                        <small class="text-muted">{{ __('Sélectionnez au moins un service.') }}</small>
                        <x-input-error :messages="$errors->get('service_ids')" class="mt-2" />
                    </div>

                    <!-- Actived -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="Actived" type="checkbox" name="Actived" value="1"
                                {{ old('Actived', $supervisor->Actived) ? 'checked' : '' }}
                                class="form-check-input">
                            <label for="Actived" class="form-check-label">{{ __('Activer le compte') }}</label>
                        </div>
                        <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                    </div>

                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <x-primary-button class="btn btn-primary">
                            {{ __('MODIFIER') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>