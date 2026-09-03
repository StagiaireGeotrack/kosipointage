<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> Nouveau rôle de validation
            </h2>
            <a href="{{ route('admin.leave-roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.leave-roles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom (identifiant unique) <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        <small class="text-muted">Ex: manager, rh, drh, direction, responsable</small>
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="label" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="label" id="label" class="form-control" value="{{ old('label') }}" required>
                        <small class="text-muted">Ex: Manager, RH, DRH, Direction, Responsable</small>
                        @error('label') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <!-- ✅ AJOUTER ICI -->
                    @if(auth()->user()->IsSuperAdmin)
                        <div class="mb-3">
                            <label for="site_id" class="form-label">Site</label>
                            <select name="site_id" id="site_id" class="form-select">
                                <option value="">{{ __('Global (tous les sites)') }}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Laissez vide pour un rôle global</small>
                            @error('site_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    @else
                        <input type="hidden" name="site_id" value="{{ auth()->user()->SiegeID }}">
                    @endif
                    <!-- FIN AJOUT -->

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Actif</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Créer</button>
                        <a href="{{ route('admin.leave-roles.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>