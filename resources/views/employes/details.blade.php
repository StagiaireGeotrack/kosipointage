{{-- resources/views/employes/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Détails de l'employé : {{ $employe->Nom }}
            </h2>
            <div>
                <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    
                    <!-- N° Mat -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">N° Matricule</label>
                        <p class="fs-5">{{ $employe->num_mat }}</p>
                    </div>

                    <!-- Nom -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Nom</label>
                        <p class="fs-5">{{ $employe->Nom }}</p>
                    </div>

                    <!-- Siège -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Siège</label>
                        <p class="fs-5">{{ $employe->siege->Nom ?? 'Non défini' }}</p>
                    </div>

                    
                    <!-- Méthode -->
                    <label class="fw-bold text-muted d-block">Méthode</label>
                    @if ($employe->BadgeID )
                    <div class="col-md-12 mb-2">
                        <span class="badge bg-info">
                            {{ __('Badge') }}
                        </span>
                    </div>
                    @endif
                    @if ($employe->HasFaceSetup)
                    <div class="col-md-12 mb-2">
                        <span class="badge bg-primary">
                            {{ __('Face image') }}
                        </span>
                    </div>
                    @endif
                    @if ($employe->Pin)
                    <div class="col-md-12 mb-2">
                        <span class="badge bg-secondary">
                            {{ __('Code Pin') }}
                        </span>
                    </div>
                    @endif

                    <!-- Statut -->
                    <div class="col-md-12 my-3">
                        <label class="fw-bold text-muted d-block">Statut</label>
                        @if($employe->Actived)
                            <span class="badge bg-success fs-6 mt-2">
                                <i class="bi bi-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 mt-2">
                                <i class="bi bi-x-circle"></i> Inactif
                            </span>
                        @endif
                    </div>

                    <!-- Dates de création et modification -->
                    @if($employe->CreatedAt)
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                @if($employe->CreatedAt)
                                    <div class="col-md-6">
                                        <label class="fw-bold text-muted d-block">Date de création</label>
                                        <p class="text-muted"><x-local-date-time :datetime="$employe->CreatedAt"/></p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>