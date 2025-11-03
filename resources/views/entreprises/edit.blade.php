<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modification site ou établissement
            </h2>
            <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('entreprises.update', $entreprise->ID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                        <label for="Nom_Lieu_Ville">Nom du lieu / Ville</label>
                        <input type="text" class="form-control @error('Nom_Lieu_Ville') is-invalid @enderror" id="Nom_Lieu_Ville" name="Nom_Lieu_Ville" value="{{ old('Nom_Lieu_Ville', $entreprise->Nom_Lieu_Ville) }}">
                        @error('Nom_Lieu_Ville')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Latitude">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.00000001" class="form-control @error('Latitude') is-invalid @enderror" id="Latitude" name="Latitude" value="{{ old('Latitude', $entreprise->Latitude) }}" required>
                                <small class="form-text text-muted">Valeur entre -90 et 90</small>
                                @error('Latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Longitude">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.00000001" class="form-control @error('Longitude') is-invalid @enderror" id="Longitude" name="Longitude" value="{{ old('Longitude', $entreprise->Longitude) }}" required>
                                <small class="form-text text-muted">Valeur entre -180 et 180</small>
                                @error('Longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="RadiusInMeters">Rayon (en mètres)</label>
                        <input type="number" step="0.01" class="form-control @error('RadiusInMeters') is-invalid @enderror" id="RadiusInMeters" name="RadiusInMeters" value="{{ old('RadiusInMeters', $entreprise->RadiusInMeters) }}">
                        <small class="form-text text-muted">Rayon de géolocalisation pour l'entreprise</small>
                        @error('RadiusInMeters')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="Logo">Logo</label>
                        
                        @if($entreprise->Logo)
                            <div class="mb-2">
                                <label class="d-block text-muted">Logo actuel :</label>
                                <img src="{{ route('entreprises.logo.thumbnail', $entreprise->ID) }}" alt="Logo {{ $entreprise->Nom }}" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('Logo') is-invalid @enderror" id="Logo" name="Logo" accept="image/*">
                        <small class="form-text text-muted">Formats acceptés : JPG, PNG, GIF. Max : 2Mo. Laisser vide pour conserver le logo actuel.</small>
                        @error('Logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="logoPreview" class="mt-2"></div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                type="checkbox" 
                                value="1" 
                                id="Actived" 
                                name="Actived" {{ old('Actived', $entreprise->Actived) ? 'checked' : '' }}
                                {{ !Auth::user()->IsSuperAdmin ? 'disabled' : '' }}>
                            <label class="form-check-label" for="Actived">Activer</label>
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

    @push('scripts')
    <script>
        document.getElementById('Logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <label class="d-block text-muted">Nouveau logo :</label>
                        <img src="${e.target.result}" alt="Aperçu du nouveau logo" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                    `;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
    @endpush
</x-app-layout>