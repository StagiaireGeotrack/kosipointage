<x-app-layout>
    @push('styles')
@vite(['resources/css/conges-settings.css'])
    @endpush

    <x-slot name="header">
        <div class="settings-header">
            <h2 class="settings-title">
                {{ __('Paramètres des Congés & Fériés par Siège') }}
            </h2>
            <a href="{{ route('conges.index') }}" class="btn-action-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="settings-container">
        <!-- Sélecteur de siège -->
        <div class="siege-card">
            <div class="siege-card-body">
                <form method="GET" action="{{ route('conges.settings') }}" id="siegeForm">
                    <div class="siege-form-row">
                        <label for="SiegeID" class="siege-label">{{ __('Choisir le siège à configurer :') }}</label>
                        <select id="SiegeID" name="SiegeID" class="siege-select" onchange="document.getElementById('siegeForm').submit()">
                            @foreach($sieges as $siege)
                                <option value="{{ $siege->ID }}" {{ $selectedSiegeId == $siege->ID ? 'selected' : '' }}>
                                    [ID: {{ $siege->ID }}] {{ $siege->Nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Structure principale avec Onglets d'accès aux 8 sous-sections -->
        <div class="settings-card">
            <div class="settings-card-body">
                <div class="row g-4">
                    
                    <!-- Menu latéral des sous-sections -->
                    <div class="col-12 col-md-3 border-end pe-md-3">
                        <div class="nav flex-column nav-pills gap-1" id="settings-tabs" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-start" id="tab-types" data-bs-toggle="pill" data-bs-target="#content-types" type="button" role="tab">
                                {{ __('Types de congés') }}
                            </button>
                            <button class="nav-link text-start" id="tab-regles" data-bs-toggle="pill" data-bs-target="#content-regles" type="button" role="tab">
                                {{ __('Règles d’acquisition et de décompte') }}
                            </button>
                            <button class="nav-link text-start" id="tab-periodes" data-bs-toggle="pill" data-bs-target="#content-periodes" type="button" role="tab">
                                {{ __('Périodes de référence') }}
                            </button>
                            <button class="nav-link text-start" id="tab-soldes" data-bs-toggle="pill" data-bs-target="#content-soldes" type="button" role="tab">
                                {{ __('Soldes initiaux') }}
                            </button>
                            <button class="nav-link text-start" id="tab-historique" data-bs-toggle="pill" data-bs-target="#content-historique" type="button" role="tab">
                                {{ __('Congés déjà validés / Reprise historique') }}
                            </button>
                            <button class="nav-link text-start" id="tab-feries" data-bs-toggle="pill" data-bs-target="#content-feries" type="button" role="tab">
                                {{ __('Jours fériés et jours non travaillés') }}
                            </button>
                            <button class="nav-link text-start" id="tab-validation" data-bs-toggle="pill" data-bs-target="#content-validation" type="button" role="tab">
                                {{ __('Circuits de validation') }}
                            </button>
                            <button class="nav-link text-start" id="tab-notifications" data-bs-toggle="pill" data-bs-target="#content-notifications" type="button" role="tab">
                                {{ __('Notifications') }}
                            </button>
                        </div>
                    </div>

                    <!-- Contenu des onglets -->
                    <div class="col-12 col-md-9 ps-md-4">
                        <div class="tab-content" id="settings-tab-content">

                            <!-- Section 1 : Types de Congés -->
                            <div class="tab-pane fade show active" id="content-types" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="settings-card-title mb-0">{{ __('Types de congés du siège') }}</h5>
                                    <button class="btn-action-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">+ Ajouter</button>
                                </div>
                                <table class="settings-table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Nom</th>
                                            <th>Décompte solde</th>
                                            <th>Couleur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leaveTypes as $type)
                                            <tr>
                                                <td><code class="code-block">{{ $type->code }}</code></td>
                                                <td>{{ $type->name }}</td>
                                                <td>
                                                    <span class="badge-status {{ $type->deducts_balance ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ $type->deducts_balance ? 'Oui' : 'Non' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="color-preview" style="background-color: {{ $type->color ?? '#6c757d' }}"></span>
                                                </td>
                                                <td>
                                                    <button class="btn-action-danger" onclick="deleteItem('/api/v1/leave-types/{{ $type->id }}')">Supprimer</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="empty-cell">Aucun type défini pour ce siège.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Section 2 : Règles d’acquisition et de décompte -->
                            <div class="tab-pane fade" id="content-regles" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Règles d’acquisition et de décompte') }}</h5>
                                <p class="text-muted small">Configuration des modes de calcul (jours ouvrés / ouvrables), règles d'acquisition mensuelle et de report.</p>
                                {{-- Formulaire ou contenu des règles --}}
                            </div>

                            <!-- Section 3 : Périodes de référence -->
                            <div class="tab-pane fade" id="content-periodes" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Périodes de référence') }}</h5>
                                <p class="text-muted small">Définition des exercices annuels de congés (ex: du 1er juin au 31 mai).</p>
                                {{-- Formulaire des périodes --}}
                            </div>

                            <!-- Section 4 : Soldes initiaux -->
                            <div class="tab-pane fade" id="content-soldes" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Soldes initiaux') }}</h5>
                                <p class="text-muted small">Attribution initiale et remise à zéro des soldes de congés par employé.</p>
                                {{-- Formulaire ou tableau des soldes --}}
                            </div>

                            <!-- Section 5 : Congés déjà validés / reprise historique -->
                            <div class="tab-pane fade" id="content-historique" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Congés déjà validés / Reprise historique') }}</h5>
                                <p class="text-muted small">Saisie ou importation des données historiques avant le démarrage du système.</p>
                                {{-- Formulaire d'import / historique --}}
                            </div>

                            <!-- Section 6 : Jours fériés et jours non travaillés -->
                            <div class="tab-pane fade" id="content-feries" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="settings-card-title mb-0">{{ __('Jours Fériés et jours non travaillés') }}</h5>
                                    <button class="btn-action-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal">+ Ajouter</button>
                                </div>
                                <table class="settings-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Nom du jour</th>
                                            <th>Récurrent</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($holidays as $holiday)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($holiday->date)->format('d/m/Y') }}</td>
                                                <td>{{ $holiday->name }}</td>
                                                <td>
                                                    <span class="badge-status {{ $holiday->is_recurring ? 'badge-info' : 'badge-secondary' }}">
                                                        {{ $holiday->is_recurring ? 'Oui' : 'Non' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn-action-danger" onclick="deleteItem('/api/v1/company-holidays/{{ $holiday->id }}')">Supprimer</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="empty-cell">Aucun jour férié pour ce siège.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Section 7 : Circuits de validation -->
                            <div class="tab-pane fade" id="content-validation" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Circuits de validation') }}</h5>
                                <p class="text-muted small">Paramétrage du workflow et des niveaux d'approbation (N+1, RH, etc.).</p>
                                {{-- Formulaire circuit de validation --}}
                            </div>

                            <!-- Section 8 : Notifications -->
                            <div class="tab-pane fade" id="content-notifications" role="tabpanel">
                                <h5 class="settings-card-title mb-3">{{ __('Notifications') }}</h5>
                                <p class="text-muted small">Configuration des envois d'e-mails automatiques et des alertes de validation.</p>
                                {{-- Formulaire notifications --}}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1 : Ajouter Type de Congé -->
    <div class="modal fade" id="addTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addTypeForm" onsubmit="submitForm(event, '/api/v1/leave-types')">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouveau Type de Congé</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="company_id" value="{{ $selectedSiegeId }}">
                        <div class="mb-3">
                            <label class="form-label">Code (ex: RTT, CP)</label>
                            <input type="text" name="code" class="form-control" required maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control" required maxlength="100">
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Unité</label>
                                <select name="unit" class="form-select">
                                    <option value="days">Jours</option>
                                    <option value="hours">Heures</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Couleur</label>
                                <input type="color" name="color" class="form-control form-control-color w-100" value="#0d6efd">
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="deducts_balance" value="1" class="form-check-input" id="deducts" checked>
                            <label class="form-check-label" for="deducts">Décompte du solde de congé</label>
                        </div>
                        <input type="hidden" name="requires_attachment" value="never">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 2 : Ajouter Jour Férié -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addHolidayForm" onsubmit="submitForm(event, '/api/v1/company-holidays')">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouveau Jour Férié</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="company_id" value="{{ $selectedSiegeId }}">
                        <div class="mb-3">
                            <label class="form-label">Nom du jour (ex: Lundi de Pâques)</label>
                            <input type="text" name="name" class="form-control" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_recurring" value="1" class="form-check-input" id="recurring" checked>
                            <label class="form-check-label" for="recurring">Récurrent chaque année</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function submitForm(event, url) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {};
            
            formData.forEach((value, key) => {
                if (key === 'deducts_balance' || key === 'is_recurring') {
                    data[key] = true;
                } else {
                    data[key] = value;
                }
            });

            if (!data.hasOwnProperty('deducts_balance') && event.target.id === 'addTypeForm') {
                data['deducts_balance'] = false;
            }
            if (!data.hasOwnProperty('is_recurring') && event.target.id === 'addHolidayForm') {
                data['is_recurring'] = false;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + (res.message || 'Champs invalides'));
                }
            })
            .catch(err => alert('Erreur serveur'));
        }

        function deleteItem(url) {
            if (!confirm('Voulez-vous vraiment supprimer cet élément ?')) return;
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then(() => window.location.reload());
        }
    </script>
    @endpush
</x-app-layout>