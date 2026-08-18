{{-- resources/views/leave_balances/initialize.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> {{ __('Initialiser un solde') }}
            </h2>
            <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    {{ __('Cette opération crée un solde initial pour un employé. Le solde sera enregistré dans le journal des transactions.') }}
                </div>

                <form method="POST" action="{{ route('leave-balances.store-initialization') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="site_id" :value="__('Siège')" />
                            <span class="text-danger">*</span>
                            <select id="site_id" name="site_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" 
                                        {{ (old('site_id') == $site->ID) || (auth()->user()->SiegeID == $site->ID && !auth()->user()->IsSuperAdmin) ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                            <small class="text-muted">{{ __('Sélectionnez le siège pour lequel vous voulez initialiser le solde') }}</small>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <span class="text-danger">*</span>
                            <select id="employee_id" name="employee_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un employé') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->ID }}" {{ old('employee_id') == $employee->ID ? 'selected' : '' }}>
                                        {{ $employee->Nom }} ({{ $employee->num_mat ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type de congé') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="period_id" :value="__('Période')" />
                            <span class="text-danger">*</span>
                            <select id="period_id" name="period_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez une période') }}</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="amount" :value="__('Solde initial')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="amount" name="amount" type="number" step="0.5" class="form-control mt-1" 
                                :value="old('amount')" placeholder="{{ __('Ex: 25') }}" required min="0" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            <small class="text-muted">{{ __('Nombre de jours acquis') }}</small>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="description" :value="__('Description (optionnel)')" />
                            <x-text-input id="description" name="description" type="text" class="form-control mt-1" 
                                :value="old('description')" placeholder="{{ __('Ex: Solde initial CP 2026') }}" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <x-primary-button class="btn btn-success">
                            <i class="bi bi-save"></i> {{ __('Initialiser') }}
                        </x-primary-button>
                        <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('site_id');
            const employeeSelect = document.getElementById('employee_id');
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const periodSelect = document.getElementById('period_id');

            if (siteSelect) {
                siteSelect.addEventListener('change', function() {
                    const siteId = this.value;
                    
                    // Si aucun siège sélectionné, réinitialiser les listes
                    if (!siteId) {
                        employeeSelect.innerHTML = '<option value="">{{ __("Sélectionnez un siège d\'abord") }}</option>';
                        leaveTypeSelect.innerHTML = '<option value="">{{ __("Sélectionnez un siège d\'abord") }}</option>';
                        periodSelect.innerHTML = '<option value="">{{ __("Sélectionnez un siège d\'abord") }}</option>';
                        return;
                    }

                    // === CHARGER LES EMPLOYÉS ===
                    // ESSAYER AVEC /api/ D'ABORD, PUIS SANS
                    fetch(`/api/employees-by-site/${siteId}`)
                        .then(response => {
                            if (!response.ok) {
                                // Si /api/ ne fonctionne pas, essayer sans
                                return fetch(`/employees-by-site/${siteId}`);
                            }
                            return response;
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(data => {
                            employeeSelect.innerHTML = '<option value="">{{ __("Sélectionnez un employé") }}</option>';
                            if (data.length === 0) {
                                employeeSelect.innerHTML += '<option value="">{{ __("Aucun employé trouvé") }}</option>';
                            } else {
                                data.forEach(employee => {
                                    employeeSelect.innerHTML += `<option value="${employee.id}">${employee.name}</option>`;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur chargement employés:', error);
                            employeeSelect.innerHTML = '<option value="">{{ __("Erreur de chargement") }}</option>';
                        });

                    // === CHARGER LES TYPES DE CONGÉS ===
                    fetch(`/api/leave-types-by-site/${siteId}`)
                        .then(response => {
                            if (!response.ok) {
                                return fetch(`/leave-types-by-site/${siteId}`);
                            }
                            return response;
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(data => {
                            leaveTypeSelect.innerHTML = '<option value="">{{ __("Sélectionnez un type de congé") }}</option>';
                            if (data.length === 0) {
                                leaveTypeSelect.innerHTML += '<option value="">{{ __("Aucun type de congé trouvé") }}</option>';
                            } else {
                                data.forEach(type => {
                                    leaveTypeSelect.innerHTML += `<option value="${type.id}">${type.name} (${type.code})</option>`;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur chargement types de congés:', error);
                            leaveTypeSelect.innerHTML = '<option value="">{{ __("Erreur de chargement") }}</option>';
                        });

                    // === CHARGER LES PÉRIODES ===
                    fetch(`/api/periods-by-site/${siteId}`)
                        .then(response => {
                            if (!response.ok) {
                                return fetch(`/periods-by-site/${siteId}`);
                            }
                            return response;
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(data => {
                            periodSelect.innerHTML = '<option value="">{{ __("Sélectionnez une période") }}</option>';
                            if (data.length === 0) {
                                periodSelect.innerHTML += '<option value="">{{ __("Aucune période trouvée") }}</option>';
                            } else {
                                data.forEach(period => {
                                    periodSelect.innerHTML += `<option value="${period.id}">${period.name}</option>`;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erreur chargement périodes:', error);
                            periodSelect.innerHTML = '<option value="">{{ __("Erreur de chargement") }}</option>';
                        });
                });

                // Déclencher le changement initial si un siège est pré-sélectionné
                @if(old('site_id') || auth()->user()->SiegeID)
                    setTimeout(() => {
                        siteSelect.dispatchEvent(new Event('change'));
                    }, 500);
                @endif
            }
        });
    </script>
    @endpush
</x-app-layout>