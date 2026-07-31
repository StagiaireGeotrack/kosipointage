<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modifier : {{ $leaveType->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('leave-types.index') }}" class="back-link">← Retour au catalogue</a>

            <div class="form-card">
                <form action="{{ route('leave-types.update', $leaveType) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="code" class="form-label">Code *</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $leaveType->code) }}"
                               class="form-input @error('code') is-invalid @enderror"
                               maxlength="50" required>
                        @error('code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Nom *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $leaveType->name) }}"
                               class="form-input @error('name') is-invalid @enderror"
                               required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-input form-textarea @error('description') is-invalid @enderror">{{ old('description', $leaveType->description) }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label">Couleur</label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="color" id="color" value="{{ old('color', $leaveType->color ?? '#3B82F6') }}"
                                   class="form-color">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $leaveType->is_active) ? 'checked' : '' }}
                                   class="form-check-input">
                            <span class="form-check-label">Type actif</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3" style="display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="{{ route('leave-types.index') }}" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>

            <div class="alert-warning">
                <p class="alert-warning-title">⚠️ Impact sur les sièges</p>
                <p class="alert-warning-text">
                    Ce type est référencé par <strong>{{ $leaveType->leavePolicies()->count() }} règle(s) de siège</strong>.
                    Modifier son nom ou son code mettra à jour l'affichage pour tous les sièges concernés.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>