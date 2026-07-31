<x-app-layout>
    {{-- Titre de page --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold text-dark mb-0">
                Paramétrage — Règles de Congés par Siège
            </h2>
            {{-- Seul le super admin peut ajouter un type au catalogue global --}}
            @if(auth()->user()->isTrueSuperAdmin())
                <button class="btn btn-outline-primary" onclick="openTypeModal()">
                    + Nouveau type global
                </button>
            @endif
        </div>
    </x-slot>

    @push('scripts')
    @vite(['resources/js/conges-settings.js'])
    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
@endpush

    <div class="container-fluid p-0">
        <p class="subtitle">
            Configurez ici les règles de chaque type de congé pour votre siège. 
            Les types (CP, RTT...) sont partagés, mais les règles sont privées à ce siège.
        </p>

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

        {{-- Tableau des policies du siège --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Code</th>
                        <th>Règles configurées</th>
                        <th style="width: 90px;">Actif</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr><td colspan="5" class="empty">Chargement...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal d'édition des RÈGLES d'une policy --}}
    <div class="modal-overlay" id="modal">
        <div class="modal-card" style="max-width: 700px;">
            <h2 id="modalTitle">Modifier les règles</h2>
            
            <form id="formPolicy" onsubmit="return false;">
                <input type="hidden" id="policyId">
                
                {{-- Infos du type global (lecture seule) --}}
                <div class="form-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <div class="form-group">
                        <label>Nom du type</label>
                        <input type="text" id="typeName" readonly class="form-control bg-light">
                    </div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" id="typeCode" readonly class="form-control bg-light">
                    </div>
                    <div class="form-group">
                        <label>Couleur</label>
                        <input type="color" id="typeColor" readonly class="form-control" style="height: 38px;">
                    </div>
                </div>

                {{-- Règles modifiables --}}
                <div class="form-grid">
                    <div class="form-group">
                        <label for="min_notice_days">Préavis minimum (jours)</label>
                        <input type="number" id="min_notice_days" min="0" value="15">
                    </div>
                    <div class="form-group">
                        <label for="max_per_year">Max par an</label>
                        <input type="number" id="max_per_year" min="1" value="25">
                    </div>
                    <div class="form-group">
                        <label for="max_consecutive_days">Max consécutifs (jours)</label>
                        <input type="number" id="max_consecutive_days" min="1" value="24">
                    </div>
                    <div class="form-group">
                        <label for="max_carryover_days">Report max (jours)</label>
                        <input type="number" id="max_carryover_days" min="0" value="5">
                    </div>
                    <div class="form-group">
                        <label for="min_duration_days">Durée minimale (jours)</label>
                        <input type="number" id="min_duration_days" min="0.5" step="0.5" value="0.5">
                    </div>
                    <div class="form-group">
                        <label for="requires_approval_from">Approbation par</label>
                        <select id="requires_approval_from">
                            <option value="manager">Manager</option>
                            <option value="rh">RH</option>
                            <option value="direction">Direction</option>
                            <option value="manager_then_rh">Manager puis RH</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="allow_half_day">Demi-journée autorisée</label>
                        <select id="allow_half_day">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exclude_weekends">Exclure week-ends</label>
                        <select id="exclude_weekends">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exclude_holidays">Exclure jours fériés</label>
                        <select id="exclude_holidays">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
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
                        <input type="number" id="attachment_threshold" value="0" min="0" step="0.5">
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
                        <label for="is_active">Actif pour ce siège</label>
                        <select id="is_active">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                </div>
                
                <div id="formErrors" class="mt-2"></div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="savePolicy()">Enregistrer les règles</button>
                </div>
            </form>
        </div>
    </div>

 
</x-app-layout>