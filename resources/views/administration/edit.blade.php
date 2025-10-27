<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('app.edit_admin') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('administrateurs.update', $administrateur->ID) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <x-input-label for="Identifiant_email" :value="__('app.email')" />
                            <x-text-input id="Identifiant_email" name="Identifiant_email" type="email" class="form-control" :value="old('Identifiant_email', $administrateur->Identifiant_email)" required />
                            <x-input-error :messages="$errors->get('Identifiant_email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <x-input-label for="password" :value="__('app.password')" />
                            <x-text-input id="password" name="password" type="password" class="form-control" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <small class="form-text text-muted">{{ __('app.leave_empty_to_keep_current') }}</small>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <x-input-label for="password_confirmation" :value="__('app.confirm_password')" />
                            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <!-- Type d'administrateur -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="IsSuperAdmin" name="IsSuperAdmin" type="checkbox" value="1" {{ old('IsSuperAdmin', $administrateur->IsSuperAdmin) ? 'checked' : '' }} class="form-check-input" onchange="toggleSiegeField()">
                                <label for="IsSuperAdmin" class="form-check-label fw-medium">{{ __('app.super_admin') }}</label>
                                <div>
                                    <small class="text-muted">{{ __('app.super_admin_description') }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Siège (uniquement pour les admins standards) -->
                        <div id="siegeField" class="mb-3 {{ $administrateur->IsSuperAdmin ? 'd-none' : '' }}">
                            <x-input-label for="SiegeID" :value="__('app.office')" />
                            <select id="SiegeID" name="SiegeID" class="form-select">
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID', $administrateur->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                            <small class="form-text text-muted">{{ __('app.admin_siege_description') }}</small>
                        </div>
                        
                        <!-- Boutons de soumission -->
                        <div class="d-flex justify-content-end align-items-center mt-4">
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary me-2">
                                {{ __('app.cancel') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('app.save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleSiegeField() {
            const isSuperAdmin = document.getElementById('IsSuperAdmin').checked;
            const siegeField = document.getElementById('siegeField');
            
            if (isSuperAdmin) {
                siegeField.classList.add('d-none');
                document.getElementById('SiegeID').removeAttribute('required');
            } else {
                siegeField.classList.remove('d-none');
                document.getElementById('SiegeID').setAttribute('required', 'required');
            }
        }
    </script>
    @endpush
</x-app-layout>