<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer un Type de Congé Global
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('leave-types.index') }}" class="back-link">← Retour au catalogue</a>

            <div class="form-card">
                <form action="{{ route('leave-types.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="code" class="form-label">Code *</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               class="form-input @error('code') is-invalid @enderror"
                               placeholder="CP, RTT, MAT..." maxlength="50" required>
                        <p class="form-hint">Code unique, court et technique</p>
                        @error('code')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Nom *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="form-input @error('name') is-invalid @enderror"
                               placeholder="Congé Payé, RTT, Maladie..." required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-input form-textarea @error('description') is-invalid @enderror"
                                  placeholder="Description du type de congé...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="color" class="form-label">Couleur</label>
                        <div class="color-picker-wrapper">
                            <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}"
                                   class="form-color">
                        </div>
                        <p class="form-hint">Couleur d'affichage dans les calendriers</p>
                    </div>

                    <div class="form-group">
                        <label class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="form-check-input">
                            <span class="form-check-label">Type actif</span>
                        </label>
                        <p class="form-hint">Un type inactif n'apparaît pas dans les nouvelles configurations</p>
                    </div>

                    <div class="flex justify-end gap-3" style="display:flex;justify-content:flex-end;gap:0.75rem;">
                        <a href="{{ route('leave-types.index') }}" class="btn-secondary">Annuler</a>
                        <button type="submit" class="btn-primary">Créer le type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>