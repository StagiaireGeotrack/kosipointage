{{-- resources/views/sieges/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">        
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark">
                {{ __('Nouveau siège') }}
            </h2>        
            <a href="{{ route('sieges.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('sieges.update_siege') }}">
                    @csrf
                    @method('patch')

                    <!-- Nom -->
                    <div class="mb-3">
                        <x-input-label for="Nom" :value="__('Nom')" />
                        <input type="hidden" name="ID" value="{{ $siege->ID }}" />
                        <x-text-input id="Nom" class="form-control mt-1" type="text" name="Nom" value="{{ $siege->Nom }}" required />
                        <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <x-input-label for="Pays" :value="__('Pays')" />
                        <select id="Pays" name="Pays" class="form-control">
                            <option value="">Sélectionnez un pays</option>
                            @foreach($countries as $code => $country)
                                <option value="{{ $code }}" {{ $code == old('Pays', $siege->Pays ?? '') ? 'selected' : '' }}>
                                    {{ $country['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('Pays')" class="mt-2" />
                    </div>

                    <!-- Nom_Lieu_Ville -->
                    <div class="mb-3">
                        <x-input-label for="Nom_Lieu_Ville" :value="__('Adresse ou ville')" />
                        <x-text-input id="Nom_Lieu_Ville" class="form-control mt-1" type="text" name="Nom_Lieu_Ville" value="{{ $siege->Nom_Lieu_Ville }}" required />
                        <x-input-error :messages="$errors->get('Nom_Lieu_Ville')" class="mt-2" />
                    </div>

                    <!-- Actived -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="Actived" type="checkbox" name="Actived" value="1" {{ $siege->Actived ? 'checked' : '' }} class="form-check-input">
                            <label for="Actived" class="form-check-label">{{ __('Activer') }}</label>
                        </div>
                        <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                    </div>

                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Modifier') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>