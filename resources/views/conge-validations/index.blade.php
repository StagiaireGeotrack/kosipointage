{{-- resources/views/conge-validations/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-patch-check me-2 text-primary"></i>
                {{ __('Validation des congés') }}
            </h2>
            @if($counts['en_cours'] > 0)
                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                    <i class="bi bi-hourglass-split me-1"></i>{{ $counts['en_cours'] }} en attente
                </span>
            @endif
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- -- Onglets rapides -- --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @php
                        $dateFilters = array_filter([
                            'date_from' => $filters['date_from'] ?? '',
                            'date_to'   => $filters['date_to']   ?? '',
                            'search'    => $filters['search']    ?? '',
                        ]);
                    @endphp
                    <a href="{{ route('conge-validations.index', $dateFilters) }}"
                       class="btn btn-sm {{ empty($filters['status']) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        Toutes <span class="badge bg-white text-dark ms-1">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('conge-validations.index', array_merge($dateFilters, ['status' => 'en_cours'])) }}"
                       class="btn btn-sm {{ ($filters['status'] ?? '') === 'en_cours' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                        <i class="bi bi-hourglass-split me-1"></i>En cours
                        <span class="badge bg-white text-dark ms-1">{{ $counts['en_cours'] }}</span>
                    </a>
                    <a href="{{ route('conge-validations.index', array_merge($dateFilters, ['status' => 'validated'])) }}"
                       class="btn btn-sm {{ ($filters['status'] ?? '') === 'validated' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="bi bi-check-circle me-1"></i>Validées
                        <span class="badge bg-white text-dark ms-1">{{ $counts['validated'] }}</span>
                    </a>
                    <a href="{{ route('conge-validations.index', array_merge($dateFilters, ['status' => 'not_validated'])) }}"
                       class="btn btn-sm {{ ($filters['status'] ?? '') === 'not_validated' ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="bi bi-x-circle me-1"></i>Refusées
                        <span class="badge bg-white text-dark ms-1">{{ $counts['not_validated'] }}</span>
                    </a>
                </div>

                {{-- -- Formulaire de filtres -- --}}
                <form action="{{ route('conge-validations.index') }}" method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">

                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold mb-1">Début (à partir du)</label>
                            <input type="date" name="date_from" class="form-control form-control-sm"
                                value="{{ $filters['date_from'] ?? '' }}">
                        </div>

                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold mb-1">Fin (jusqu'au)</label>
                            <input type="date" name="date_to" class="form-control form-control-sm"
                                value="{{ $filters['date_to'] ?? '' }}">
                        </div>

                        {{-- Filtre Siège : Super Admin uniquement --}}
                        @if($isSuperAdmin)
                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold mb-1">Siège</label>
                            <select name="siege_id" class="form-select form-select-sm">
                                <option value="">Tous les sièges</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ ($filters['siege_id'] ?? '') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold mb-1">Statut</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                <option value="en_cours"      {{ ($filters['status'] ?? '') === 'en_cours'      ? 'selected' : '' }}>En cours</option>
                                <option value="validated"     {{ ($filters['status'] ?? '') === 'validated'     ? 'selected' : '' }}>Validé</option>
                                <option value="not_validated" {{ ($filters['status'] ?? '') === 'not_validated' ? 'selected' : '' }}>Refusé</option>
                            </select>
                        </div>

                        <div class="col-6 col-md-4">
                            <label class="form-label small fw-semibold mb-1">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                value="{{ $filters['search'] ?? '' }}"
                                placeholder="Nom, email ou matricule...">
                        </div>

                        <div class="col-12 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('conge-validations.index') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                Réinitialiser
                            </a>
                        </div>

                    </div>

                    {{-- Badges des filtres actifs --}}
                    @if(array_filter($filters))
                        <div class="mt-2 d-flex flex-wrap gap-1 align-items-center">
                            <small class="text-muted me-1">Actifs :</small>
                            @if(!empty($filters['date_from']))
                                <span class="badge bg-light text-dark border"><i class="bi bi-calendar2 me-1"></i>à partir du {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y H:i') }}</span>
                            @endif
                            @if(!empty($filters['date_to']))
                                <span class="badge bg-light text-dark border"><i class="bi bi-calendar2 me-1"></i>Jusqu'au {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y H:i') }}</span>
                            @endif
                            @if(!empty($filters['siege_id']) && $isSuperAdmin)
                                @php $siegeNom = $sieges->firstWhere('ID', $filters['siege_id'])?->Nom ?? $filters['siege_id']; @endphp
                                <span class="badge bg-light text-dark border"><i class="bi bi-building me-1"></i>{{ $siegeNom }}</span>
                            @endif
                            @if(!empty($filters['status']))
                                @php $lbl = ['en_cours' => 'En cours', 'validated' => 'Validé', 'not_validated' => 'Refusé']; @endphp
                                <span class="badge bg-light text-dark border">{{ $lbl[$filters['status']] ?? $filters['status'] }}</span>
                            @endif
                            @if(!empty($filters['search']))
                                <span class="badge bg-light text-dark border"><i class="bi bi-search me-1"></i>{{ $filters['search'] }}</span>
                            @endif
                        </div>
                    @endif
                </form>

                {{-- -- Tableau -- --}}
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary" style="width:60px">#</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Matricule</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Nom / Prénom</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Contact</th>
                                @if($isSuperAdmin)
                                    <th class="text-uppercase small fw-semibold text-secondary">Siège</th>
                                @endif
                                <th class="text-uppercase small fw-semibold text-secondary">Début congé</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Fin congé</th>
                                <th class="text-uppercase small fw-semibold text-secondary" style="width:80px">Durée</th>
                                <th class="text-uppercase small fw-semibold text-secondary" style="width:100px">Statut</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Raison</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Raison rejet</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Demande le</th>
                                <th class="text-uppercase small fw-semibold text-secondary">Validé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($validations as $v)
                                @php
                                    $jours = (int) ceil($v->date_heure_debut->floatDiffInDays($v->date_heure_fin));
                                    $statusConfig = [
                                        'en_cours'      => ['class' => 'bg-warning text-dark', 'icon' => 'bi-hourglass-split', 'label' => 'En cours'],
                                        'validated'     => ['class' => 'bg-success',           'icon' => 'bi-check-circle',    'label' => 'Validé'],
                                        'not_validated' => ['class' => 'bg-danger',            'icon' => 'bi-x-circle',        'label' => 'Refusé'],
                                    ];
                                    $cfg = $statusConfig[$v->status] ?? ['class' => 'bg-secondary', 'icon' => 'bi-question', 'label' => $v->status];
                                @endphp
                                <tr class="{{ $v->status === 'en_cours' ? 'table-warning' : '' }} cv-row"
                                    style="cursor:pointer"
                                    data-bs-toggle="modal"
                                    data-bs-target="#cvModal"
                                    data-id="{{ $v->id }}"
                                    data-nom="{{ e($v->nom_prenom) }}"
                                    data-email="{{ e($v->email) }}"
                                     data-matricule="{{ e($v->matricule ?? '') }}"
                                     data-tel="{{ e($v->telephone ?? '') }}"
                                    data-debut="{{ $v->date_heure_debut->format('d/m/Y H:i') }}"
                                    data-fin="{{ $v->date_heure_fin->format('d/m/Y H:i') }}"
                                    data-jours="{{ $jours }}"
                                    data-raison="{{ e($v->raison ?? '') }}"
                                    data-raison-rejet="{{ e($v->raison_rejection ?? '') }}"
                                    data-status="{{ $v->status }}"
                                    data-status-label="{{ $cfg['label'] }}"
                                    data-approve-url="{{ route('conge-validations.approve', $v->id) }}"
                                    data-reject-url="{{ route('conge-validations.reject', $v->id) }}"
                                >
                                    <td class="text-muted small">{{ $v->id }}</td>
                                    <td class="small text-muted">{{ $v->matricule ?? '—' }}</td>
                                    <td class="fw-semibold">{{ $v->nom_prenom }}</td>
                                    <td class="small">
                                        <a href="mailto:{{ $v->email }}" class="text-decoration-none d-block">
                                            <i class="bi bi-envelope me-1 text-muted"></i>{{ $v->email }}
                                        </a>
                                        @if($v->telephone)
                                            <span class="text-muted">
                                                <i class="bi bi-telephone me-1"></i>{{ $v->telephone }}
                                            </span>
                                        @endif
                                    </td>
                                    @if($isSuperAdmin)
                                        <td class="small text-muted">{{ $v->siege?->Nom ?? '—' }}</td>
                                    @endif
                                    <td class="small">
                                        {{ $v->date_heure_debut->format('d/m/Y H:i') }}
                                        <span class="text-muted d-block" style="font-size:0.75rem">{{ $v->date_heure_debut->format('H:i') }}</span>
                                    </td>
                                    <td class="small">
                                        {{ $v->date_heure_fin->format('d/m/Y H:i') }}
                                        <span class="text-muted d-block" style="font-size:0.75rem">{{ $v->date_heure_fin->format('H:i') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            {{ $jours }}j
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $cfg['class'] }}">
                                            <i class="bi {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                        </span>
                                    </td>
                                    <td class="small text-muted" style="max-width:180px">
                                        @if($v->raison)
                                            <span title="{{ $v->raison }}">
                                                {{ Str::limit($v->raison, 60) }}
                                            </span>
                                        @else
                                            <span class="fst-italic text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="small" style="max-width:180px">
                                        @if($v->raison_rejection)
                                            <span class="text-danger" title="{{ $v->raison_rejection }}">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ Str::limit($v->raison_rejection, 60) }}
                                            </span>
                                        @else
                                            <span class="fst-italic text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if($v->date_creation)
                                            {{ $v->date_creation->format('d/m/Y H:i') }}
                                            <span class="d-block" style="font-size:0.75rem">{{ $v->date_creation->format('H:i') }}</span>
                                        @else &mdash;
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        @if($v->date_validation)
                                            {{ $v->date_validation->format('d/m/Y H:i') }}
                                            <span class="d-block" style="font-size:0.75rem">{{ $v->date_validation->format('H:i') }}</span>
                                        @else
                                            <span class="fst-italic text-muted">&mdash;</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Aucune demande de congé trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $validations->links('pagination.custom') }}
                </div>

            </div>
        </div>
    </div>

    {{-- --------------------------------------------------- --}}
    {{-- MODAL : Détail + Actions Validation / Rejet        --}}
    {{-- --------------------------------------------------- --}}
    <div class="modal fade" id="cvModal" tabindex="-1" aria-labelledby="cvModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                {{-- Header --}}
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="cvModalLabel">
                        <i class="bi bi-patch-check-fill me-2 text-primary"></i>Demande de congé
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-2">


                    {{-- -- Bloc info : liste ligne par ligne -- --}}
                    <table class="table table-bordered table-sm mb-3" style="font-size:0.82rem">
                        <colgroup><col style="width:35%"><col></colgroup>
                        <tbody>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem">Matricule</th><td class="fw-semibold py-1 px-2" id="cv-matricule">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem">Nom / Pr&#233;nom</th><td class="fw-bold py-1 px-2" id="cv-nom">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem">Statut</th><td class="py-1 px-2" id="cv-status-badge"></td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-envelope me-1"></i>Email</th><td class="py-1 px-2" id="cv-email">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-telephone me-1"></i>T&#233;l&#233;phone</th><td class="py-1 px-2" id="cv-tel">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-clock me-1"></i>Dur&#233;e</th><td class="fw-bold text-primary py-1 px-2" id="cv-jours">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-calendar-check me-1 text-success"></i>D&#233;but</th><td class="fw-semibold py-1 px-2" id="cv-debut">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-calendar-x me-1 text-danger"></i>Fin</th><td class="fw-semibold py-1 px-2" id="cv-fin">&mdash;</td></tr>
                            <tr><th class="bg-light text-muted text-uppercase py-1 px-2" style="font-size:0.67rem"><i class="bi bi-chat-left-text me-1"></i>Raison</th><td class="fst-italic text-muted py-1 px-2" id="cv-raison">&mdash;</td></tr>
                        </tbody>
                    </table>

                    {{-- -- Zone action Simple Admin (en_cours uniquement) -- --}}
                    <div id="cv-actions" style="display:none">
                        <hr class="my-3">
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" id="tab-approve"
                                    class="btn btn-success flex-fill"
                                    onclick="switchTab('approve')">
                                <i class="bi bi-check-circle-fill me-1"></i>Valider la demande
                            </button>
                            <button type="button" id="tab-reject"
                                    class="btn btn-outline-danger flex-fill"
                                    onclick="switchTab('reject')">
                                <i class="bi bi-x-circle-fill me-1"></i>Refuser la demande
                            </button>
                        </div>

                        {{-- Section Valider --}}
                        <div id="section-approve" class="p-3 border border-success rounded-3 bg-white">
                            <form id="form-approve" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-md-7">
                                        <label class="form-label fw-semibold">Employé <span class="text-danger">*</span></label>
                                        <select name="employee_id" class="form-select" required>
                                            <option value="">Sélectionner un employé...</option>
                                            @foreach($employes as $emp)
                                                <option value="{{ $emp->ID }}">
                                                    {{ $emp->Nom }}
                                                    @if($emp->num_mat) é {{ $emp->num_mat }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label class="form-label fw-semibold">Type de congé <span class="text-danger">*</span></label>
                                        <select name="type_conge" class="form-select" required>
                                            <option value="">Choisir...</option>
                                            <option value="CP">Congé Payé (CP)</option>
                                            <option value="RTT">RTT</option>
                                            <option value="Maladie">Maladie</option>
                                            <option value="Autres">Autres</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Commentaire</label>
                                        <textarea name="commentaire" class="form-control" rows="2"
                                                  placeholder="Remarque optionnelle..."></textarea>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="bi bi-check-circle-fill me-1"></i>Confirmer la validation
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Section Refuser --}}
                        <div id="section-reject" class="p-3 border border-danger rounded-3 bg-white mt-3" style="display:none">
                            <form id="form-reject" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Raison du refus <span class="text-danger">*</span></label>
                                    <textarea name="raison_rejection" class="form-control" rows="3"
                                              placeholder="Expliquer la raison du refus..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-danger px-4">
                                        <i class="bi bi-x-circle-fill me-1"></i>Confirmer le refus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- -- Zone lecture seule -- --}}
                    <div id="cv-readonly" style="display:none">
                        <hr class="my-3">
                        <div id="cv-readonly-alert" class="alert d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <span id="cv-readonly-msg"></span>
                        </div>
                        <div id="cv-rejet-block" style="display:none"
                             class="p-3 bg-danger bg-opacity-10 border border-danger rounded-3">
                            <p class="small text-muted mb-1">
                                <i class="bi bi-exclamation-triangle me-1"></i>Raison du refus
                            </p>
                            <p class="text-danger fw-semibold mb-0" id="cv-raison-rejet"></p>
                        </div>
                    </div>

                </div>{{-- /.modal-body --}}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const isSuperAdmin = {{ $isSuperAdmin ? 'true' : 'false' }};

    const statusClasses = {
        en_cours:      'bg-warning text-dark',
        validated:     'bg-success text-white',
        not_validated: 'bg-danger text-white',
    };

    const readonlyAlertClasses = {
        validated:     'alert-success',
        not_validated: 'alert-danger',
        en_cours:      'alert-info',
    };

    const statusMessages = {
        validated:     'Cette demande a été validée.',
        not_validated: 'Cette demande a été refusée.',
        en_cours:      'Cette demande est en attente (consultation seule).',
    };

    document.getElementById('cvModal').addEventListener('show.bs.modal', function (event) {
        const row = event.relatedTarget;
        if (!row) return;
        const d = row.dataset;

        document.getElementById('cv-matricule').textContent = d.matricule || '\u2014';
        document.getElementById('cv-nom').textContent       = d.nom       || '\u2014';
        document.getElementById('cv-email').textContent     = d.email     || '\u2014';
        document.getElementById('cv-tel').textContent       = d.tel       || '\u2014';
        document.getElementById('cv-debut').textContent     = d.debut     || '\u2014';
        document.getElementById('cv-fin').textContent       = d.fin       || '\u2014';
        document.getElementById('cv-jours').textContent     = d.jours ? d.jours + ' jour(s)' : '\u2014';
        document.getElementById('cv-raison').textContent    = d.raison    || '\u2014';

        document.getElementById('cv-status-badge').innerHTML =
            `<span class="badge ${statusClasses[d.status] || 'bg-secondary'} px-3 py-2">${d.statusLabel}</span>`;

        document.getElementById('form-approve').action = d.approveUrl;
        document.getElementById('form-reject').action  = d.rejectUrl;
        document.getElementById('form-approve').reset();
        document.getElementById('form-reject').reset();

        const forceReadonly = isSuperAdmin || d.status !== 'en_cours';

        if (forceReadonly) {
            document.getElementById('cv-actions').style.display  = 'none';
            document.getElementById('cv-readonly').style.display = '';
            const alertDiv = document.getElementById('cv-readonly-alert');
            alertDiv.className = 'alert d-flex align-items-center gap-2 mb-2 ' +
                (readonlyAlertClasses[d.status] || 'alert-secondary');
            document.getElementById('cv-readonly-msg').textContent = statusMessages[d.status] || d.statusLabel;
            const rejetBlock = document.getElementById('cv-rejet-block');
            if (d.status === 'not_validated' && d.raisonRejet) {
                rejetBlock.style.display = '';
                document.getElementById('cv-raison-rejet').textContent = d.raisonRejet;
            } else {
                rejetBlock.style.display = 'none';
            }
        } else {
            document.getElementById('cv-actions').style.display  = '';
            document.getElementById('cv-readonly').style.display = 'none';
            switchTab('approve');
        }
    });

    function switchTab(tab) {
        const isApprove = tab === 'approve';
        document.getElementById('section-approve').style.display = isApprove ? '' : 'none';
        document.getElementById('section-reject').style.display  = isApprove ? 'none' : '';
        document.getElementById('tab-approve').className = isApprove
            ? 'btn btn-success flex-fill' : 'btn btn-outline-success flex-fill';
        document.getElementById('tab-reject').className = isApprove
            ? 'btn btn-outline-danger flex-fill' : 'btn btn-danger flex-fill';
    }
    </script>
    @endpush

</x-app-layout>
