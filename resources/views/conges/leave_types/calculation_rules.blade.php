<x-app-layout>
    {{-- En-tête de page --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 fw-bold text-dark mb-0">
                Formules de calcul — {{ $leaveType->name }}
            </h2>
            <a href="{{ route('leave-types.rule-fields', $leaveType) }}" class="btn btn-outline-secondary btn-sm">
                &larr; Retour aux champs
            </a>
        </div>
    </x-slot>

    {{-- Scripts spécifiques à cette vue --}}
   @push('scripts')
    {{-- 1. D'ABORD les variables globales --}}
    <script>
    window.csrfToken = "{{ csrf_token() }}";
    window.leaveTypeId = {{ $leaveType->id }};
    window.apiUrl = "{{ route('leave-types.calculations.store', $leaveType) }}";
    window.updateUrl = "{{ route('calculation-rules.update', ':id') }}";
    window.destroyUrl = "{{ route('calculation-rules.destroy', ':id') }}";
    window.testUrl = "{{ route('calculation-rules.test') }}";
    window.rules = @json($rules);
    window.variables = @json($variables);
</script>

    {{-- 2. ENSUITE le fichier JS qui les utilise --}}
    @vite(['resources/js/calculation-rules.js'])
@endpush

    <div class="container-fluid py-4">
        <div class="row g-4">
            {{-- Colonne gauche : formulaire + aide --}}
            <div class="col-lg-5">
                {{-- Carte formulaire --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0" id="formTitle">Ajouter une formule</h5>
                    </div>

                    <div class="card-body">
                        <form id="ruleForm" onsubmit="return false;" novalidate>
                            <input type="hidden" id="ruleId">

                            {{-- Nom --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nom de la formule</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    placeholder="ex : Droits acquis mensuels"
                                    autocomplete="off"
                                >
                            </div>

                            {{-- Variable de sortie --}}
                            <div class="mb-3">
                                <label for="output_variable" class="form-label fw-bold">
                                    Variable de sortie
                                    <small class="text-muted fw-normal">(snake_case)</small>
                                </label>
                                <input
                                    type="text"
                                    id="output_variable"
                                    name="output_variable"
                                    class="form-control"
                                    placeholder="ex : entitlement"
                                    aria-describedby="outputHelp"
                                >
                                <div id="outputHelp" class="form-text">
                                    Le résultat sera stocké sous ce nom. Ex : <code>entitlement</code>, <code>balance</code>, <code>bonus</code>.
                                </div>
                            </div>

                            {{-- Formule --}}
                            <div class="mb-3">
                                <label for="formula" class="form-label fw-bold">Formule</label>
                                <textarea
                                    id="formula"
                                    name="formula"
                                    class="form-control font-monospace"
                                    rows="4"
                                    placeholder="(max_per_year / 12) * mois_travailles"
                                    aria-describedby="formulaHelp"
                                ></textarea>
                                <div id="formulaHelp" class="form-text">
                                    <span class="fw-semibold">Variables disponibles :</span>
                                    <ul class="list-unstyled mb-0 mt-1">
                                        @foreach($variables as $key => $label)
                                            <li>
                                                <code class="me-1">{{ $key }}</code>
                                                <small class="text-muted">{{ $label }}</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- Actif / Inactif --}}
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Formule active</label>
                                </div>
                            </div>

                            {{-- Erreurs --}}
                            <div id="formErrors" class="alert alert-danger py-2 small d-none" role="alert"></div>

                            {{-- Actions --}}
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary flex-fill" onclick="saveRule()">
                                    <span id="btnText">Ajouter</span>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-info"
                                    onclick="testFormula()"
                                    title="Tester avec des valeurs fictives"
                                    aria-label="Tester la formule"
                                >
                                    🧪 <span class="d-none d-sm-inline">Tester</span>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="btnCancel"
                                    onclick="resetForm()"
                                    style="display: none;"
                                >
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Carte d'aide syntaxe --}}
                <div class="card bg-light border-0 mt-3">
                    <div class="card-body">
                        <h6 class="card-title">🧮 Syntaxe des formules</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><code>+ - * /</code> — opérations de base</li>
                            <li><code>min(a, b)</code>, <code>max(a, b)</code> — minimum / maximum</li>
                            <li><code>floor(x)</code>, <code>ceil(x)</code>, <code>round(x)</code> — arrondis</li>
                            <li><code>if(condition, valeur_si_vrai, valeur_si_faux)</code> — condition</li>
                            <li class="mt-1 text-muted">
                                Exemple : <code>if(anciennete_annees &gt; 5, max_per_year + 2, max_per_year)</code>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Colonne droite : liste des formules --}}
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Formules configurées
                            <span class="badge bg-secondary" id="ruleCount">{{ $rules->count() }}</span>
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        <div id="rulesList" class="list-group list-group-flush">
                            @forelse($rules as $rule)
                                <div class="list-group-item" data-id="{{ $rule['id'] }}">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="min-w-0">
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <code class="bg-success text-white px-2 py-1 rounded small">${{ $rule['output_variable'] }}</code>
                                                <strong class="text-break">{{ $rule['name'] }}</strong>
                                                @if(!$rule['is_active'])
                                                    <span class="badge bg-warning text-dark">Inactif</span>
                                                @endif
                                            </div>
                                            <div class="mt-2 p-2 bg-light rounded font-monospace small text-break">
                                                {{ $rule['formula'] }}
                                            </div>
                                        </div>

                                        <div class="d-flex gap-1 flex-shrink-0">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="editRule({{ $rule['id'] }})"
                                                aria-label="Modifier {{ $rule['name'] }}"
                                                title="Modifier"
                                            >
                                                ✏️
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="deleteRule({{ $rule['id'] }})"
                                                aria-label="Supprimer {{ $rule['name'] }}"
                                                title="Supprimer"
                                            >
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted py-5" id="emptyState">
                                    <p class="mb-0">Aucune formule configurée.</p>
                                    <p class="mb-0 small">Créez votre première formule de calcul à l'aide du formulaire.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de test --}}
    <div
        class="modal fade"
        id="testModal"
        tabindex="-1"
        aria-labelledby="testModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalLabel">🧪 Tester la formule</h5>
                    <button
                        type="button"
                        class="btn-close"
                        onclick="closeTestModal()"
                        aria-label="Fermer"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Formule</label>
                        <div class="p-2 bg-light rounded font-monospace small text-break" id="testFormulaDisplay"></div>
                    </div>

                    <div id="testVariables">
                        {{-- Généré dynamiquement par JS --}}
                    </div>

                    <div id="testResult" class="alert mt-3 d-none" role="status"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTestModal()">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="runTest()">Calculer</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>