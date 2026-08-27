{{-- resources/views/conges/leave_types/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $leaveType->isGlobal();
                    $hasOverride = isset($override) && $override;
                    $isCustomizable = $leaveType->is_customizable ?? false;
                    $isDeleted = $leaveType->trashed();
                @endphp

                @if($hasOverride && !$isSuperAdmin)
                    {{ __('Personnalisation du type global') }}
                @elseif($isGlobal && $isSuperAdmin)
                    {{ __('Modifier le Type (Global)') }}
                @elseif($isGlobal && !$isSuperAdmin)
                    {{ __('Personnaliser le Type Global') }}
                @else
                    {{ __('Modifier le Type') }}
                @endif
            </h2>
            <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez un type global pour votre siège. Les modifications n\'affecteront que votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$hasOverride && $isCustomizable)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez ce type global pour votre siège. Les modifications ne seront visibles que pour votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce type global n\'est pas personnalisable. Vous ne pouvez pas le modifier.') }}
                    </div>
                @endif

                @if($isDeleted)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce type a été supprimé le ') . $leaveType->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.leave-types.restore', $leaveType->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if((!$isGlobal || $isSuperAdmin || ($isGlobal && $isCustomizable)) && !$isDeleted)
                    <form method="POST" action="{{ route('admin.leave-types.update', $leaveType->id) }}">
                        @csrf
                        @method('PUT')

                        @if($hasOverride)
                            <input type="hidden" name="is_override" value="1">
                        @endif

                        <!-- Informations de base -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="name" :value="__('Nom du type')" />
                                @if($hasOverride && !$isSuperAdmin)
                                    <span class="text-muted small ms-2">{{ __('(Laisser vide pour hériter du global)') }}</span>
                                @else
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="form-control mt-1 @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $hasOverride ? ($override->local_name ?? '') : $leaveType->name) }}"
                                    placeholder="{{ __('Ex: Congés Payés') }}"
                                    {{ ($isSuperAdmin || !$hasOverride) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leaveType->name)
                                    <small class="text-muted">Valeur globale : {{ $leaveType->name }}</small>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="code" :value="__('Code')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="code" 
                                    name="code" 
                                    type="text" 
                                    class="form-control mt-1 @error('code') is-invalid @enderror" 
                                    value="{{ old('code', $leaveType->code) }}"
                                    placeholder="{{ __('Ex: CP') }}"
                                    {{ (!$hasOverride || $isSuperAdmin) ? 'required' : '' }}
                                    {{ $hasOverride && !$isSuperAdmin ? 'readonly' : '' }} />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin)
                                    <small class="text-muted">Le code ne peut pas être personnalisé</small>
                                @endif
                            </div>
                        </div>

                        <!-- Unités et couleurs -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="unit" :value="__('Unité')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="unit" name="unit" class="form-select mt-1" 
                                    {{ $hasOverride && !$isSuperAdmin ? 'disabled' : '' }}>
                                    <option value="days" {{ old('unit', $leaveType->unit) == 'days' ? 'selected' : '' }}>Jours</option>
                                    <option value="half_days" {{ old('unit', $leaveType->unit) == 'half_days' ? 'selected' : '' }}>Demi-journées</option>
                                    <option value="hours" {{ old('unit', $leaveType->unit) == 'hours' ? 'selected' : '' }}>Heures</option>
                                </select>
                                @if($hasOverride && !$isSuperAdmin)
                                    <input type="hidden" name="unit" value="{{ $leaveType->unit }}">
                                @endif
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin)
                                    <small class="text-muted">L'unité ne peut pas être personnalisée</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="color" :value="__('Couleur')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <div class="input-group mt-1">
                                    <input type="color" id="color_picker" class="form-control form-control-color" 
                                        style="width: 50px; padding: 0;" 
                                        value="{{ old('color', $hasOverride ? ($override->local_color ?? '#10B981') : $leaveType->color) }}">
                                    <input 
                                        id="color" 
                                        name="color" 
                                        type="text" 
                                        class="form-control @error('color') is-invalid @enderror" 
                                        value="{{ old('color', $hasOverride ? ($override->local_color ?? '#10B981') : $leaveType->color) }}"
                                        {{ ($isSuperAdmin || !$hasOverride) ? 'required' : '' }} />
                                </div>
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leaveType->color)
                                    <small class="text-muted">Valeur globale : {{ $leaveType->color }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="requires_attachment" :value="__('Justificatif requis')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="requires_attachment" name="requires_attachment" class="form-select mt-1" 
                                    {{ $hasOverride && !$isSuperAdmin ? '' : 'required' }}>
                                    <option value="never" {{ old('requires_attachment', $hasOverride ? ($override->local_requires_attachment ?? 'never') : $leaveType->requires_attachment) == 'never' ? 'selected' : '' }}>Jamais</option>
                                    <option value="always" {{ old('requires_attachment', $hasOverride ? ($override->local_requires_attachment ?? 'never') : $leaveType->requires_attachment) == 'always' ? 'selected' : '' }}>Toujours</option>
                                    <option value="after_duration" {{ old('requires_attachment', $hasOverride ? ($override->local_requires_attachment ?? 'never') : $leaveType->requires_attachment) == 'after_duration' ? 'selected' : '' }}>Après une durée</option>
                                </select>
                                <x-input-error :messages="$errors->get('requires_attachment')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leaveType->requires_attachment)
                                    <small class="text-muted">Valeur globale : {{ $leaveType->requires_attachment }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Justificatif après durée -->
                        <div class="row g-3 mb-4" id="attachment_days_row" style="{{ old('requires_attachment', $hasOverride ? ($override->local_requires_attachment ?? 'never') : $leaveType->requires_attachment) == 'after_duration' ? '' : 'display: none;' }}">
                            <div class="col-lg-4">
                                <x-input-label for="requires_attachment_after" :value="__('Nombre de jours avant justificatif')" />
                                <input 
                                    id="requires_attachment_after" 
                                    name="requires_attachment_after" 
                                    type="number" 
                                    class="form-control mt-1 @error('requires_attachment_after') is-invalid @enderror" 
                                    value="{{ old('requires_attachment_after', $hasOverride ? ($override->local_requires_attachment_after ?? '') : $leaveType->requires_attachment_after) }}"
                                    min="1" />
                                <x-input-error :messages="$errors->get('requires_attachment_after')" class="mt-2" />
                                <small class="text-muted">{{ __('Le justificatif sera requis après X jours') }}</small>
                                @if($hasOverride && !$isSuperAdmin && $leaveType->requires_attachment_after)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->requires_attachment_after }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Gestion du solde -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-3">
                                <x-input-label for="deducts_balance" :value="__('Déduire du solde')" />
                                <div class="mt-1">
                                    <input type="hidden" name="deducts_balance" value="0">
                                    <input type="checkbox" id="deducts_balance" name="deducts_balance" value="1" 
                                        {{ old('deducts_balance', $hasOverride ? ($override->local_deducts_balance ?? true) : $leaveType->deducts_balance) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="deducts_balance" class="form-check-label ms-2">
                                        {{ __('Déduire automatiquement') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('deducts_balance')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leaveType->deducts_balance !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->deducts_balance ? 'Oui' : 'Non' }}</small>
                                @endif
                            </div>

                            <div class="col-lg-3">
                                <x-input-label for="allow_negative_balance" :value="__('Solde négatif autorisé')" />
                                <div class="mt-1">
                                    <input type="hidden" name="allow_negative_balance" value="0">
                                    <input type="checkbox" id="allow_negative_balance" name="allow_negative_balance" value="1" 
                                        {{ old('allow_negative_balance', $hasOverride ? ($override->local_allow_negative_balance ?? false) : $leaveType->allow_negative_balance) ? 'checked' : '' }} 
                                        class="form-check-input" onchange="toggleNegativeLimit()">
                                    <label for="allow_negative_balance" class="form-check-label ms-2">
                                        {{ __('Autoriser') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('allow_negative_balance')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leaveType->allow_negative_balance !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->allow_negative_balance ? 'Oui' : 'Non' }}</small>
                                @endif
                            </div>

                            <div class="col-lg-3" id="max_negative_row" style="{{ old('allow_negative_balance', $hasOverride ? ($override->local_allow_negative_balance ?? false) : $leaveType->allow_negative_balance) ? '' : 'display: none;' }}">
                                <x-input-label for="max_negative_limit" :value="__('Limite négative max')" />
                                <input 
                                    id="max_negative_limit" 
                                    name="max_negative_limit" 
                                    type="number" 
                                    class="form-control mt-1 @error('max_negative_limit') is-invalid @enderror" 
                                    value="{{ old('max_negative_limit', $hasOverride ? ($override->local_max_negative_limit ?? '') : $leaveType->max_negative_limit) }}"
                                    min="0" />
                                <x-input-error :messages="$errors->get('max_negative_limit')" class="mt-2" />
                                <small class="text-muted">{{ __('Laissez vide pour illimité') }}</small>
                                @if($hasOverride && !$isSuperAdmin && $leaveType->max_negative_limit !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->max_negative_limit }}</small>
                                @endif
                            </div>

                            <div class="col-lg-3">
                                <x-input-label for="is_active" :value="__('Actif')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                        {{ old('is_active', $hasOverride ? ($override->is_enabled ?? true) : $leaveType->is_active) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="is_active" class="form-check-label ms-2">
                                        {{ __('Type actif') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Règles de validation -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="min_notice_days" :value="__('Délai de prévenance (jours)')" />
                                <input 
                                    id="min_notice_days" 
                                    name="min_notice_days" 
                                    type="number" 
                                    class="form-control mt-1 @error('min_notice_days') is-invalid @enderror" 
                                    value="{{ old('min_notice_days', $hasOverride ? ($override->local_min_notice_days ?? 0) : ($leaveType->min_notice_days ?? 0)) }}"
                                    min="0" />
                                <x-input-error :messages="$errors->get('min_notice_days')" class="mt-2" />
                                <small class="text-muted">{{ __('Nombre de jours minimum avant le début du congé (0 = aucun délai)') }}</small>
                                @if($hasOverride && !$isSuperAdmin && $leaveType->min_notice_days !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->min_notice_days }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="max_duration_per_request" :value="__('Durée maximale par demande')" />
                                <input 
                                    id="max_duration_per_request" 
                                    name="max_duration_per_request" 
                                    type="number" 
                                    step="0.5"
                                    class="form-control mt-1 @error('max_duration_per_request') is-invalid @enderror" 
                                    value="{{ old('max_duration_per_request', $hasOverride ? ($override->local_max_duration_per_request ?? '') : $leaveType->max_duration_per_request) }}"
                                    min="0" />
                                <x-input-error :messages="$errors->get('max_duration_per_request')" class="mt-2" />
                                <small class="text-muted">{{ __('Laissez vide pour illimité') }}</small>
                                @if($hasOverride && !$isSuperAdmin && $leaveType->max_duration_per_request !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->max_duration_per_request }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="allow_overlap" :value="__('Autoriser les chevauchements')" />
                                <div class="mt-1">
                                    <input type="hidden" name="allow_overlap" value="0">
                                    <input type="checkbox" id="allow_overlap" name="allow_overlap" value="1" 
                                        {{ old('allow_overlap', $hasOverride ? ($override->local_allow_overlap ?? false) : ($leaveType->allow_overlap ?? false)) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="allow_overlap" class="form-check-label ms-2">
                                        {{ __('Autoriser') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('allow_overlap')" class="mt-2" />
                                <small class="text-muted">{{ __('Permet à un employé d\'avoir plusieurs congés qui se chevauchent') }}</small>
                                @if($hasOverride && !$isSuperAdmin && $leaveType->allow_overlap !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leaveType->allow_overlap ? 'Oui' : 'Non' }}</small>
                                @endif
                            </div>
                        </div>

                        <!-- Affecte l'effectif -->
                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="affects_team_availability" :value="__('Affecte l\'effectif de l\'équipe')" />
                                <div class="mt-1">
                                    <input type="hidden" name="affects_team_availability" value="0">
                                    <input type="checkbox" id="affects_team_availability" name="affects_team_availability" value="1" 
                                        {{ old('affects_team_availability', $leaveType->affects_team_availability ?? true) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="affects_team_availability" class="form-check-label ms-2">
                                        {{ __('Affecte l\'effectif') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('affects_team_availability')" class="mt-2" />
                                <small class="text-muted">{{ __('L\'absence réduit-elle le nombre de personnes disponibles dans l\'équipe ?') }}</small>
                            </div>
                        </div>

                        <!-- Site et personnalisation -->
                        @if($isSuperAdmin)
                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <x-input-label for="site_id" :value="__('Siège')" />
                                    <select id="site_id" name="site_id" class="form-select mt-1">
                                        <option value="">{{ __('Global (tous les sièges)') }}</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->ID }}" 
                                                {{ old('site_id', $leaveType->site_id) == $site->ID ? 'selected' : '' }}>
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
                                            {{ old('is_customizable', $leaveType->is_customizable) ? 'checked' : '' }} 
                                            class="form-check-input" {{ $hasOverride && !$isSuperAdmin ? 'disabled' : '' }}>
                                        <label for="is_customizable" class="form-check-label ms-2">
                                            {{ __('Personnalisable') }}
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                                    @if($hasOverride && !$isSuperAdmin)
                                        <small class="text-muted">Ce champ est géré au niveau global</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <x-primary-button class="btn btn-primary">
                                @if($hasOverride && !$isSuperAdmin)
                                    {{ __('Personnaliser') }}
                                @elseif($isGlobal && !$isSuperAdmin)
                                    {{ __('Personnaliser pour mon siège') }}
                                @else
                                    {{ __('Mettre à jour') }}
                                @endif
                            </x-primary-button>
                            <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                @elseif($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce type global n\'est pas personnalisable. Vous ne pouvez pas le modifier.') }}
                    </div>
                    <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Ce type est en lecture seule.') }}
                    </div>
                    <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @endif
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
            function toggleNegativeLimit() {
                const checkbox = document.getElementById('allow_negative_balance');
                const row = document.getElementById('max_negative_row');
                if (checkbox.checked) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                toggleNegativeLimit();
            });
        </script>
    @endpush
</x-app-layout>