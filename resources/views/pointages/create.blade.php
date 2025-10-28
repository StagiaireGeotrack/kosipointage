<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Pointages') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('pointages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Siège -->
                        @can('superadmin')
                        <div class="mb-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1" onchange="loadEmployeesBySiege(this.value)" required>
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>
                        @else
                            <input type="hidden" name="SiegeID" value="{{ auth()->user()->SiegeID }}" />
                        @endcan
                        
                        <!-- Employé -->
                        <div class="mb-3">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <select id="employee_id" name="employee_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un employé') }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->ID }}" {{ old('employee_id') == $employe->ID ? 'selected' : '' }}>
                                        {{ $employe->Nom }} ({{ $employe->BadgeID }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>
                        
                        <!-- Type de pointage -->
                        <div class="mb-3">
                            <x-input-label for="type_" :value="__('Type')" />
                            <select id="type_" name="type_" class="form-select mt-1" required>
                                <option value="entry" {{ old('type_') == 'entry' ? 'selected' : '' }}>{{ __('Entrée') }}</option>
                                <option value="exit" {{ old('type_') == 'exit' ? 'selected' : '' }}>{{ __('Sortie') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type_')" class="mt-2" />
                        </div>
                        
                        <!-- Timestamp -->
                        <div class="mb-3">
                            <x-input-label for="timestamp_" :value="__('Date et heure')" />
                            <x-text-input id="timestamp_" name="timestamp_" type="datetime-local" class="form-control mt-1" :value="old('timestamp_', now()->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('timestamp_')" class="mt-2" />
                        </div>
                        
                        <!-- Latitude -->
                        <div class="mb-3">
                            <x-input-label for="latitude" :value="__('Latitude')" />
                            <x-text-input id="latitude" name="latitude" type="number" step="0.00000001" class="form-control mt-1" :value="old('latitude', 0)" required />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>
                        
                        <!-- Longitude -->
                        <div class="mb-3">
                            <x-input-label for="longitude" :value="__('Longitude')" />
                            <x-text-input id="longitude" name="longitude" type="number" step="0.00000001" class="form-control mt-1" :value="old('longitude', 0)" required />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div>
                        
                        <!-- Photo -->
                        <div class="mb-3">
                            <x-input-label for="photo" :value="__('Image')" />
                            <input id="photo" name="photo" type="file" accept="image/*" class="form-control mt-1" />
                            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>
                        
                        <!-- Synced -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="synced" name="synced" type="checkbox" value="1" {{ old('synced', 1) ? 'checked' : '' }} class="form-check-input">
                                <label for="synced" class="form-check-label fw-medium">{{ __('synchronisée') }}</label>
                            </div>
                        </div>
                        
                        <!-- Boutons de soumission -->
                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a href="{{ route('pointages.index') }}" class="btn btn-secondary me-2">
                                {{ __('Annuler') }}
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

    @push('scripts')
    <script>
        function loadEmployeesBySiege(siegeId) {
            if (!siegeId) return;
            
            fetch('{{ route("pointages.employees-by-siege") }}?siege_id=' + siegeId)
                .then(response => response.json())
                .then(data => {
                    const employeeSelect = document.getElementById('employee_id');
                    employeeSelect.innerHTML = '<option value="">{{ __('app.select_employee') }}</option>';
                    
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