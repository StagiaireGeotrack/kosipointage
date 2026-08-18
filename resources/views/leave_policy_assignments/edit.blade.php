{{-- resources/views/leave_policy_assignments/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-pencil"></i> {{ __('Modifier l\'Assignation') }}
            </h2>
            <a href="{{ route('leave-policy-assignments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('leave-policy-assignments.update', $leavePolicyAssignment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="leave_policy_id" :value="__('Politique de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_policy_id" name="leave_policy_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez une politique') }}</option>
                                @foreach($leavePolicies as $policy)
                                    <option value="{{ $policy->id }}" {{ old('leave_policy_id', $leavePolicyAssignment->leave_policy_id) == $policy->id ? 'selected' : '' }}>
                                        {{ $policy->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_policy_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="priority" :value="__('Priorité')" />
                            <x-text-input id="priority" name="priority" type="number" class="form-control mt-1" 
                                :value="old('priority', $leavePolicyAssignment->priority)" min="0" max="100" />
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            <small class="text-muted">{{ __('Plus le nombre est élevé, plus la priorité est grande (0-100)') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="target_type_display" :value="__('Type de cible')" />
                            <input type="text" id="target_type_display" class="form-control mt-1" 
                                   value="{{ $leavePolicyAssignment->target_type }}" disabled>
                            <small class="text-muted">Le type de cible ne peut pas être modifié</small>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="target_display" :value="__('Cible')" />
                            <input type="text" id="target_display" class="form-control mt-1" 
                                   value="{{ $leavePolicyAssignment->target_label }}" disabled>
                            <small class="text-muted">La cible ne peut pas être modifiée</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', $leavePolicyAssignment->is_active) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Assignation active') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <x-primary-button class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ __('Mettre à jour') }}
                        </x-primary-button>
                        <a href="{{ route('leave-policy-assignments.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>