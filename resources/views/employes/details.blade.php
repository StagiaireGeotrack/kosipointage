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

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <!-- Badge ID -->
                            <label class="fw-bold text-muted">Badge ID</label>
                            <p class="fs-5">{{ $employe->BadgeID }}</p>
                            <small class="text-muted">Identifiant unique de l'employé</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <!-- Pin -->
                            <label class="fw-bold text-muted">Code PIN</label>
                            <p class="fs-5">{{ $employe->Pin ?? 'Non défini' }}</p>
                            <small class="text-muted">Code PIN à 6 chiffres</small>
                        </div>
                    </div>

                    <!-- Photo de visage -->
                    @if($employe->FaceEncodingPath)
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold text-muted d-block">Photo de visage</label>
                            <img src="{{ route('employes.face.thumbnail', $employe->ID) }}" 
                                    alt="Photo {{ $employe->Nom }}" 
                                    style="max-width: 200px; max-height: 200px;" 
                                    class="img-thumbnail mt-2"
                                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EErreur%3C/text%3E%3C/svg%3E';">
                        </div>
                    @else
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold text-muted d-block">Photo de visage</label>
                            <div class="alert alert-warning mt-2">
                                <i class="bi bi-exclamation-triangle"></i> Aucune photo de visage configurée
                            </div>
                        </div>
                    @endif

                    <!-- Empreinte digitale -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted d-block">Empreinte digitale</label>
                        @if($employe->HasBiometricSetup)
                            <div class="alert alert-success mt-2">
                                <i class="bi bi-fingerprint"></i> Empreinte digitale configurée
                            </div>
                        @else
                            <div class="alert alert-warning mt-2">
                                <i class="bi bi-exclamation-triangle"></i> Empreinte digitale non configurée
                            </div>
                        @endif
                    </div>

                    <!-- Reconnaissance faciale -->
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold text-muted d-block">Reconnaissance faciale</label>
                        @if($employe->HasFaceSetup)
                            <div class="alert alert-success mt-2">
                                <i class="bi bi-check-circle"></i> Reconnaissance faciale configurée
                            </div>
                        @else
                            <div class="alert alert-warning mt-2">
                                <i class="bi bi-exclamation-triangle"></i> Reconnaissance faciale non configurée
                            </div>
                        @endif
                    </div>

                    <!-- Statut -->
                    <div class="col-md-12 mb-3">
                        <hr>
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
                    @if($employe->created_at || $employe->updated_at)
                        <div class="col-md-12 mb-3">
                            <hr>
                            <div class="row">
                                @if($employe->created_at)
                                    <div class="col-md-6">
                                        <label class="fw-bold text-muted d-block">Date de création</label>
                                        <p class="text-muted"><x-local-date-time :datetime="$employe->created_at"/></p>
                                    </div>
                                @endif
                                @if($employe->updated_at)
                                    <div class="col-md-6">
                                        <label class="fw-bold text-muted d-block">Dernière modification</label>
                                        <p class="text-muted"><x-local-date-time :datetime="$employe->updated_at"/></p>
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