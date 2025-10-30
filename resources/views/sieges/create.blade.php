{{-- resources/views/sieges/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Nouveau siège') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('sieges.store') }}">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <x-input-label for="Nom" :value="__('Nom')" />
                            <x-text-input id="Nom" class="form-control mt-1" type="text" name="Nom" :value="old('Nom')" required />
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

                        <!-- Nom_Lieu_Ville -->
                        <div class="mb-3">
                            <x-input-label for="Nom_Lieu_Ville" :value="__('Adresse ou ville')" />
                            <x-text-input id="Nom_Lieu_Ville" class="form-control mt-1" type="text" name="Nom_Lieu_Ville" :value="old('Nom_Lieu_Ville')" />
                            <x-input-error :messages="$errors->get('Nom_Lieu_Ville')" class="mt-2" />
                        </div>

                        <!-- Actived -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="Actived" type="checkbox" name="Actived" value="1" {{ old('Actived') ? 'checked' : '' }} class="form-check-input">
                                <label for="Actived" class="form-check-label">{{ __('Activer') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a href="{{ route('sieges.index') }}" class="btn btn-secondary me-2">
                                {{ __('ANNULER') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('AJOUTER') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>