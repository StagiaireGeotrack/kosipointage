<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-pencil"></i> Modifier le rôle : {{ $leaveRole->label }}
            </h2>
            <a href="{{ route('admin.leave-roles.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.leave-roles.update', $leaveRole) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom (identifiant unique) <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $leaveRole->name) }}" required>
                        <small class="text-muted">Ex: manager, rh, drh, direction</small>
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="label" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="label" id="label" class="form-control" value="{{ old('label', $leaveRole->label) }}" required>
                        <small class="text-muted">Ex: Manager, RH, DRH, Direction</small>
                        @error('label') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $leaveRole->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Actif</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('admin.leave-roles.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>