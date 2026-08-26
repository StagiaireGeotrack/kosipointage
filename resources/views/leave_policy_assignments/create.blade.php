{{-- resources/views/leave_policy_assignments/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle Assignation') }}
            </h2>
            <a href="{{ route('admin.leave-policy-assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-policy-assignments.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="leave_policy_id" :value="__('Politique de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_policy_id" name="leave_policy_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez une politique') }}</option>
                                @foreach($leavePolicies as $policy)
                                    <option value="{{ $policy->id }}" {{ old('leave_policy_id') == $policy->id ? 'selected' : '' }}>
                                        {{ $policy->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_policy_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="priority" :value="__('Priorité')" />
                            <x-text-input id="priority" name="priority" type="number" class="form-control mt-1" 
                                :value="old('priority', 50)" min="0" max="100" />
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            <small class="text-muted">{{ __('Plus le nombre est élevé, plus la priorité est grande (0-100)') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="assignment_type" :value="__('Type d\'assignation')" />
                            <span class="text-danger">*</span>
                            <select id="assignment_type" name="assignment_type" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                <option value="individual" {{ old('assignment_type') == 'individual' ? 'selected' : '' }}>Individuel</option>
                                <option value="department" {{ old('assignment_type') == 'department' ? 'selected' : '' }}>Service</option>
                                <option value="site" {{ old('assignment_type') == 'site' ? 'selected' : '' }}>Siège</option>
                                <option value="company" {{ old('assignment_type') == 'company' ? 'selected' : '' }}>Entreprise</option>
                            </select>
                            <x-input-error :messages="$errors->get('assignment_type')" class="mt-2" />
                        </div>

                        <div class="col-md-6" id="target_container">
                            <x-input-label for="target_id" :value="__('Cible')" />
                            <span class="text-danger">*</span>
                            <select id="target_id" name="target_id" class="form-select mt-1">
                                <option value="">{{ __('Sélectionnez d\'abord un type') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('target_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Assignation active') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ __('Créer') }}
                        </button>
                        <a href="{{ route('admin.leave-policy-assignments.index') }}" class="btn btn-secondary">
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
            const assignmentType = document.getElementById('assignment_type');
            const targetId = document.getElementById('target_id');

            // Données préchargées
            const data = {
                employees: @json($employees->map(fn($e) => ['id' => $e->ID, 'name' => $e->Nom])),
                departments: @json($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])),
                sites: @json($sites->map(fn($s) => ['id' => $s->ID, 'name' => $s->Nom])),
            };

            function updateTargetOptions() {
                const type = assignmentType.value;
                let options = '<option value="">{{ __("Sélectionnez une cible") }}</option>';

                if (type === 'individual') {
                    data.employees.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                } else if (type === 'department') {
                    data.departments.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                } else if (type === 'site') {
                    data.sites.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                } else if (type === 'company') {
                    // Pour l'entreprise, on pourrait avoir une liste
                    options += `<option value="1">Entreprise principale</option>`;
                }

                targetId.innerHTML = options;
            }

            assignmentType.addEventListener('change', updateTargetOptions);

            // Initialisation
            const oldType = '{{ old('assignment_type') }}';
            const oldTarget = '{{ old('target_id') }}';
            if (oldType) {
                assignmentType.value = oldType;
                updateTargetOptions();
                if (oldTarget) {
                    targetId.value = oldTarget;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>