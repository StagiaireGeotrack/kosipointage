<x-employe-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Mes Demandes de Congés') }}
            </h2>
            <a href="{{ route('employe.conges.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ __('Nouvelle demande') }}
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employe.conges.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="date_debut" class="form-label text-muted small fw-bold">Date demande (Début)</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label text-muted small fw-bold">Date demande (Fin)</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label text-muted small fw-bold">Statut du congé</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="validated" {{ request('status') == 'validated' ? 'selected' : '' }}>Validée</option>
                        <option value="not_validated" {{ request('status') == 'not_validated' ? 'selected' : '' }}>Refusée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-dark">{{ __('Date de demande') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Période') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Motif') }}</th>
                            <th class="px-4 py-3 text-center text-dark">{{ __('Statut') }}</th>
                            <th class="px-4 py-3 text-end text-dark">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($conges as $conge)
                            <tr>
                                <td class="px-4 py-3 fw-medium text-muted">
                                    {{ \Carbon\Carbon::parse($conge->date_creation)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="small">
                                        <span class="fw-semibold text-dark">Du :</span> {{ \Carbon\Carbon::parse($conge->date_heure_debut)->format('d/m/Y H:i') }}<br>
                                        <span class="fw-semibold text-dark">Au :</span> {{ \Carbon\Carbon::parse($conge->date_heure_fin)->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $conge->raison }}">
                                        {{ Str::limit($conge->raison, 40) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-{{ $conge->status_color }} bg-opacity-10 text-{{ $conge->status_color }} border border-{{ $conge->status_color }} border-opacity-25 px-2 py-1">
                                        @if($conge->status == 'validated')
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                        @elseif($conge->status == 'not_validated')
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                        @else
                                            <i class="bi bi-hourglass-split me-1"></i>
                                        @endif
                                        {{ $conge->status_label }}
                                    </span>
                                    @if($conge->status == 'not_validated' && !empty($conge->raison_rejection))
                                        <div class="small text-danger mt-1" style="font-size: 0.75rem;" title="{{ $conge->raison_rejection }}">
                                            <i class="bi bi-info-circle"></i> Motif refus : {{ Str::limit($conge->raison_rejection, 20) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-end">
                                    @if($conge->status === 'en_cours')
                                        <form action="{{ route('employe.conges.destroy', $conge->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande de congé ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Annuler la demande">
                                                <i class="bi bi-trash"></i> Annuler
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small"><i class="bi bi-lock"></i> Traité</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    {{ __('Vous n\'avez fait aucune demande de congé.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($conges->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $conges->links('pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</x-employe-layout>
