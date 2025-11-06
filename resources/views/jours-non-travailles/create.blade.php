{{-- resources/views/jours-non-travailles/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Ajouter un jour non travaillé') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('jours-non-travailles.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="Date" :value="__('Date')" />
                            <x-text-input id="Date" name="Date" type="date" class="form-control mt-1" :value="old('Date')" required />
                            <x-input-error :messages="$errors->get('Date')" class="mt-2" />
                        </div>
                        
                        <!-- Nom -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="Nom" :value="__('Nom du jour')" />
                            <x-text-input id="Nom" name="Nom" type="text" class="form-control mt-1" :value="old('Nom')" placeholder="{{ __('Ex: Fête nationale') }}" required />
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Type -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="Type" :value="__('Type')" />
                            <select id="Type" name="Type" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                <option value="ferie" {{ old('Type') == 'ferie' ? 'selected' : '' }}>{{ __('Férié') }}</option>
                                <option value="fermeture" {{ old('Type') == 'fermeture' ? 'selected' : '' }}>{{ __('Fermeture exceptionnelle') }}</option>
                                <option value="autre" {{ old('Type') == 'autre' ? 'selected' : '' }}>{{ __('Autre') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('Type')" class="mt-2" />
                        </div>
                        
                        <!-- Siège -->
                        <div class="col-md-6 mb-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1">
                                <option value="">{{ __('National (tous les sièges)') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                            <small class="text-muted">{{ __('Laissez vide pour un jour férié national') }}</small>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <x-input-label for="Description" :value="__('Description (optionnel)')" />
                        <textarea id="Description" name="Description" class="form-control mt-1" rows="3" placeholder="{{ __('Ajoutez une description...') }}">{{ old('Description') }}</textarea>
                        <x-input-error :messages="$errors->get('Description')" class="mt-2" />
                    </div>
                    
                    <div class="row">
                        <!-- Récurrent -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="Recurrent" name="Recurrent" value="1" {{ old('Recurrent') ? 'checked' : '' }}>
                                <label class="form-check-label" for="Recurrent">
                                    {{ __('Récurrent chaque année (même date)') }}
                                </label>
                            </div>
                            <small class="text-muted">{{ __('Ex: 1er janvier, 25 décembre...') }}</small>
                            <x-input-error :messages="$errors->get('Recurrent')" class="mt-2" />
                        </div>
                        
                        <!-- Actif -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" class="form-check-input" id="Actived" name="Actived" value="1" {{ old('Actived', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="Actived">
                                    {{ __('Actif') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                        </div>
                    </div>
                    
                    <!-- Boutons -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('jours-non-travailles.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>