{{-- resources/views/conges/leave_types/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau Type de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-types.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="name" :value="__('Nom du type')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name')" placeholder="{{ __('Ex: Congés Payés') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="code" :value="__('Code')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="code" name="code" type="text" class="form-control mt-1" 
                                :value="old('code')" placeholder="{{ __('Ex: CP') }}" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            <small class="text-muted">{{ __('Code unique pour ce type de congé') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-4">
                            <x-input-label for="unit" :value="__('Unité')" />
                            <span class="text-danger">*</span>
                            <select id="unit" name="unit" class="form-select mt-1" required>
                                <option value="days" {{ old('unit') == 'days' ? 'selected' : '' }}>Jours</option>
                                <option value="half_days" {{ old('unit') == 'half_days' ? 'selected' : '' }}>Demi-journées</option>
                                <option value="hours" {{ old('unit') == 'hours' ? 'selected' : '' }}>Heures</option>
                            </select>
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div class="col-lg-4">
                            <x-input-label for="color" :value="__('Couleur')" />
                            <span class="text-danger">*</span>
                            <div class="input-group mt-1">
                                <input type="color" id="color_picker" class="form-control form-control-color" 
                                    style="width: 50px; padding: 0;" value="{{ old('color', '#10B981') }}">
                                <x-text-input id="color" name="color" type="text" class="form-control" 
                                    :value="old('color', '#10B981')" required />
                            </div>
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </div>

                        <div class="col-lg-4">
                            <x-input-label for="requires_attachment" :value="__('Justificatif requis')" />
                            <span class="text-danger">*</span>
                            <select id="requires_attachment" name="requires_attachment" class="form-select mt-1" required>
                                <option value="never" {{ old('requires_attachment') == 'never' ? 'selected' : '' }}>Jamais</option>
                                <option value="always" {{ old('requires_attachment') == 'always' ? 'selected' : '' }}>Toujours</option>
                                <option value="after_duration" {{ old('requires_attachment') == 'after_duration' ? 'selected' : '' }}>Après une durée</option>
                            </select>
                            <x-input-error :messages="$errors->get('requires_attachment')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4" id="attachment_days_row" style="{{ old('requires_attachment') == 'after_duration' ? '' : 'display: none;' }}">
                        <div class="col-lg-4">
                            <x-input-label for="requires_attachment_after" :value="__('Nombre de jours avant justificatif')" />
                            <x-text-input id="requires_attachment_after" name="requires_attachment_after" type="number" 
                                class="form-control mt-1" :value="old('requires_attachment_after')" min="1" />
                            <x-input-error :messages="$errors->get('requires_attachment_after')" class="mt-2" />
                            <small class="text-muted">{{ __('Le justificatif sera requis après X jours') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-3">
                            <x-input-label for="deducts_balance" :value="__('Déduire du solde')" />
                            <div class="mt-1">
                                <input type="hidden" name="deducts_balance" value="0">
                                <input type="checkbox" id="deducts_balance" name="deducts_balance" value="1" 
                                    {{ old('deducts_balance', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="deducts_balance" class="form-check-label ms-2">
                                    {{ __('Déduire automatiquement') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('deducts_balance')" class="mt-2" />
                        </div>

                        <div class="col-lg-3">
                            <x-input-label for="allow_negative_balance" :value="__('Solde négatif autorisé')" />
                            <div class="mt-1">
                                <input type="hidden" name="allow_negative_balance" value="0">
                                <input type="checkbox" id="allow_negative_balance" name="allow_negative_balance" value="1" 
                                    {{ old('allow_negative_balance') ? 'checked' : '' }} class="form-check-input">
                                <label for="allow_negative_balance" class="form-check-label ms-2">
                                    {{ __('Autoriser') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('allow_negative_balance')" class="mt-2" />
                        </div>

                        <div class="col-lg-3" id="max_negative_row" style="{{ old('allow_negative_balance') ? '' : 'display: none;' }}">
                            <x-input-label for="max_negative_limit" :value="__('Limite négative max')" />
                            <x-text-input id="max_negative_limit" name="max_negative_limit" type="number" 
                                class="form-control mt-1" :value="old('max_negative_limit')" min="0" />
                            <x-input-error :messages="$errors->get('max_negative_limit')" class="mt-2" />
                            <small class="text-muted">{{ __('Laissez vide pour illimité') }}</small>
                        </div>

                        <div class="col-lg-3">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Type actif') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    @if($sites->count() > 0 && auth()->user()->IsSuperAdmin)
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
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
                                <small class="text-muted">{{ __('Permet aux admins de siège de personnaliser ce type') }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            // Synchronisation du color picker
            document.getElementById('color_picker').addEventListener('input', function() {
                document.getElementById('color').value = this.value;
            });
            document.getElementById('color').addEventListener('input', function() {
                document.getElementById('color_picker').value = this.value;
            });

            // Afficher/masquer le champ "jours avant justificatif"
            document.getElementById('requires_attachment').addEventListener('change', function() {
                const row = document.getElementById('attachment_days_row');
                if (this.value === 'after_duration') {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            });

            // Afficher/masquer le champ "limite négative"
            document.getElementById('allow_negative_balance').addEventListener('change', function() {
                const row = document.getElementById('max_negative_row');
                if (this.checked) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            });
        </script>
    @endpush
</x-app-layout>