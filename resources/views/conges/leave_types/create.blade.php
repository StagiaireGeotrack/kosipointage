<x-app-layout>
    <x-slot name="header">
        <h5 class="font-semibold text-xl text-gray-800 leading-tight">
            Nouveau type global
        </h5>
    </x-slot>

    <div class="py-12 conges-admin">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('leave-types.index') }}" class="ca-btn-back">
    &larr; Retour au catalogue
</a>

            <div class="ca-form-card">
                <div style="margin-bottom: 1.5rem;">
                    <h3 class="ca-page-title"></h3>
                </div>

                <form action="{{ route('leave-types.store') }}" method="POST">
                    @csrf

                    <div class="ca-form-group">
                        <label for="code" class="ca-form-label">Code identifiant *</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               class="ca-form-input @error('code') is-invalid @enderror"
                               placeholder="CP, RTT, MAT..." maxlength="50" required>
                        <p class="ca-form-hint">Code technique unique, court et sans espace. Exemple : CP, RTT, MALADIE</p>
                        @error('code')
                            <p class="ca-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group">
                        <label for="name" class="ca-form-label">Nom affiché *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="ca-form-input @error('name') is-invalid @enderror"
                               placeholder="Congé Payé, RTT, Maladie..." required>
                        @error('name')
                            <p class="ca-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group">
                        <label for="description" class="ca-form-label">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="ca-form-textarea @error('description') is-invalid @enderror"
                                  placeholder="Décrivez l'usage de ce type de congé...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="ca-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group">
                        <label for="color" class="ca-form-label">Couleur d'affichage</label>
                        <div class="ca-color-wrap">
                            <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}"
                                   class="ca-color-input">
                            <span class="ca-form-hint">Utilisée dans les calendriers et tableaux de bord</span>
                        </div>
                    </div>

                    <div class="ca-form-group">
                        <label class="ca-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="ca-check-input">
                            <span class="ca-check-label">Type actif</span>
                        </label>
                        <p class="ca-form-hint">Un type inactif n'apparaît pas dans la liste des types disponibles pour les nouvelles configurations de siège.</p>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 0.5rem; border-top: 1px solid #f1f5f9;">
                        <a href="{{ route('leave-types.index') }}" class="ca-btn ca-btn-secondary">Annuler</a>
                        <button type="submit" class="ca-btn ca-btn-primary">Créer le type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>