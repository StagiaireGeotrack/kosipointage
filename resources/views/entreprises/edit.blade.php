<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modifier l'entreprise
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
                        <form action="{{ route('entreprises.update', $entreprise->ID) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="Nom">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('Nom') is-invalid @enderror" id="Nom" name="Nom" value="{{ old('Nom', $entreprise->Nom) }}" required>
                                        @error('Nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                                        <select class="form-control @error('SiegeID') is-invalid @enderror" id="SiegeID" name="SiegeID" required>
                                            <option value="">Sélectionner un siège</option>
                                            @foreach($sieges as $siege)
                                                <option value="{{ $siege->ID }}" {{ old('SiegeID', $entreprise->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                                    {{ $siege->Nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('SiegeID')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="Email">Email</label>
                                        <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email', $entreprise->Email) }}">
                                        @error('Email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="Telephone">Téléphone</label>
                                        <input type="text" class="form-control @error('Telephone') is-invalid @enderror" id="Telephone" name="Telephone" value="{{ old('Telephone', $entreprise->Telephone) }}">
                                        @error('Telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="text-center mb-3">
                                        @if($entreprise->Logo)
                                            <img src="{{ route('entreprises.logo', $entreprise->ID) }}" alt="{{ $entreprise->Nom }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                                            <p class="text-muted">Logo actuel</p>
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 150px; width: 100%;">
                                                <i class="bi bi-building" style="font-size: 3rem; color: #ccc;"></i>
                                            </div>
                                            <p class="text-muted">Pas de logo</p>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Logo">Changer le logo</label>
                                        <input type="file" class="form-control @error('Logo') is-invalid @enderror" id="Logo" name="Logo">
                                        <small class="form-text text-muted">Formats acceptés : JPG, PNG. Max : 2Mo</small>
                                        @error('Logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="Adresse">Adresse</label>
                                <textarea class="form-control @error('Adresse') is-invalid @enderror" id="Adresse" name="Adresse" rows="3">{{ old('Adresse', $entreprise->Adresse) }}</textarea>
                                @error('Adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('Actived') is-invalid @enderror" type="checkbox" value="1" id="Actived" name="Actived" {{ old('Actived', $entreprise->Actived) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="Actived">Actif</label>
                                    @error('Actived')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>