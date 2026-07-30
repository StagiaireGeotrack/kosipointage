<x-app-layout>
    {{-- Titre de page --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold text-dark mb-0">
                 Paramétrage — Types de Congés
            </h2>
            <button class="btn btn-primary" onclick="openModal()">
                + Nouveau type de congé
            </button>
        </div>
    </x-slot>

  @push('scripts')
    @vite(['resources/js/conges-settings.js'])
    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
@endpush

    <div class="container-fluid p-0">
        <p class="subtitle">Gérez ici tous les types de congés de votre siège. Aucun code à modifier.</p>

        {{-- Bandeau siège --}}
        <div class="siege-banner">
            <div>
                <strong>Siège actuel :</strong> {{ $selectedSiegeName }} (ID: {{ $selectedSiegeId }})
            </div>
            
            @if(auth()->user()->isTrueSuperAdmin() && $sieges)
                <form method="POST" action="{{ route('admin.select-siege') }}" class="m-0 d-flex align-items-center gap-2">
                    @csrf
                    <label class="mb-0 text-white font-weight-bold">Changer :</label>
                    <select name="siege_id" onchange="this.form.submit()" class="siege-select">
                        @foreach($sieges as $s)
                            <option value="{{ $s->ID }}" {{ $selectedSiegeId == $s->ID ? 'selected' : '' }}>
                                {{ $s->Nom }}
                            </option>
                        @endforeach
                    </select>
                </form>
            @endif
        </div>

        {{-- Barre d'outils --}}
        <div class="toolbar">
            <div>
                <span id="countBadge" class="badge-count">Chargement...</span>
            </div>
        </div>

        {{-- Tableau des données --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Unité</th>
                        <th>Décompte solde</th>
                        <th>Pièce justificative</th>
                        <th>Actif</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr><td colspan="7" class="empty">Chargement...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de création/édition --}}
    <div class="modal-overlay" id="modal">
        <div class="modal-card">
            <h2 id="modalTitle">Nouveau type de congé</h2>
            
            <form id="formType" onsubmit="return false;">
                <input type="hidden" id="typeId">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nom *</label>
                        <input type="text" id="name" placeholder="Ex: Congé Payé">
                    </div>
                    <div class="form-group">
                        <label for="code">Code *</label>
                        <input type="text" id="code" placeholder="Ex: CP" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="color">Couleur *</label>
                        <input type="color" id="color" value="#4CAF50">
                    </div>
                    <div class="form-group">
                        <label for="unit">Unité *</label>
                        <select id="unit">
                            <option value="days">Jours</option>
                            <option value="half_days">Demi-journées</option>
                            <option value="hours">Heures</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deducts_balance">Décompte solde</label>
                        <select id="deducts_balance">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="approval_required">Approbation requise</label>
                        <select id="approval_required">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="requires_attachment">Pièce justificative</label>
                        <select id="requires_attachment">
                            <option value="never">Jamais</option>
                            <option value="always">Toujours</option>
                            <option value="from_duration">À partir d'une durée</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attachment_threshold">Seuil pièce (jours)</label>
                        <input type="number" id="attachment_threshold" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label for="allow_negative_balance">Solde négatif autorisé</label>
                        <select id="allow_negative_balance">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="negative_limit">Limite négative</label>
                        <input type="number" id="negative_limit" value="0" min="0" step="0.5">
                    </div>
                    <div class="form-group">
                        <label for="visibility_level">Visibilité</label>
                        <select id="visibility_level">
                            <option value="all">Tous</option>
                            <option value="manager">Manager</option>
                            <option value="rh">RH</option>
                            <option value="admin">Admin uniquement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_active">Actif</label>
                        <select id="is_active">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                </div>
                
                <div id="formErrors" class="mt-2"></div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="saveType()">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            window.csrfToken = "{{ csrf_token() }}";
        </script>
        <script src="{{ asset('js/conges-settings.js') }}"></script>
    @endpush
</x-app-layout>