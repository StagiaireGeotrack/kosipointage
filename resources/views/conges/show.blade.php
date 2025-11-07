{{-- resources/views/conges/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du Congé') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('conges.edit', $conge->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('conges.index') }}" class="btn btn-secondary">
                    {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Employé') }}</label>
                            <p class="fs-5">{{ $conge->employe->Nom }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Type de congé') }}</label>
                            <p>
                                @php
                                    $badgeClass = match($conge->type_conge) {
                                        'CP' => 'bg-primary',
                                        'RTT' => 'bg-info',
                                        'Maladie' => 'bg-danger',
                                        'Autres' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">
                                    {{ $conge->type_conge }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Date et heure de début') }}</label>
                            <p class="fs-5">{{ ucfirst($conge->date_debut->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Date et heure de fin') }}</label>
                            <p class="fs-5">{{ ucfirst($conge->date_fin->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Durée') }}</label>
                            <p class="fs-5">
                                @php
                                    $jourOuvrableService = app(\App\Services\JourOuvrableService::class);
                                    $resultat = $jourOuvrableService->calculerJoursOuvrables(
                                        $conge->date_debut, 
                                        $conge->date_fin,
                                        $conge->employe->SiegeID ?? null
                                    );
                                @endphp
                                
                                @if($resultat['jours'] > 0)
                                    <span class="text-muted">{{ round( $resultat['jours'] ) }} jour(s) ouvrable(s)</span>
                                @endif
                                
                                @if($resultat['heures'] > 0)
                                    @if($resultat['jours'] > 0) et @endif
                                    <span class="text-muted">{{ round( $resultat['heures'] ) }} h</span>
                                @endif
                                
                                @if($resultat['jours'] == 0 && $resultat['heures'] == 0)
                                    <span class="badge bg-warning text-dark">{{ __('Aucun jour ouvrable') }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">{{ __('Date de création') }}</label>
                            <p class="fs-5">{{ ucfirst($conge->created_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}</p>
                        </div>
                    </div>
                </div>

                @if($conge->commentaire)
                    <div class="mt-4">
                        <label class="form-label fw-semibold text-secondary">{{ __('Commentaire') }}</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $conge->commentaire }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-4 pt-3 border-top">
                    <form action="{{ route('conges.destroy', $conge->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce congé ?') }}')">
                            {{ __('Supprimer ce congé') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>