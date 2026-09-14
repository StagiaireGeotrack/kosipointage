{{-- resources/views/master/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark">
                {{ __('Modifier le Master') }}
            </h2>
            <a href="{{ route('master.show') }}" class="btn btn-secondary">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('master.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Identifiant_email -->
                    <div class="mb-3">
                        <x-input-label for="Identifiant_email" :value="__('Identifiant ou E-mail')" />
                        <x-text-input id="Identifiant_email" class="form-control mt-1" type="email"
                            name="Identifiant_email"
                            :value="old('Identifiant_email', $master->Identifiant_email)" required />
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

                    <!-- Actived -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="Actived" type="checkbox" name="Actived" value="1"
                                {{ old('Actived', $master->Actived) ? 'checked' : '' }}
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