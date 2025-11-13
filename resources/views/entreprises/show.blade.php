<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Détails du site ou établissement
            </h2>
            <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="p-2">
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
                        
                        @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
                        <div class="d-grid gap-2">
                            <a href="{{ route('entreprises.edit', $entreprise->ID) }}" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-md-8">
                        <h3>{{ $entreprise->Nom }}</h3>
                        
                        <div class="mb-2">
                            <span class="badge bg-{{ $entreprise->Actived ? 'success' : 'danger' }}">
                                {{ $entreprise->Actived ? 'Activée' : 'Désactivée' }}
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
                                        <dt class="col-sm-3">Créée le</dt>
                                        <dd class="col-sm-9">{{ ucfirst($entreprise->CreatedAt->isoFormat('dddd D MMMM YYYY')) }}</dd>
                                    @endif                                           
                                    
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>