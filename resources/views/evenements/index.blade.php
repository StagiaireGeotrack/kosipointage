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

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Filtres --}}
                <form method="GET" action="{{ route('evenements.index') }}" class="mb-4">
                    <div class="row g-3">

                        {{-- Filtre siège (Super Admin uniquement) --}}
                        @if($readOnly && $sieges->count() > 0)
                        <div class="col-12 col-md-3">
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

                        {{-- Date de début --}}
                        <div class="col-12 col-sm-6 col-md-{{ $readOnly ? '2' : '3' }}">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>{{ __('Date début') }}
                            </label>
                            <input type="date" name="date_from" class="form-control"
                                   value="{{ $dateFrom ?? '' }}">
                        </div>

                        {{-- Date de fin --}}
                        <div class="col-12 col-sm-6 col-md-{{ $readOnly ? '2' : '3' }}">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-calendar-check me-1"></i>{{ __('Date fin') }}
                            </label>
                            <input type="date" name="date_to" class="form-control"
                                   value="{{ $dateTo ?? '' }}">
                        </div>

                        {{-- Filtre par type d'erreur --}}
                        <div class="col-12 col-md-{{ $readOnly ? '3' : '4' }}">
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

                        <div class="col-12 col-md-{{ $readOnly ? '2' : '2' }} d-flex align-items-end gap-2">
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

                        {{-- Légende séquence (doublons seulement) --}}
                        @if(in_array($erreur->type, ['doublon_entree', 'doublon_sortie']))
                        <div class="d-flex align-items-center gap-2 mb-2 small text-muted">
                            <i class="bi bi-info-circle"></i>
                            {{ __('Séquence complète du jour — les lignes en rouge sont des pointages consécutifs identiques (doublons à supprimer).') }}
                        </div>
                        @endif

                        {{-- Tableau des pointages du jour (séquence complète) --}}
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
                                        @if(!$readOnly)
                                            <th class="small text-secondary text-uppercase">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($erreur->pointages as $i => $p)
                                    {{-- Surligner en rouge uniquement les doublons consécutifs --}}
                                    <tr class="{{ $p->is_duplicate ? 'table-danger' : '' }}">
                                        <td class="text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}</strong>
                                            @if($p->is_duplicate)
                                                <span class="badge bg-danger ms-1" style="font-size:0.65rem;">
                                                    {{ __('doublon') }}
                                                </span>
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
                                        {{-- Bouton supprimer : visible sur tous les pointages du jour --}}
                                        @if(!$readOnly)
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm {{ $p->is_duplicate ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete"
                                                data-id="{{ $p->ID }}"
                                                data-heure="{{ \Carbon\Carbon::parse($p->timestamp_)->format('H:i:s') }}"
                                                data-type="{{ $p->type_ === 'entry' ? __('Entrée') : __('Sortie') }}"
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
                            <em>{{ __('Aucun pointage trouvé pour ce jour.') }}</em>
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

                            {{-- Acknowledger (jour férié / weekend uniquement) --}}
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
                            {{ __('L\'événement de') }} <strong id="ackEmployeNom"></strong>
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

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- BOUTON AIDE FLOTTANT CLIGNOTANT                                         --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <button type="button"
        id="btnAideEvenements"
        data-bs-toggle="modal"
        data-bs-target="#modalAideEvenements"
        title="{{ __('Aide — fonctionnalités des événements') }}"
        style="
            position: fixed;
            top: 6rem;
            right: 1.25rem;
            z-index: 1045;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            border: none;
            background: #0dcaf0;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0,0,0,.25);
            padding: 0;
        ">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
        </svg>
    </button>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- MODAL AIDE FONCTIONNALITÉS ÉVÉNEMENTS                                   --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="modalAideEvenements" tabindex="-1" aria-labelledby="modalAideLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-info bg-opacity-10 border-bottom">
                    <h5 class="modal-title fw-semibold" id="modalAideLabel">
                        <i class="bi bi-question-circle-fill text-info me-2"></i>
                        {{ __('Guide — Événements de pointage') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pb-2">

                    <p class="text-muted small mb-4">
                        {{ __('Cette page détecte automatiquement les anomalies sur les pointages. Chaque événement correspond à une situation à corriger ou à valider avant de pouvoir exporter vos rapports.') }}
                    </p>

                    {{-- Types d'événements --}}
                    <h6 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                        {{ __('Types d\'événements détectés') }}
                    </h6>

                    <div class="row g-2 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-danger bg-opacity-5 h-100">
                                <i class="bi bi-arrow-down-circle-fill text-danger fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-danger small">{{ __('Doublon d\'entrée') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Deux entrées consécutives enregistrées à moins de 30 minutes d\'intervalle. La seconde est probablement une erreur.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-warning bg-opacity-5 h-100">
                                <i class="bi bi-arrow-up-circle-fill text-warning fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-warning small">{{ __('Doublon de sortie') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Deux sorties consécutives enregistrées à moins de 30 minutes d\'intervalle. La seconde est probablement une erreur.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-warning bg-opacity-5 h-100">
                                <i class="bi bi-box-arrow-right text-warning fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-warning small">{{ __('Manque de sortie') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Plus d\'entrées que de sorties sur la journée — au moins une sortie n\'a pas été enregistrée.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-info bg-opacity-5 h-100">
                                <i class="bi bi-box-arrow-in-right text-info fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-info small">{{ __('Manque d\'entrée') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Plus de sorties que d\'entrées sur la journée — au moins une entrée n\'a pas été enregistrée.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-secondary bg-opacity-5 h-100">
                                <i class="bi bi-calendar-x-fill text-secondary fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-secondary small">{{ __('Pointage jour férié') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Un pointage a été enregistré un jour défini comme non travaillé dans le calendrier du siège.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-start gap-2 p-2 rounded border bg-secondary bg-opacity-5 h-100">
                                <i class="bi bi-calendar2-week-fill text-secondary fs-5 flex-shrink-0 mt-1"></i>
                                <div>
                                    <div class="fw-semibold text-secondary small">{{ __('Pointage weekend') }}</div>
                                    <div class="text-muted" style="font-size:.8rem;">
                                        {{ __('Un pointage a été enregistré un samedi ou un dimanche.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions disponibles --}}
                    @if(!$readOnly)
                    <h6 class="fw-bold mb-3 border-bottom pb-2">
                        <i class="bi bi-tools text-primary me-1"></i>
                        {{ __('Actions de correction') }}
                    </h6>
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <span class="badge bg-danger mt-1 flex-shrink-0"><i class="bi bi-trash"></i></span>
                            <div>
                                <div class="fw-semibold small">{{ __('Supprimer un doublon') }}</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    {{ __('Les lignes surlignées en rouge sont les pointages en doublon. Cliquez sur « Supprimer » pour retirer le pointage erroné. Action irréversible.') }}
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <span class="badge bg-primary mt-1 flex-shrink-0"><i class="bi bi-plus-circle"></i></span>
                            <div>
                                <div class="fw-semibold small">{{ __('Ajouter un pointage manquant') }}</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    {{ __('Pour les manques d\'entrée ou de sortie, cliquez sur « Ajouter une entrée / sortie » pour saisir manuellement le pointage oublié.') }}
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <span class="badge bg-success mt-1 flex-shrink-0"><i class="bi bi-check-circle"></i></span>
                            <div>
                                <div class="fw-semibold small">{{ __('Marquer comme intentionnel') }}</div>
                                <div class="text-muted" style="font-size:.8rem;">
                                    {{ __('Pour les pointages en jour férié ou weekend (ex: astreinte, heures sup), marquez-les « Intentionnel » avec une note. Ils disparaîtront de la liste.') }}
                                </div>
                            </div>
                        </li>
                    </ul>
                    @else
                    <div class="alert alert-secondary small py-2 mb-4">
                        <i class="bi bi-eye me-1"></i>
                        {{ __('En lecture seule (Super Admin) : vous consultez les événements de tous les sièges. Seuls les Admins de siège peuvent les corriger.') }}
                    </div>
                    @endif

                    <div class="alert alert-warning small py-2 mb-0">
                        <i class="bi bi-lock-fill me-1"></i>
                        <strong>{{ __('Exports bloqués') }}</strong> —
                        {{ __('Tant qu\'il reste des événements non résolus, les exports de rapports sont désactivés. Résolvez-les tous pour débloquer.') }}
                    </div>

                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        {{ __('Fermer') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    // ── Bouton aide clignotant (permanent) ──────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('btnAideEvenements');
        if (!btn) return;

        var on = true;
        setInterval(function () {
            on = !on;
            btn.style.backgroundColor = on ? '#0dcaf0' : '#ffffff';
            btn.style.color           = on ? '#ffffff' : '#0dcaf0';
        }, 400);
    });

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
