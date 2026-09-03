{{-- resources/views/conges/leave_workflows/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau Workflow de Validation') }}
            </h2>
            <a href="{{ route('admin.leave-workflows.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-workflows.store') }}" id="workflowForm">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-12">
                            <x-input-label for="name" :value="__('Nom du workflow')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name')" placeholder="{{ __('Ex: Workflow Standard') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-12">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="form-control mt-1" rows="2" 
                                placeholder="{{ __('Description optionnelle du workflow') }}">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
    <div class="col-lg-12">
        <x-input-label for="leave_type_id" :value="__('Type de congé')" />
        <select id="leave_type_id" name="leave_type_id" class="form-select mt-1">
            <option value="">{{ __('Aucun (général)') }}</option>
            @foreach($leaveTypes as $type)
                <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }} ({{ $type->code }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
        <small class="text-muted">{{ __('Associez ce workflow à un type de congé spécifique. Laissez vide pour un workflow général.') }}</small>
    </div>
</div>

                    <!-- Gestion des étapes -->
                    <div class="row g-3 mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">{{ __('Étapes de validation') }}</h5>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addStep()">
                                            <i class="bi bi-plus-circle"></i> Ajouter une étape
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="steps-container">
                                        <div class="alert alert-info" id="no-steps-msg">
                                            <i class="bi bi-info-circle"></i> Aucune étape définie. Le workflow par défaut sera utilisé.
                                        </div>
                                    </div>
                                    <input type="hidden" name="steps" id="steps-input" value="">
                                    <x-input-error :messages="$errors->get('steps')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-3">
                            <x-input-label for="is_default" :value="__('Workflow par défaut')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_default" value="0">
                                <input type="checkbox" id="is_default" name="is_default" value="1" 
                                    {{ old('is_default') ? 'checked' : '' }} class="form-check-input">
                                <label for="is_default" class="form-check-label ms-2">
                                    {{ __('Définir par défaut') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_default')" class="mt-2" />
                        </div>

                        <div class="col-lg-3">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Workflow actif') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="is_customizable" :value="__('Personnalisable par siège')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_customizable" value="0">
                                <input type="checkbox" id="is_customizable" name="is_customizable" value="1" 
                                    {{ old('is_customizable') ? 'checked' : '' }} class="form-check-input">
                                <label for="is_customizable" class="form-check-label ms-2">
                                    {{ __('Personnalisable') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                            <small class="text-muted">{{ __('Permet aux admins de siège de personnaliser ce workflow') }}</small>
                        </div>
                    </div>

                    @if($sites->count() > 0 && auth()->user()->IsSuperAdmin)
                        <div class="row g-3 mb-4">
                            <div class="col-lg-12">
                                <x-input-label for="site_id" :value="__('Siège')" />
                                <select id="site_id" name="site_id" class="form-select mt-1">
                                    <option value="">{{ __('Global (tous les sièges)') }}</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                            {{ $site->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                                <small class="text-muted">{{ __('Sélectionnez un siège spécifique ou laissez global') }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.leave-workflows.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
    <script>
        let stepCounter = 0;
       const roleOptions = {
    'manager': 'Manager',
    'rh': 'RH',
    'drh': 'DRH',
    'direction': 'Direction'
};;

        function addStep(data = null) {
            const container = document.getElementById('steps-container');
            const noStepsMsg = document.getElementById('no-steps-msg');
            if (noStepsMsg) noStepsMsg.remove();

            stepCounter++;

            const order = data?.order || stepCounter;
            const role = data?.role || 'manager';
            const label = data?.label || '';
            const description = data?.description || '';

            const stepHtml = `
                <div class="step-item border rounded p-3 mb-3" data-step-id="${stepCounter}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Ordre</label>
                                    <input type="number" class="form-control step-order" value="${order}" min="1" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Rôle</label>
                                   <select class="form-select step-role">
    <option value="manager" ${role === 'manager' ? 'selected' : ''}>Manager</option>
    <option value="rh" ${role === 'rh' ? 'selected' : ''}>RH</option>
    <option value="drh" ${role === 'drh' ? 'selected' : ''}>DRH</option>
    <option value="direction" ${role === 'direction' ? 'selected' : ''}>Direction</option>
</select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Label</label>
                                    <input type="text" class="form-control step-label" value="${label}" placeholder="Ex: Validation Manager">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Description</label>
                                    <input type="text" class="form-control step-description" value="${description}" placeholder="Description courte">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeStep(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', stepHtml);
            updateStepsInput();
        }

        function removeStep(button) {
            const stepItem = button.closest('.step-item');
            if (stepItem) {
                stepItem.remove();
                updateStepsInput();
                reorderSteps();
            }
        }

        function reorderSteps() {
            const steps = document.querySelectorAll('.step-item');
            steps.forEach((step, index) => {
                const orderInput = step.querySelector('.step-order');
                if (orderInput) {
                    orderInput.value = index + 1;
                }
            });
            updateStepsInput();
        }

        function updateStepsInput() {
            const steps = document.querySelectorAll('.step-item');
            const stepsData = [];
            
            steps.forEach((step) => {
                const order = step.querySelector('.step-order')?.value || 1;
                const role = step.querySelector('.step-role')?.value || 'manager';
                const label = step.querySelector('.step-label')?.value || '';
                const description = step.querySelector('.step-description')?.value || '';
                
                stepsData.push({
                    order: parseInt(order),
                    role: role,
                    label: label || roleOptions[role] || role,
                    description: description
                });
            });

            document.getElementById('steps-input').value = JSON.stringify(stepsData);
        }

        // Initialiser avec des étapes par défaut
        document.addEventListener('DOMContentLoaded', function() {
            // Ajouter 2 étapes par défaut
            addStep({ order: 1, role: 'manager', label: 'Validation Manager', description: 'Le manager valide la demande' });
            addStep({ order: 2, role: 'hr', label: 'Validation RH', description: 'Le service RH approuve la demande' });
        });

        // Mettre à jour les steps avant soumission
        document.getElementById('workflowForm').addEventListener('submit', function(e) {
            updateStepsInput();
        });
    </script>
    @endpush
</x-app-layout>