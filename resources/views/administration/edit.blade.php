{{-- resources/views/administrateurs/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Modification administrateur') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('administrateurs.update', $administrateur->ID) }}">
                        @csrf
                        @method('PUT')

                        <!-- Identifiant_email -->
                        <div class="mb-3">
                            <x-input-label for="Identifiant_email" :value="__('Identifiant ou E-mail')" />
                            <x-text-input id="Identifiant_email" class="form-control mt-1" type="email" name="Identifiant_email" :value="old('Identifiant_email', $administrateur->Identifiant_email)" required />
                            <x-input-error :messages="$errors->get('Identifiant_email')" class="mt-2" />
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
                            <x-text-input id="password" class="form-control mt-1" type="password" name="password" autocomplete="new-password" />
                            <small class="text-muted">{{ __('Laisser vide pour conserver le mot de passe actuel') }}</small>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-3">
                            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                            <x-text-input id="password_confirmation" class="form-control mt-1" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- IsSuperAdmin -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="IsSuperAdmin" type="checkbox" name="IsSuperAdmin" value="1" 
                                    {{ old('IsSuperAdmin', $administrateur->IsSuperAdmin) ? 'checked' : '' }} 
                                    class="form-check-input" 
                                    onchange="toggleAdminFields()" 
                                    disabled>
                                <label for="IsSuperAdmin" class="form-check-label">{{ __('Super Administrateur') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('IsSuperAdmin')" class="mt-2" />
                        </div>

                        <!-- SiegeID -->
                        <div class="mb-3" id="siege-field" style="display: {{ old('IsSuperAdmin', $administrateur->IsSuperAdmin) ? 'none' : 'block' }};">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID_display" class="form-select mt-1" disabled>
                                <option value="">{{ __('Sélectionner un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID', $administrateur->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <!-- Champ caché pour envoyer la vraie valeur -->
                            <input type="hidden" name="SiegeID" value="{{ old('SiegeID', $administrateur->SiegeID) }}">
                            
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <!-- Actived (pour les administrateurs simples uniquement) -->
                        <div class="mb-3" id="actived-field" style="display: {{ old('IsSuperAdmin', $administrateur->IsSuperAdmin) ? 'none' : 'block' }};">
                            <div class="form-check">
                                <input id="Actived" type="checkbox" name="Actived" value="1" 
                                    {{ old('Actived', $administrateur->Actived) ? 'checked' : '' }} 
                                    class="form-check-input">
                                <label for="Actived" class="form-check-label">{{ __('Activer') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary me-2">
                                {{ __('ANNULER') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('MODIFIER') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleAdminFields() {
            const isSuperAdmin = document.getElementById('IsSuperAdmin').checked;
            const siegeField = document.getElementById('siege-field');
            const activedField = document.getElementById('actived-field');
            const siegeSelect = document.getElementById('SiegeID');
            
            if (isSuperAdmin) {
                // Super Admin : masquer siège et actived
                siegeField.style.display = 'none';
                activedField.style.display = 'none';
                siegeSelect.value = ''; // Vider la sélection
                siegeSelect.removeAttribute('required');
            } else {
                // Admin simple : afficher siège et actived
                siegeField.style.display = 'block';
                activedField.style.display = 'block';
                siegeSelect.setAttribute('required', 'required');
            }
        }

        // Initialiser l'état au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            toggleAdminFields();
        });
    </script>
    @endpush
</x-app-layout>