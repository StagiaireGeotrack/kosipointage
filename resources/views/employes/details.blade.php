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
                        <p class="fs-5">{{ $employe->num_mat ?? 'Non défini' }}</p>
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

                    <!-- Service -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Service</label>
                        <p class="fs-5">{{ $employe->department->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Poste -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Poste</label>
                        <p class="fs-5">{{ $employe->jobTitle->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Manager Direct -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Manager Direct</label>
                        <p class="fs-5">{{ $employe->manager->Nom ?? 'Aucun manager' }}</p>
                    </div>

                    <!-- Niveau Hiérarchique -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Niveau Hiérarchique</label>
                        <p class="fs-5">{{ $employe->hierarchyLevel->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Date d'embauche -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Date d'embauche</label>
                        <p class="fs-5">{{ $employe->hire_date ? \Carbon\Carbon::parse($employe->hire_date)->format('d/m/Y') : 'Non définie' }}</p>
                    </div>

                    <!-- Statut employé -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted">Statut</label>
                        <p class="fs-5">
                            @php
                                $statusLabels = [
                                    'actif' => 'Actif',
                                    'suspendu' => 'Suspendu',
                                    'sorti' => 'Sorti',
                                ];
                            @endphp
                            {{ $statusLabels[$employe->employment_status] ?? $employe->employment_status ?? 'Actif' }}
                        </p>
                    </div>
                    
                    <!-- Méthode -->
                    <label class="fw-bold text-muted d-block">Méthode</label>
                    <div class="col-md-12 mb-2">
                        @if ($employe->BadgeID)
                            <span class="badge bg-info me-1">
                                {{ __('Badge') }}
                            </span>
                        @endif
                        @if ($employe->HasFaceSetup)
                            <span class="badge bg-primary me-1">
                                {{ __('Face image') }}
                            </span>
                        @endif
                        @if ($employe->Pin)
                            <span class="badge bg-secondary me-1">
                                {{ __('Code Pin') }}
                            </span>
                        @endif
                        @if ($employe->email)
                            <span class="badge bg-dark me-1">
                                {{ __('Accès Web') }}
                            </span>
                        @endif
                        @if (!$employe->BadgeID && !$employe->HasFaceSetup && !$employe->Pin && !$employe->email)
                            <span class="text-muted">Aucune méthode configurée</span>
                        @endif
                    </div>

                    <!-- Statut Actived -->
                    <div class="col-md-12 my-3">
                        <label class="fw-bold text-muted d-block">Statut de l'employé</label>
                        @if($employe->Actived)
                            <span class="badge bg-success fs-6 mt-2">
                                <i class="bi bi-check-circle"></i> Actif
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 mt-2">
                                <i class="bi bi-x-circle"></i> Inactif
                            </span>
                        @endif
                        @if($employe->deleted)
                            <span class="badge bg-danger fs-6 mt-2 ms-2">
                                <i class="bi bi-trash"></i> Supprimé
                            </span>
                        @endif
                    </div>

                    <!-- Dates de création -->
                    @if($employe->CreatedAt)
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="fw-bold text-muted d-block">Date de création</label>
                                    <p class="text-muted"><x-local-date-time :datetime="$employe->CreatedAt"/></p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>