<x-app-layout>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1 text-dark">
                <i class="bi bi-calendar-range me-2 text-primary"></i>Périodes de référence
            </h2>
            <p class="text-muted mb-0">
                @if($isSuperAdmin)
                    <span class="badge bg-dark"><i class="bi bi-eye me-1"></i>Vue Super Admin — Tous les sièges</span>
                @else
                    <span class="badge bg-primary"><i class="bi bi-building me-1"></i>Siège : {{ Auth::user()->employe?->siege?->nom ?? 'Votre siège' }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.leave-periods.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nouvelle période
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-light border-bottom py-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Portée</strong> = Global (tous sièges) ou Siège spécifique |
                <strong>Dates</strong> = début → fin du solde |
                <strong>Par défaut</strong> = période proposée automatiquement aux employés
                @if(!$isSuperAdmin)
                    | <span class="text-primary">Les valeurs affichées tiennent compte de vos personnalisations.</span>
                @endif
            </small>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #0d6efd; color: white;">
                        <tr>
                            <th class="py-3 ps-4">Portée</th>
                            <th class="py-3">Type de congé</th>
                            <th class="py-3">Nom de la période</th>
                            <th class="py-3">Dates de validité</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3 text-center">Par défaut</th>
                            @if($isSuperAdmin)
                                <th class="py-3">Personnalisations</th>
                            @else
                                <th class="py-3">État</th>
                            @endif
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse($periods as $period)
                            {{-- Pour Admin Siège : on utilise les valeurs résolues --}}
                            @php
                                $isGlobal = is_null($period->site_id);
                                $displayName = $isSuperAdmin ? $period->name : ($period->resolved_name ?? $period->name);
                                $displayStart = $isSuperAdmin ? $period->start_date : ($period->resolved_start_date ?? $period->start_date);
                                $displayEnd = $isSuperAdmin ? $period->end_date : ($period->resolved_end_date ?? $period->end_date);
                                $displayDeadline = $isSuperAdmin ? $period->submission_deadline : ($period->resolved_submission_deadline ?? $period->submission_deadline);
                                $displayStatus = $isSuperAdmin ? $period->status : ($period->resolved_status ?? $period->status);
                                $displayDefault = $isSuperAdmin ? $period->is_default : ($period->resolved_is_default ?? $period->is_default);
                                $hasOverride = !$isSuperAdmin && ($period->has_override ?? false);
                            @endphp

                        <tr>
                            {{-- PORTÉE --}}
                            <td class="ps-4">
                                @if($isGlobal)
                                    <span class="badge bg-dark"><i class="bi bi-globe me-1"></i>Global</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-building me-1"></i>{{ $period->site?->nom ?? 'Siège #'.$period->site_id }}</span>
                                @endif
                            </td>

                            {{-- TYPE --}}
                            <td>
                                <span class="badge" style="background-color: {{ $period->leaveType->color ?? '#666' }}">
                                    {{ $period->leaveType->name ?? 'N/A' }}
                                </span>
                                <small class="text-muted d-block">{{ $period->leaveType->code ?? '' }}</small>
                            </td>

                            {{-- NOM --}}
                            <td class="fw-semibold text-dark">
                                {{ $displayName }}
                                @if($hasOverride)
                                    <span class="badge bg-warning text-dark ms-1"><i class="bi bi-pencil-square me-1"></i>Modifié</span>
                                @endif
                            </td>

                            {{-- DATES --}}
                            <td>
                                <div class="small">
                                    <div class="mb-1"><i class="bi bi-calendar-check text-success me-1"></i>{{ $displayStart->format('d/m/Y') }}</div>
                                    <div class="mb-1"><i class="bi bi-calendar-x text-danger me-1"></i>{{ $displayEnd->format('d/m/Y') }}</div>
                                    @if($displayDeadline)
                                        <div class="text-warning"><i class="bi bi-alarm me-1"></i>Limite : {{ $displayDeadline->format('d/m/Y') }}</div>
                                    @endif
                                </div>
                            </td>

                            {{-- STATUT --}}
                            <td>
                                @if($displayStatus == 'open')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Ouverte</span>
                                @elseif($displayStatus == 'closed')
                                    <span class="badge bg-secondary"><i class="bi bi-lock me-1"></i>Clôturée</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-pencil me-1"></i>Préparation</span>
                                @endif
                            </td>

                            {{-- PAR DÉFAUT --}}
                            <td class="text-center">
                                @if($displayDefault)
                                    <span class="badge bg-info"><i class="bi bi-star-fill me-1"></i>Oui</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            {{-- PERSONNALISATIONS / ÉTAT --}}
                            @if($isSuperAdmin)
                                <td>
                                    @if($isGlobal)
                                        @if($period->site_settings_count > 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-sliders me-1"></i>{{ $period->site_settings_count }} siège(s)
                                            </span>
                                        @else
                                            <span class="text-muted small">Aucune</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            @else
                                <td>
                                    @if($isGlobal)
                                        @if($hasOverride)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-sliders me-1"></i>Personnalisé</span>
                                        @else
                                            <span class="badge bg-light text-dark border"><i class="bi bi-globe me-1"></i>Global inchangé</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-building me-1"></i>Spécifique</span>
                                    @endif
                                </td>
                            @endif

                            {{-- ACTIONS --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.leave-periods.edit', $period) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil me-1"></i>Modifier
                                </a>

                                {{-- Admin siège ne peut pas supprimer un global --}}
                                @if($isSuperAdmin || !$isGlobal)
                                    <form action="{{ route('admin.leave-periods.destroy', $period) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette période ?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isSuperAdmin ? 8 : 8 }}" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                                <p class="mb-3">Aucune période définie.</p>
                                <a href="{{ route('admin.leave-periods.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Créer la première période
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($periods->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $periods->links() }}
            </div>
        @endif
    </div>
</div>

</x-app-layout>