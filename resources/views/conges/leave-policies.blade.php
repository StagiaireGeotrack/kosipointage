<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="h4 font-weight-bold text-dark mb-0">
                Paramétrage — Règles de Congés par Siège
            </h3>
            @if(auth()->user()->isTrueSuperAdmin())
                <a href="{{ route('leave-types.create') }}" class="btn btn-outline-primary">
                    + Nouveau type global
                </a>
            @endif
        </div>
    </x-slot>

    @push('scripts')
        @vite(['resources/js/conges-settings.js'])
        <script>
            window.csrfToken = "{{ csrf_token() }}";
            window.companyId = {{ $selectedSiegeId }};
        </script>
    @endpush

    <div class="container-fluid p-0">
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

        {{-- Tableau --}}
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

    {{-- Modal dynamique --}}
    <div class="modal-overlay" id="modal">
        <div class="modal-card" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <h2 id="modalTitle">Configurer les règles</h2>
            
            <form id="formPolicy" onsubmit="return false;">
                <input type="hidden" id="policyId">
                <input type="hidden" id="leaveTypeId">
                
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

                {{-- FORMULAIRE DYNAMIQUE --}}
                <div id="dynamicFormContainer" class="form-grid">
                    {{-- Rempli par JS --}}
                </div>

                <div class="form-group" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <label for="is_active">Actif pour ce siège</label>
                    <select id="is_active" class="form-control">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
                
                <div id="formErrors" class="mt-2" style="color: #dc2626; font-size: 0.875rem;"></div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="button" class="btn btn-primary" onclick="savePolicy()">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>