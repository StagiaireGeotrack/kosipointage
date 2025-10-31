{{-- resources/views/employes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modifier l'employé : {{ $employe->Nom }}
            </h2>
            <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('employes.update', $employe->ID) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Nom -->
                        <div class="form-group mb-3">
                            <label for="Nom">Nom <span class="text-danger">*</span></label>
                            <input type="text" 
                                    class="form-control @error('Nom') is-invalid @enderror" 
                                    id="Nom" 
                                    name="Nom" 
                                    value="{{ old('Nom', $employe->Nom) }}" 
                                    required>
                            @error('Nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SiegeID -->
                        <div class="form-group mb-3">
                            <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                            <select class="form-control @error('SiegeID') is-invalid @enderror" 
                                    id="SiegeID" 
                                    name="SiegeID" 
                                    required>
                                <option value="">Sélectionnez un siège</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" 
                                            {{ old('SiegeID', $employe->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SiegeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- BadgeID -->
                                <div class="form-group mb-3">
                                    <label for="BadgeID">Badge ID <span class="text-danger">*</span></label>
                                    <input type="text" 
                                            class="form-control @error('BadgeID') is-invalid @enderror" 
                                            id="BadgeID" 
                                            name="BadgeID" 
                                            value="{{ old('BadgeID', $employe->BadgeID) }}" 
                                            maxlength="25"
                                            required>
                                    <small class="form-text text-muted">Identifiant unique de l'employé</small>
                                    @error('BadgeID')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <!-- Pin -->
                                <div class="form-group mb-3">
                                    <label for="Pin">Code PIN</label>
                                    <input type="text" 
                                            class="form-control @error('Pin') is-invalid @enderror" 
                                            id="Pin" 
                                            name="Pin" 
                                            value="{{ old('Pin', $employe->Pin) }}" 
                                            maxlength="6"
                                            pattern="[0-9]{6}"
                                            placeholder="000000">
                                    <small class="form-text text-muted">Code PIN à 6 chiffres</small>
                                    @error('Pin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo de visage actuelle -->
                        @if($employe->FaceEncodingPath)
                            <div class="form-group mb-3">
                                <label class="d-block text-muted">Photo de visage actuelle :</label>
                                {{-- ✅ Utiliser la ROUTE au lieu du Base64 direct --}}
                                <img src="{{ route('employes.face.thumbnail', $employe->ID) }}" 
                                        alt="Photo {{ $employe->Nom }}" 
                                        style="max-width: 200px; max-height: 200px;" 
                                        class="img-thumbnail"
                                        onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EErreur%3C/text%3E%3C/svg%3E';">
                            </div>
                        @endif
                        
                        <!-- HasBiometricSetup -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('HasBiometricSetup') is-invalid @enderror" 
                                        type="checkbox" 
                                        value="1" 
                                        id="HasBiometricSetup" 
                                        name="HasBiometricSetup" 
                                        {{ old('HasBiometricSetup', $employe->HasBiometricSetup) ? 'checked' : '' }}>
                                <label class="form-check-label" for="HasBiometricSetup">
                                    <i class="bi bi-fingerprint"></i> Empreinte digitale configurée
                                </label>
                                @error('HasBiometricSetup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- HasFaceSetup (lecture seule, automatique) -->
                        @if($employe->HasFaceSetup)
                            <div class="alert alert-info">
                                <i class="bi bi-check-circle"></i> Reconnaissance faciale configurée
                            </div>
                        @endif

                        <!-- Actived -->
                        <div class="form-group mb-3">
                            <hr>
                            <div class="form-check">
                                <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                        type="checkbox" 
                                        value="1" 
                                        id="Actived" 
                                        name="Actived" 
                                        {{ old('Actived', $employe->Actived) ? 'checked' : '' }}>
                                <label class="form-check-label" for="Actived">
                                    Activer
                                </label>
                                @error('Actived')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                               
                            
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('employes.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
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
        // Aperçu de la nouvelle photo
        document.getElementById('FaceEncodingFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('facePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <label class="d-block text-muted mb-1">Nouvelle photo :</label>
                        <img src="${e.target.result}" 
                             alt="Aperçu de la nouvelle photo" 
                             style="max-width: 200px; max-height: 200px;" 
                             class="img-thumbnail">
                    `;
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Validation du PIN
        document.getElementById('Pin').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
        });
    </script>
    @endpush
</x-app-layout>