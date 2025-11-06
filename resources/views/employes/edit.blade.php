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
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

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
                            <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
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
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

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
                            <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                        </div>
                    
                        <!-- Photo de visage actuelle -->
                        @if($employe->FaceEncodingPath)
                            <div class="form-group mb-3">
                                <label class="d-block text-muted">Photo de visage actuelle :</label>
                                <img src="{{ route('employes.face.thumbnail', $employe->ID) }}" 
                                    alt="{{ $employe->Nom }}" 
                                    style="width: 200px; height: 200px; object-fit: cover;">
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
                                <x-input-error :messages="$errors->get('HasBiometricSetup')" class="mt-2" />
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
                            <div class="form-check">
                                <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                        type="checkbox" 
                                        value="1" 
                                        id="Actived" 
                                        name="Actived" 
                                        {{ old('Actived', $employe->Actived) ? 'checked' : '' }}
                                        {{ !Auth::user()->IsSuperAdmin ? 'disabled' : '' }}>
                                <label class="form-check-label" for="Actived">
                                    Activer
                                    @if(!Auth::user()->IsSuperAdmin)
                                        <small class="text-muted">(réservé aux super administrateurs)</small>
                                    @endif
                                </label>
                                <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
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