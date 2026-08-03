<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="h4 font-weight-bold text-dark mb-0">
                Champs du formulaire — {{ $leaveType->name }}
            </h3>
            <a href="{{ route('leave-types.index') }}" class="btn btn-outline-secondary btn-sm">
                &larr; Retour au catalogue
            </a>
        </div>
    </x-slot>

    @push('scripts')
        @vite(['resources/js/rule-fields.js'])
        <script>
            window.csrfToken = "{{ csrf_token() }}";
            window.leaveTypeId = {{ $leaveType->id }};
            window.apiUrl = "{{ route('leave-types.rule-fields', $leaveType) }}";
            window.fields = @json($fields);
        </script>
    @endpush

    <div class="container-fluid py-4">
        <div class="row">
            {{-- Colonne gauche : formulaire d'ajout/édition --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0" id="formTitle">Ajouter un champ</h5>
                    </div>
                    <div class="card-body">
                        <form id="fieldForm" onsubmit="return false;">
                            <input type="hidden" id="fieldId">

                            <div class="mb-3">
                                <label class="form-label fw-bold">Clé technique <small class="text-muted">(snake_case)</small></label>
                                <input type="text" id="field_key" class="form-control" placeholder="ex: max_per_year" pattern="[a-z0-9_]+">
                                <div class="form-text">Uniquement minuscules, chiffres et underscores. Ex: <code>min_notice_days</code></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Type de champ</label>
                                <select id="field_type" class="form-select">
                                    <option value="number">Nombre</option>
                                    <option value="boolean">Oui / Non</option>
                                    <option value="select">Liste déroulante</option>
                                    <option value="text">Texte libre</option>
                                    <option value="formula">Formule (lecture seule)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Libellé affiché</label>
                                <input type="text" id="label" class="form-control" placeholder="ex: Maximum par an">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Valeur par défaut</label>
                                <input type="text" id="default_value" class="form-control" placeholder="ex: 25">
                            </div>

                            {{-- Options pour select --}}
                            <div class="mb-3" id="optionsBlock" style="display:none;">
                                <label class="form-label fw-bold">Options <small class="text-muted">(JSON)</small></label>
                                <textarea id="options" class="form-control font-monospace" rows="4" placeholder='[{"value":"manager","label":"Manager"}]'>[]</textarea>
                            </div>

                            {{-- Validation --}}
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Validation</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small">Min</label>
                                            <input type="number" id="val_min" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Max</label>
                                            <input type="number" id="val_max" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Step</label>
                                            <input type="text" id="val_step" class="form-control form-control-sm" placeholder="any">
                                        </div>
                                        <div class="col-6 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="val_required">
                                                <label class="form-check-label small" for="val_required">Obligatoire</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="formErrors" class="alert alert-danger py-2 small" style="display:none;"></div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary w-100" onclick="saveField()">
                                    <span id="btnText">Ajouter</span>
                                </button>
                                <button type="button" classbtn btn-outline-secondary" onclick="resetForm()" style="display:none;" id="btnCancel">
                                    Annuler
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <button type="button" class="btn btn-outline-info w-100" onclick="seedDefaults()" id="btnSeed"
                            {{ $fields->isNotEmpty() ? 'style=display:none;' : '' }}>
                            ⚡ Pré-remplir avec les 15 champs standards
                        </button>
                    </div>
                </div>
            </div>

            {{-- Colonne droite : liste des champs --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Champs configurés <span class="badge bg-secondary" id="fieldCount">{{ $fields->count() }}</span></h5>
                        <small class="text-muted">Glissez-déposez pour réordonner</small>
                    </div>
                    <div class="card-body p-0">
                        <div id="fieldsList" class="list-group list-group-flush">
                            @forelse($fields as $field)
                                <div class="list-group-item d-flex align-items-center gap-3" data-id="{{ $field['id'] }}" style="cursor: grab;">
                                    <span class="text-muted fs-5">⋮⋮</span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <code class="bg-light px-2 py-1 rounded">{{ $field['field_key'] }}</code>
                                            <span class="badge bg-{{ $field['field_type'] === 'number' ? 'primary' : ($field['field_type'] === 'boolean' ? 'success' : ($field['field_type'] === 'select' ? 'info' : 'secondary')) }}">
                                                {{ $field['field_type'] }}
                                            </span>
                                            <strong>{{ $field['label'] }}</strong>
                                        </div>
                                        <div class="small text-muted mt-1">
                                            Défaut: <code>{{ $field['default_value'] ?? '—' }}</code>
                                            @if($field['validation'])
                                                · Validation: {{ json_encode($field['validation']) }}
                                            @endif
                                            @if($field['options'])
                                                · Options: {{ count($field['options']) }} choix
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editField({{ $field['id'] }})">✏️</button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteField({{ $field['id'] }})">🗑️</button>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-5" id="emptyState">
                                    Aucun champ configuré.<br>
                                    Utilisez le formulaire à gauche ou le bouton "Pré-remplir".
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <strong>⚠️ Impact</strong><br>
                    Modifier les champs d'un type affecte <strong>toutes les policies</strong> de ce type dans tous les sièges.
                    Les employés verront le nouveau formulaire immédiatement.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>