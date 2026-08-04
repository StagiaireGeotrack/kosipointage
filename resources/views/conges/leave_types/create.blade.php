<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h5 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ __('Nouveau type global') }}
            </h5>
            <a href="{{ route('leave-types.index') }}" class="ca-btn-back text-sm">
                &larr; {{ __('Retour au catalogue') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 conges-admin">
        <!-- 'max-w-md' réduit la largeur du conteneur (environ 448px de large) -->
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">

            <!-- Card avec bordure blanche fine et ombre légère -->
            <div class="ca-form-card bg-white border border-white rounded-lg shadow-sm p-5">
                
                <form action="{{ route('leave-types.store') }}" method="POST">
                    @csrf

                    <div class="ca-form-group mb-4">
                        <label for="code" class="ca-form-label text-sm font-medium">Code identifiant *</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                               class="ca-form-input @error('code') is-invalid @enderror w-full mt-1"
                               placeholder="CP, RTT, MAT..." maxlength="50" required>
                        <p class="ca-form-hint text-xs text-gray-500 mt-1">Code unique, court et sans espace (ex: CP, RTT).</p>
                        @error('code')
                            <p class="ca-form-error text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group mb-4">
                        <label for="name" class="ca-form-label text-sm font-medium">Nom affiché *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="ca-form-input @error('name') is-invalid @enderror w-full mt-1"
                               placeholder="Congé Payé, RTT, Maladie..." required>
                        @error('name')
                            <p class="ca-form-error text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group mb-4">
                        <label for="description" class="ca-form-label text-sm font-medium">Description</label>
                        <textarea name="description" id="description" rows="2"
                                  class="ca-form-textarea @error('description') is-invalid @enderror w-full mt-1"
                                  placeholder="Décrivez l'usage de ce type...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="ca-form-error text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="ca-form-group mb-4">
                        <label for="color" class="ca-form-label text-sm font-medium">Couleur d'affichage</label>
                        <div class="ca-color-wrap flex items-center gap-2 mt-1">
                            <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}"
                                   class="ca-color-input h-8 w-12 rounded cursor-pointer border border-gray-200">
                            <span class="ca-form-hint text-xs text-gray-500">Utilisée dans le calendrier</span>
                        </div>
                    </div>

                    <div class="ca-form-group mb-5">
                        <label class="ca-check flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="ca-check-input rounded">
                            <span class="ca-check-label text-sm font-medium">Type actif</span>
                        </label>
                        <p class="ca-form-hint text-xs text-gray-500 mt-1">Visible lors de la création de nouvelles configurations.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('leave-types.index') }}" class="ca-btn ca-btn-secondary px-3 py-1.5 text-sm">Annuler</a>
                        <button type="submit" class="ca-btn ca-btn-primary px-4 py-1.5 text-sm">Créer le type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>