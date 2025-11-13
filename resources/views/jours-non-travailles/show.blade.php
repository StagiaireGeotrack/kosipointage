{{-- resources/views/jours-non-travailles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du jour non travaillé') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('jours-non-travailles.edit', $jourNonTravaille->ID) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>{{ __('Modifier') }}
                </a>
                <a href="{{ route('jours-non-travailles.index') }}" class="btn btn-secondary">
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Date') }}</h6>
                        <p class="fs-5 fw-bold mb-0">{{ ucfirst($jourNonTravaille->Date->isoFormat('dddd D MMMM YYYY')) }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Nom') }}</h6>
                        <p class="mb-0">{{ $jourNonTravaille->Nom }}</p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Type') }}</h6>
                        @php
                            $badgeClass = match($jourNonTravaille->Type) {
                                'ferie' => 'bg-primary',
                                'fermeture' => 'bg-warning text-dark',
                                'autre' => 'bg-secondary',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($jourNonTravaille->Type) }}</span>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Siège') }}</h6>
                        @if($jourNonTravaille->SiegeID)
                            <p class="mb-0">{{ $jourNonTravaille->siege->Nom }}</p>
                        @else
                            <span class="badge bg-info">{{ __('National (tous les sièges)') }}</span>
                        @endif
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Récurrent') }}</h6>
                        @if($jourNonTravaille->Recurrent)
                            <span class="badge bg-success">
                                <i class="bi bi-arrow-repeat me-1"></i>{{ __('Oui, chaque année') }}
                            </span>
                        @else
                            <span class="badge bg-secondary">{{ __('Non') }}</span>
                        @endif
                    </div>
                </div>
                
                @if($jourNonTravaille->Description)
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Description') }}</h6>
                        <p class="mb-0">{{ $jourNonTravaille->Description }}</p>
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Statut') }}</h6>
                        @if($jourNonTravaille->Actived)
                            <span class="badge bg-success">{{ __('Actif') }}</span>
                        @else
                            <span class="badge bg-danger">{{ __('Inactif') }}</span>
                        @endif
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Créé le') }}</h6>
                        <p class="mb-0">{{ ucfirst($jourNonTravaille->created_at->isoFormat('dddd D MMMM YYYY')) }}</p>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <h6 class="text-uppercase text-secondary small fw-semibold">{{ __('Modifié le') }}</h6>
                        <p class="mb-0">{{ ucfirst($jourNonTravaille->updated_at->isoFormat('dddd D MMMM YYYY')) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-3 d-flex justify-content-end">
            <form action="{{ route('jours-non-travailles.destroy', $jourNonTravaille->ID) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce jour ?') }}')">
                    <i class="bi bi-trash me-1"></i>{{ __('Supprimer') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>