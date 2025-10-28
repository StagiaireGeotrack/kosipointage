{{-- resources/views/administration/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Nouveau administrateur') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('administrateurs.store') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <x-input-label for="Identifiant_email" :value="__('E-mail')" />
                            <x-text-input id="Identifiant_email" class="form-control" type="email" name="Identifiant_email" :value="old('Identifiant_email')" required autofocus />
                            <x-input-error :messages="$errors->get('Identifiant_email')" class="mt-2" />
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <x-input-label for="Password_" :value="__('Mot de passe')" />
                            <x-text-input id="Password_" class="form-control" type="password" name="Password_" required />
                            <x-input-error :messages="$errors->get('Password_')" class="mt-2" />
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="mb-3">
                            <x-input-label for="password_confirmation" :value="__('Confirmation mot de passe')" />
                            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Type d'administrateur -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="IsSuperAdmin" type="checkbox" name="IsSuperAdmin" value="1" {{ old('IsSuperAdmin') ? 'checked' : '' }} class="form-check-input" onchange="toggleSiegeField()">
                                <label for="IsSuperAdmin" class="form-check-label">{{ __('Super administrateur') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('IsSuperAdmin')" class="mt-2" />
                        </div>

                        <!-- Siège (requis uniquement pour les admin standard) -->
                        <div id="siege-field" class="mb-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select">
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('app.standard_admin_siege_help') }}</small>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-4">
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary me-2">
                                {{ __('ANNULER') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('Ajouter') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSiegeField() {
            const isSuperAdmin = document.getElementById('IsSuperAdmin').checked;
            const siegeField = document.getElementById('siege-field');
            
            if (isSuperAdmin) {
                siegeField.style.opacity = '0.5';
                siegeField.querySelector('select').removeAttribute('required');
            } else {
                siegeField.style.opacity = '1';
                siegeField.querySelector('select').setAttribute('required', 'required');
            }
        }
        
        // Initialiser l'état du champ siège au chargement
        document.addEventListener('DOMContentLoaded', function() {
            toggleSiegeField();
        });
    </script>
</x-app-layout>