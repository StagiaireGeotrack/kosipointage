{{-- resources/views/leave_validators/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> Ajouter un validateur
            </h2>
            <a href="{{ route('admin.leave-validators.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.leave-validators.store') }}" method="POST">
                    @csrf

                    @if(auth()->user()->IsSuperAdmin)
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site <span class="text-danger">*</span></label>
                            <select name="site_id" id="site_id" class="form-select" required>
                                <option value="">-- Sélectionnez un site --</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('site_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    @else
                        <input type="hidden" name="site_id" value="{{ $siteId }}">
                    @endif

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employé <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">-- Sélectionnez un employé --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->ID }}" {{ old('employee_id') == $employee->ID ? 'selected' : '' }}>
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
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="rh" {{ old('role') == 'rh' ? 'selected' : '' }}>RH</option>
                            <option value="drh" {{ old('role') == 'drh' ? 'selected' : '' }}>DRH</option>
                            <option value="direction" {{ old('role') == 'direction' ? 'selected' : '' }}>Direction</option>
                        </select>
                        @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Actif</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('admin.leave-validators.index') }}" class="btn btn-secondary">Annuler</a>
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

            if (siteSelect) {
                siteSelect.addEventListener('change', function() {
                    const siteId = this.value;
                    if (!siteId) {
                        employeeSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un site --</option>';
                        return;
                    }
                    fetch(`/admin/leave-validators/get-employees/${siteId}`)
                        .then(response => response.json())
                        .then(data => {
                            employeeSelect.innerHTML = '<option value="">-- Sélectionnez un employé --</option>';
                            data.forEach(emp => {
                                employeeSelect.innerHTML += `<option value="${emp.ID}">${emp.Nom} (${emp.num_mat || 'N/A'})</option>`;
                            });
                        })
                        .catch(() => {
                            employeeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                        });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>