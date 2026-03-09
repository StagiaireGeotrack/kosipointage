{{-- resources/views/evenements/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                {{ __('Événements de pointage') }}
                @if($totalCount > 0)
                    <span class="badge bg-danger ms-2">{{ $totalCount }}</span>
                @else
                    <span class="badge bg-success ms-2">{{ __('Tout est correct') }}</span>
                @endif
            </h2>
            @if($readOnly)
                <span class="badge bg-secondary fs-6 px-3 py-2">
                    <i class="bi bi-eye me-1"></i>{{ __('Lecture seule') }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="p-2">

        {{-- Alerte exports bloqués (Simple Admin uniquement) --}}
        @if(!$readOnly && $totalCount > 0)
        <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-lock-fill fs-4 me-3 flex-shrink-0"></i>
            <div>
                <strong>{{ __('Exports des rapports bloqués') }}</strong> —
                {{ __('Vous avez') }} <strong>{{ $totalCount }}</strong> {{ __('événement(s) non résolu(s).') }}
                {{ __('Corrigez-les tous pour débloquer les exports.') }}
            </div>
        </div>
        @endif

        @if(!$readOnly && $totalCount === 0)
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="bi bi-unlock-fill fs-4 me-3 flex-shrink-0"></i>
            <div>
                <strong>{{ __('Exports débloqués') }}</strong> —
                {{ __('Aucun événement non résolu. Vous pouvez exporter vos rapports.') }}
                <a href="{{ route('reports.index') }}" class="alert-link ms-2">
                    {{ __('Accéder aux rapports →') }}
                </a>
            </div>
        </div>
        @endif

        {{-- Messages flash --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Filtres --}}
                <form method="GET" action="{{ route('evenements.index') }}" class="mb-4">
                    <div class="row g-3">

                        {{-- Filtre siège (Super Admin uniquement) --}}
                        @if($readOnly && $sieges->count() > 0)
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold">{{ __('Siège') }}</label>
                            <select name="SiegeID" class="form-select">
                                <option value="">{{ __('Tous les sièges') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ $siegeId == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Filtre par type d'erreur --}}
                        <div class="col-12 col-md-{{ $readOnly ? '4' : '6' }}">
                            <label class="form-label fw-semibold">{{ __('Type d\'événement') }}</label>
                            <select name="filter_type" class="form-select">
                                <option value="">{{ __('Tous les types') }}</option>
                                <option value="doublon_entree"      {{ $filterType === 'doublon_entree'      ? 'selected' : '' }}>{{ __('Doublon d\'entrée') }}</option>
                                <option value="doublon_sortie"      {{ $filterType === 'doublon_sortie'      ? 'selected' : '' }}>{{ __('Doublon de sortie') }}</option>
                                <option value="manque_sortie"       {{ $filterType === 'manque_sortie'       ? 'selected' : '' }}>{{ __('Manque de sortie') }}</option>
                                <option value="manque_entree"       {{ $filterType === 'manque_entree'       ? 'selected' : '' }}>{{ __('Manque d\'entrée') }}</option>
                                <option value="pointage_jour_ferie" {{ $filterType === 'pointage_jour_ferie' ? 'selected' : '' }}>{{ __('Jour férié') }}</option>
                                <option value="pointage_weekend"    {{ $filterType === 'pointage_weekend'    ? 'selected' : '' }}>{{ __('Weekend') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-{{ $readOnly ? '4' : '6' }} d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i>{{ __('Filtrer') }}
                            </button>
                            <a href="{{ route('evenements.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Compteurs par type (badges de filtre rapide) --}}
                @php
                    $filtered = $filterType
                        ? $erreurs->filter(fn($e) => $e->type === $filterType)->values()
                        : $erreurs->values();

                    $counts = [
                        'doublon_entree'      => $erreurs->where('type', 'doublon_entree')->count(),
                        'doublon_sortie'      => $erreurs->where('type', 'doublon_sortie')->count(),
                        'manque_sortie'       => $erreurs->where('type', 'manque_sortie')->count(),
                        'manque_entree'       => $erreurs->where('type', 'manque_entree')->count(),
                        'pointage_jour_ferie' => $erreurs->where('type', 'pointage_jour_ferie')->count(),
                        'pointage_weekend'    => $erreurs->where('type', 'pointage_weekend')->count(),
                    ];

                    $typeMeta = [
                        'doublon_entree'      => ['label' => 'Doublons entrée',  'color' => 'danger'],
                        'doublon_sortie'      => ['label' => 'Doublons sortie',  'color' => 'warning'],
                        'manque_sortie'       => ['label' => 'Manque sortie',    'color' => 'warning'],
                        'manque_entree'       => ['label' => 'Manque entrée',    'color' => 'info'],
                        'pointage_jour_ferie' => ['label' => 'Jours fériés',     'color' => 'secondary'],
                        'pointage_weekend'    => ['label' => 'Weekends',         'color' => 'secondary'],
                    ];
                @endphp

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('evenements.index', array_merge(request()->except('filter_type'), $readOnly ? [] : [])) }}"
                       class="btn btn-sm {{ !$filterType ? 'btn-dark' : 'btn-outline-dark' }}">
                        {{ __('Tous') }}
                        <span class="badge {{ !$filterType ? 'bg-white text-dark' : 'bg-dark' }} ms-1">{{ $totalCount }}</span>
                    </a>
                    @foreach($typeMeta as $key => $meta)
                        @if($counts[$key] > 0)
                        <a href="{{ route('evenements.index', array_merge(request()->except('filter_type'), ['filter_type' => $key])) }}"
                           class="btn btn-sm {{ $filterType === $key ? 'btn-'.$meta['color'] : 'btn-outline-'.$meta['color'] }}">
                            {{ __($meta['label']) }}
                            <span class="badge {{ $filterType === $key ? 'bg-white text-dark' : 'bg-'.$meta['color'] }} ms-1">
                                {{ $counts[$key] }}
                            </span>
                        </a>
                        @endif
                    @endforeach
                </div>

                {{-- ═══════════════════════════════════════════════════════════ --}}
                {{-- LISTE DES ERREURS                                          --}}
                {{-- ═══════════════════════════════════════════════════════════ --}}

                @forelse($filtered as $erreur)
                <div class="card border-{{ $erreur->color }} mb-3 shadow-sm">

                    {{-- En-tête de la carte erreur --}}
                    <div class="card-header bg-{{ $erreur->color }} bg-opacity-10 d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi {{ $erreur->icon }} text-{{ $erreur->color }} fs-5"></i>
                            <span class="badge bg-{{ $erreur->color }}">{{ __($erreur->label) }}</span>
                            <strong>{{ $erreur->employee->Nom ?? '—' }}</strong>
                            @if($erreur->employee->num_mat ?? false)
                                <span class="text-muted small">· N° {{ $erreur->employee->num_mat }}</span>
                            @endif
                        </div>
                        <div class="text-muted small d-flex align-items-center gap-2">
                            <span>
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ ucfirst($erreur->date->isoFormat('dddd D MMMM YYYY')) }}
                            </span>
                            @if($erreur->extra)
                                <span class="badge bg-secondary">{{ $erreur->extra }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body py-3">

                        {{-- Tableau des pointages concernés --}}
                        @if($erreur->pointages->count() > 0)
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small text-secondary text-uppercase">#</th>
                                        <th class="small text-secondary text-uppercase">{{ __('Heure') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('Type') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('Méthode') }}</th>
                                        <th class="small text-secondary text-uppercase">{{ __('GPS') }}</th>
                                        @if(!$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']))
                                            <th class="small text-secondary text-uppercase">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($erreur->pointages as $i => $p)
                                    <tr class="{{ !$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']) && $i > 0 ? 'table-danger' : '' }}">
                                        <td class="text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}</strong>
                                            @if(!$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']) && $i > 0)
                                                <span class="badge bg-danger ms-1 small">{{ __('doublon') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $p->type_ === 'entry' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $p->type_ === 'entry' ? __('Entrée') : __('Sortie') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $p->auth_method }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            @if($p->latitude && $p->longitude && ($p->latitude != 0 || $p->longitude != 0))
                                                {{ number_format($p->latitude, 4) }}, {{ number_format($p->longitude, 4) }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        {{-- Bouton supprimer doublon --}}
                                        @if(!$readOnly && in_array($erreur->type, ['doublon_entree', 'doublon_sortie']))
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete"
                                                data-id="{{ $p->ID }}"
                                                data-heure="{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}"
                                                data-employe="{{ $erreur->employee->Nom ?? '' }}">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-md-inline ms-1">{{ __('Supprimer') }}</span>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-muted small mb-2">
                            <em>{{ __('Aucun pointage trouvé pour ce critère.') }}</em>
                        </p>
                        @endif

                        {{-- ── Actions de correction (Simple Admin uniquement) ── --}}
                        @if(!$readOnly)

                            {{-- Ajouter un pointage manquant --}}
                            @if(in_array($erreur->type, ['manque_sortie', 'manque_entree']))
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <i class="bi bi-info-circle text-muted small"></i>
                                <span class="text-muted small">
                                    {{ $erreur->type === 'manque_sortie'
                                        ? __('Aucune sortie enregistrée ce jour — ajoutez-la manuellement.')
                                        : __('Aucune entrée enregistrée ce jour — ajoutez-la manuellement.') }}
                                </span>
                                <button type="button"
                                    class="btn btn-sm btn-primary ms-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAjoutPointage"
                                    data-employee-id="{{ $erreur->employee->ID }}"
                                    data-employee-nom="{{ $erreur->employee->Nom }}"
                                    data-date="{{ $erreur->date->format('Y-m-d') }}"
                                    data-type="{{ $erreur->manqueType }}"
                                    data-label="{{ $erreur->manqueType === 'entry' ? __('Ajouter une entrée') : __('Ajouter une sortie') }}">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    {{ $erreur->manqueType === 'entry' ? __('Ajouter une entrée') : __('Ajouter une sortie') }}
                                </button>
                            </div>
                            @endif

                            {{-- Acknowledger (jour férié / weekend) --}}
                            @if(in_array($erreur->type, ['pointage_jour_ferie', 'pointage_weekend']))
                            <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                                <i class="bi bi-info-circle text-muted small"></i>
                                <span class="text-muted small">
                                    {{ __('Ce pointage est sur un jour non travaillé. S\'il est intentionnel (astreinte, heures sup), marquez-le.') }}
                                </span>
                                <div class="ms-auto d-flex gap-2">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAcknowledge"
                                        data-employee-id="{{ $erreur->employee->ID }}"
                                        data-employee-nom="{{ $erreur->employee->Nom }}"
                                        data-date="{{ $erreur->date->format('Y-m-d') }}"
                                        data-date-display="{{ ucfirst($erreur->date->isoFormat('dddd D MMMM YYYY')) }}"
                                        data-error-type="{{ $erreur->type }}">
                                        <i class="bi bi-check-circle me-1"></i>{{ __('Intentionnel') }}
                                    </button>
                                </div>
                            </div>
                            @endif

                        @endif
                        {{-- fin if !readOnly --}}

                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
                    <h5 class="mt-3 text-success fw-semibold">{{ __('Aucun événement') }}</h5>
                    <p class="text-muted">
                        @if($filterType)
                            {{ __('Aucun événement de ce type.') }}
                            <a href="{{ route('evenements.index') }}">{{ __('Voir tous les événements') }}</a>
                        @else
                            {{ __('Tous les pointages sont corrects.') }}
                        @endif
                    </p>
                </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL : Supprimer un pointage (doublon)                                --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteLabel">
                        <i class="bi bi-trash me-2 text-danger"></i>{{ __('Supprimer ce pointage') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ __('Voulez-vous supprimer le pointage de') }}
                        <strong id="deleteEmployeNom"></strong>
                        {{ __('à') }} <strong id="deleteHeure"></strong> ?
                    </p>
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ __('Cette action est irréversible. Le pointage sera définitivement supprimé.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Annuler') }}
                    </button>
                    <form id="formDelete" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>{{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL : Ajouter un pointage manquant                                   --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalAjoutPointage" tabindex="-1" aria-labelledby="modalAjoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjoutLabel">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>{{ __('Ajouter un pointage manquant') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('pointages.store') }}">
                    @csrf
                    <div class="modal-body">

                        <div class="alert alert-info d-flex align-items-center small mb-3">
                            <i class="bi bi-person-circle me-2 fs-5"></i>
                            <div>
                                {{ __('Correction manuelle pour') }} :
                                <strong id="ajoutEmployeNom"></strong>
                                — <span id="ajoutTypeLabel" class="fw-semibold"></span>
                            </div>
                        </div>

                        {{-- Champs cachés --}}
                        <input type="hidden" name="employee_id" id="ajoutEmployeeId">
                        <input type="hidden" name="type_"       id="ajoutType">
                        <input type="hidden" name="SiegeID"     value="{{ $siegeId }}">
                        <input type="hidden" name="latitude"    value="0">
                        <input type="hidden" name="longitude"   value="0">
                        <input type="hidden" name="synced"      value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('Date et heure') }} <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                    name="timestamp_"
                                    id="ajoutTimestamp"
                                    class="form-control"
                                    required>
                                <div class="form-text">{{ __('Entrez la date et l\'heure exactes du pointage.') }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('Méthode d\'authentification') }} <span class="text-danger">*</span>
                                </label>
                                <select name="auth_method" class="form-select" required>
                                    <option value="pin" selected>{{ __('PIN (correction manuelle)') }}</option>
                                    <option value="rfid">RFID / Badge</option>
                                    <option value="face">{{ __('Reconnaissance faciale') }}</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    {{ __('Site / Établissement') }} <span class="text-danger">*</span>
                                </label>
                                <select name="company_id" class="form-select" required>
                                    <option value="">{{ __('Sélectionner un site...') }}</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->ID }}">{{ $site->Nom }}</option>
                                    @endforeach
                                </select>
                                @if($sites->isEmpty())
                                    <div class="form-text text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ __('Aucun site actif trouvé pour ce siège.') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn btn-primary" {{ $sites->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i>{{ __('Enregistrer le pointage') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL : Acknowledger (jour férié / weekend)                             --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalAcknowledge" tabindex="-1" aria-labelledby="modalAckLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAckLabel">
                        <i class="bi bi-check-circle me-2 text-success"></i>{{ __('Marquer comme intentionnel') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('evenements.acknowledge') }}">
                    @csrf
                    <div class="modal-body">

                        <p class="mb-3">
                            {{ __('Le pointage de') }} <strong id="ackEmployeNom"></strong>
                            {{ __('le') }} <strong id="ackDateDisplay"></strong>
                            {{ __('sera considéré comme intentionnel (ex: astreinte, heures supplémentaires).') }}
                        </p>

                        <input type="hidden" name="employee_id" id="ackEmployeeId">
                        <input type="hidden" name="date"        id="ackDateInput">
                        <input type="hidden" name="error_type"  id="ackErrorType">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('Note explicative') }}
                                <span class="text-muted fw-normal">({{ __('optionnel') }})</span>
                            </label>
                            <textarea name="note"
                                class="form-control"
                                rows="3"
                                placeholder="{{ __('Ex : Astreinte weekend validée par le responsable, heures supplémentaires autorisées...') }}"></textarea>
                        </div>

                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            {{ __('Cet événement ne sera plus signalé. Vous pouvez annuler cette décision à tout moment depuis la liste.') }}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>{{ __('Confirmer — intentionnel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // ── Modal Supprimer doublon ──────────────────────────────────────────────
    const modalDelete = document.getElementById('modalDelete');
    if (modalDelete) {
        modalDelete.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;
            document.getElementById('deleteEmployeNom').textContent = btn.dataset.employe;
            document.getElementById('deleteHeure').textContent      = btn.dataset.heure;
            document.getElementById('formDelete').action            = '/pointages/' + btn.dataset.id;
        });
    }

    // ── Modal Ajouter pointage manquant ─────────────────────────────────────
    const modalAjout = document.getElementById('modalAjoutPointage');
    if (modalAjout) {
        modalAjout.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;

            document.getElementById('ajoutEmployeNom').textContent = btn.dataset.employeeNom;
            document.getElementById('ajoutEmployeeId').value       = btn.dataset.employeeId;
            document.getElementById('ajoutType').value             = btn.dataset.type;
            document.getElementById('ajoutTypeLabel').textContent  = btn.dataset.label;

            // Pré-remplir la date de l'erreur à 08:00 par défaut
            const date = btn.dataset.date;
            document.getElementById('ajoutTimestamp').value = date + 'T08:00';
        });
    }

    // ── Modal Acknowledger ───────────────────────────────────────────────────
    const modalAck = document.getElementById('modalAcknowledge');
    if (modalAck) {
        modalAck.addEventListener('show.bs.modal', function (e) {
            const btn = e.relatedTarget;

            document.getElementById('ackEmployeNom').textContent   = btn.dataset.employeeNom;
            document.getElementById('ackDateDisplay').textContent  = btn.dataset.dateDisplay;
            document.getElementById('ackEmployeeId').value         = btn.dataset.employeeId;
            document.getElementById('ackDateInput').value          = btn.dataset.date;
            document.getElementById('ackErrorType').value          = btn.dataset.errorType;
        });
    }
    </script>
    @endpush

</x-app-layout>
