{{-- resources/views/leave_validators/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-pencil"></i> Modifier le validateur
            </h2>
            <a href="{{ route('admin.leave-validators.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.leave-validators.update', $leaveValidator) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(auth()->user()->IsSuperAdmin)
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site <span class="text-danger">*</span></label>
                            <select name="site_id" id="site_id" class="form-select" required>
                                <option value="">-- Sélectionnez un site --</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" {{ old('site_id', $leaveValidator->site_id) == $site->ID ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('site_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    @else
                        <input type="hidden" name="site_id" value="{{ $leaveValidator->site_id }}">
                    @endif

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employé <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->ID }}" {{ old('employee_id', $leaveValidator->employee_id) == $employee->ID ? 'selected' : '' }}>
                                    {{ $employee->Nom }} ({{ $employee->num_mat ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
    <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
    <select name="role" id="role" class="form-select" required>
        <option value="">-- Sélectionnez un rôle --</option>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ old('role', $leaveValidator->role) == $role->name ? 'selected' : '' }}>
                {{ $role->label }}
            </option>
        @endforeach
    </select>
    @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
</div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $leaveValidator->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Actif</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('admin.leave-validators.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>