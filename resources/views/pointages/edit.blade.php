<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modification pointage
            </h2>
            <a href="{{ route('pointages.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('pointages.update', $pointage->ID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Siège -->
                    @can('superadmin') 
                    <div class="mb-3">
                        <x-input-label for="SiegeID" :value="__('Siège')" />
                        <select id="SiegeID" name="SiegeID" class="form-select mt-1" onchange="loadEmployeesBySiege(this.value)" required>
                            @foreach($sieges as $siege)
                                <option value="{{ $siege->ID }}" {{ old('SiegeID', $pointage->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                    {{ $siege->Nom }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                    </div>
                    @else
                        <input type="hidden" name="SiegeID" value="{{ $pointage->SiegeID }}" />
                    @endcan
                    
                    <!-- site ou établissement -->
                    <div class="mb-3">
                        <x-input-label for="company_id" :value="__('Site ou établissement')" />
                        <select id="company_id" name="company_id" class="form-select mt-1" required>
                            <option value="">{{ __('Sélectionnez un site ou établissement') }}</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->ID }}" {{ old('company_id',$pointage->company_id ) == $pointage->company_id ? 'selected' : '' }}>
                                    {{ $site->Nom }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>

                    <!-- Employé -->
                    <div class="mb-3">
                        <x-input-label for="employee_id" :value="__('Employé')" />
                        <select id="employee_id" name="employee_id" class="form-select mt-1" required>
                            @foreach($employes as $employe)
                                <option value="{{ $employe->ID }}" {{ old('employee_id', $pointage->employee_id) == $employe->ID ? 'selected' : '' }}>
                                    {{ $employe->Nom }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    
                    <!-- Type de pointage -->
                    <div class="mb-3">
                        <x-input-label for="type_" :value="__('Type')" />
                        <select id="type_" name="type_" class="form-select mt-1" required>
                            <option value="entry" {{ old('type_', $pointage->type_) == 'entry' ? 'selected' : '' }}>{{ __('Entrée') }}</option>
                            <option value="exit" {{ old('type_', $pointage->type_) == 'exit' ? 'selected' : '' }}>{{ __('Sortie') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('type_')" class="mt-2" />
                    </div>
                    
                    <!-- Auth Method -->
                    <div class="mb-3">
                        <x-input-label for="auth_method" :value="__('Méthode')" />
                        <select id="auth_method" name="auth_method" class="form-select mt-1" required>
                            <option value="rfid" {{ old('auth_method', $pointage->auth_method) == 'rfid' ? 'selected' : '' }}>{{ __('Badge') }}</option>
                            <option value="face" {{ old('auth_method', $pointage->auth_method) == 'face' ? 'selected' : '' }}>{{ __('Face image') }}</option>
                            <option value="pin" {{ old('auth_method', $pointage->auth_method) == 'pin' ? 'selected' : '' }}>{{ __('Code PIN') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('auth_method')" class="mt-2" />
                    </div>
                    
                    <!-- Timestamp -->
                    <div class="mb-3">
                        <x-input-label for="timestamp_" :value="__('Date et heure')" />
                        <x-text-input id="timestamp_" name="timestamp_" type="datetime-local" class="form-control mt-1" :value="old('timestamp_', $pointage->timestamp_->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('timestamp_')" class="mt-2" />
                    </div>
                    
                    <!-- Latitude -->
                    <div class="mb-3">
                        <x-input-label for="latitude" :value="__('Latitude')" />
                        <x-text-input id="latitude" name="latitude" type="number" step="0.00000001" class="form-control mt-1" :value="old('latitude', $pointage->latitude)" required />
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    </div>
                    
                    <!-- Longitude -->
                    <div class="mb-3">
                        <x-input-label for="longitude" :value="__('Longitude')" />
                        <x-text-input id="longitude" name="longitude" type="number" step="0.00000001" class="form-control mt-1" :value="old('longitude', $pointage->longitude)" required />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>
                    
                    <!-- Photo actuelle -->
                    @if ($pointage->photo_path)
                        <div class="mb-3">
                            <x-input-label :value="__('Image actuelle')" />
                            <div class="mt-2">
                                <img src="{{ route('pointages.photo.thumbnail', $pointage->ID) }}" alt="{{ __('Image actuelle') }}" class="rounded" style="height: 96px; width: auto;">
                            </div>
                        </div>
                    @endif
                    
                    <!-- Nouvelle photo -->
                    <div class="mb-3">
                        <x-input-label for="photo" :value="__('Nouvelle image')" />
                        <input id="photo" name="photo" type="file" accept="image/*" class="form-control mt-1" />
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                    
                    <!-- Boutons de soumission -->
                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <x-primary-button class="btn btn-primary">
                            {{ __('MODIFIER') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function loadEmployeesBySiege(siegeId) {
            if (!siegeId) return;
            
            // ✅ Utiliser le paramètre de route au lieu de query string
            fetch(`/pointages/get-employes-by-siege/${siegeId}`)
                .then(response => response.json())
                .then(data => {
                    const employeeSelect = document.getElementById('employee_id');
                    employeeSelect.innerHTML = '<option value="">{{ __('Sélectionner un employé') }}</option>';
                    
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.ID;
                        option.textContent = `${employee.Nom} (${employee.BadgeID})`;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading employees:', error));
        }
        
        // Si position actuelle est demandée
        document.addEventListener('DOMContentLoaded', function() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>