<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Créer le Master') }}</h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('master.store') }}">
                    @csrf

                    <div class="mb-3">
                        <x-input-label for="Identifiant_email" :value="__('Identifiant ou Email')" />
                        <x-text-input id="Identifiant_email" name="Identifiant_email" type="email"
                            class="form-control mt-1" :value="old('Identifiant_email')" required />
                        <x-input-error :messages="$errors->get('Identifiant_email')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Mot de passe')" />
                        <x-text-input id="password" name="password" type="password" class="form-control mt-1" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control mt-1" required />
                    </div>

                    <div class="mb-3 form-check">
                        <input id="Actived" type="checkbox" name="Actived" value="1" checked class="form-check-input">
                        <label for="Actived" class="form-check-label">{{ __('Activer le compte') }}</label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-primary-button class="btn btn-primary">{{ __('CRÉER') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
