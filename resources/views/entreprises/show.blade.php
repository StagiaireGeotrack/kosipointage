<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Détails du site ou établissement
            </h2>
            <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                @if($entreprise->Logo)
                                    <img src="{{ route('entreprises.logo', $entreprise->ID) }}" alt="{{ $entreprise->Nom }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px; width: 100%;">
                                        <i class="bi bi-building" style="font-size: 5rem; color: #ccc;"></i>
                                    </div>
                                @endif
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('entreprises.edit', $entreprise->ID) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </a>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <h3>{{ $entreprise->Nom }}</h3>
                                
                                <div class="mb-2">
                                    <span class="badge bg-{{ $entreprise->Actived ? 'success' : 'danger' }}">
                                        {{ $entreprise->Actived ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2 text-muted">Informations générales</h6>
                                        
                                        <dl class="row mb-0">

                                            <dt class="col-sm-3">Siège</dt>
                                            <dd class="col-sm-9">{{ $entreprise->siege->Nom }}</dd>

                                            @if ($entreprise->Nom_Lieu_Ville)
                                            <dt class="col-sm-3">Adresse ou ville</dt>
                                            <dd class="col-sm-9">{{ $entreprise->Nom_Lieu_Ville }}</dd>
                                            @endif

                                            <dt class="col-sm-3">Latitdude</dt>
                                            <dd class="col-sm-9">{{ $entreprise->Latitude }}</dd>
                                            
                                            <dt class="col-sm-3">Longitude</dt>
                                            <dd class="col-sm-9">{{ $entreprise->Longitude}}</dd>
                                            
                                            @if ($entreprise->CreatedAt)
                                                <dt class="col-sm-3">Créé le</dt>
                                                <dd class="col-sm-9">{{ $entreprise->CreatedAt->format('d/m/Y H:i') }}</dd>
                                            @endif                                           
                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <form action="{{ route('entreprises.destroy', $entreprise->ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>