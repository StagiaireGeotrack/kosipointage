{{-- Modification --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modifier l'employé : {{ $employe->num_mat ? "N° Matricule " . $employe->num_mat . " - " : "" }} {{ $employe->Nom }} 
            </h2>
            <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
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

                        <!-- num_mat - Super Admin ET Simple Admin -->
                        <div class="form-group mb-3">
                            <label for="num_mat">Numéro matricule</label>
                            <input type="text" 
                                    class="form-control @error('num_mat') is-invalid @enderror" 
                                    id="num_mat" 
                                    name="num_mat" 
                                    value="{{ old('num_mat', $employe->num_mat) }}" 
                                    maxlength="50">
                            @error('num_mat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optionnel — unique par siège</small>
                        </div>

                        <!-- Nom - TOUS LES UTILISATEURS PEUVENT MODIFIER -->
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

                        <!-- SiegeID - SEULEMENT SUPER ADMIN -->
                        <div class="form-group mb-3">
                            <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                            @if(Auth::user()->IsSuperAdmin)
                                {{-- Super Admin : select MODIFIABLE --}}
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
                            @else
                                {{-- Non Super Admin : juste disabled (valeur non envoyée) --}}
                                <select class="form-control" 
                                        id="SiegeID" 
                                        disabled>
                                    <option value="">Sélectionnez un siège</option>
                                    @foreach($sieges as $siege)
                                        <option value="{{ $siege->ID }}" 
                                                {{ $employe->SiegeID == $siege->ID ? 'selected' : '' }}>
                                            {{ $siege->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Modification réservée aux super administrateurs</small>
                            @endif
                            @error('SiegeID')
                                <div class="invalid-feedback {{ Auth::user()->IsSuperAdmin ? 'd-block' : '' }}">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if( Auth::user()->IsSuperAdmin )
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BadgeID - SEULEMENT SUPER ADMIN -->
                                <div class="form-group mb-3">
                                    <label for="BadgeID">Badge ID</label>
                                    @if(Auth::user()->IsSuperAdmin)
                                        {{-- Super Admin : input MODIFIABLE --}}
                                        <input type="password" 
                                                class="form-control @error('BadgeID') is-invalid @enderror" 
                                                id="BadgeID" 
                                                name="BadgeID" 
                                                value="{{ old('BadgeID') }}" 
                                                maxlength="25">
                                    @else
                                        {{-- Non Super Admin : disabled --}}
                                        <input type="text" 
                                                class="form-control" 
                                                id="BadgeID" 
                                                value="{{ $employe->BadgeID }}" 
                                                maxlength="25"
                                                disabled>
                                    @endif
                                    <small class="form-text text-muted">
                                        Identifiant unique de l'employé
                                        @if(!Auth::user()->IsSuperAdmin)
                                            <span class="text-warning">(modification réservée aux super administrateurs)</span>
                                        @endif
                                    </small>
                                    @error('BadgeID')
                                        <div class="invalid-feedback {{ Auth::user()->IsSuperAdmin ? 'd-block' : '' }}">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        @if( Auth::user()->isSimpleAdmin() )
                            @if(empty($employe->Pin))
                            <div class="row">
                                <div class="col-md-12">                                
                                    <!-- Pin - SEULEMENT SUPER ADMIN -->
                                    <div class="form-group mb-3">
                                        <label for="Pin">Code PIN (6 chiffres)</label>
                                        <input type="password" 
                                            class="form-control @error('Pin') is-invalid @enderror" 
                                            id="Pin" 
                                            name="Pin" 
                                            value="{{ old('Pin', $employe->Pin) }}" 
                                            maxlength="6"
                                            inputmode="numeric"
                                            pattern="[0-9]{6}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif

                        <!-- Actived - SEULEMENT SUPER ADMIN -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                @if(Auth::user()->IsSuperAdmin)
                                    {{-- Super Admin peut modifier --}}
                                    <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                            type="checkbox" 
                                            value="1" 
                                            id="Actived" 
                                            name="Actived" 
                                            {{ old('Actived', $employe->Actived) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="Actived">
                                        Activer
                                    </label>
                                @else
                                    {{-- Non Super Admin : disabled + hidden --}}
                                    <input class="form-check-input" 
                                            type="checkbox" 
                                            id="Actived_display" 
                                            {{ $employe->Actived ? 'checked' : '' }}
                                            disabled>
                                    <input type="hidden" name="Actived" value="{{ $employe->Actived ? '1' : '0' }}">
                                    <label class="form-check-label" for="Actived_display">
                                        Activer
                                        <small class="text-muted">(réservé aux super administrateurs)</small>
                                    </label>
                                @endif
                                @error('Actived')
                                    <div class="invalid-feedback {{ Auth::user()->IsSuperAdmin ? '' : 'd-block' }}">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                               
                            
                    </div>

                    {{-- Bouton submit pour TOUS LES UTILISATEURS (au moins pour modifier le Nom) --}}
                    @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                    @endif
                </form>

                @if (auth()->user()->isSimpleAdmin())
                    @if(!empty($employe->Pin))                
                        <hr>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-info"
                                                                onclick="setResetPinAction('{{ route('employes.reset-pin', $employe->ID) }}', '{{ $employe->Nom }}')"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#resetPinModal"
                                                                title="Réinitialiser le PIN">
                                <i class="bi bi-save"></i> Réinitialiser le Code Pin
                            </button>
                        </div>
                    @endif
                @endif

                <!-- Modal de confirmation réinitialisation Pin Employé -->
                <div class="modal fade" id="resetPinModal" tabindex="-1" aria-labelledby="resetPinModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="resetPinModalLabel">{{ __('Réinitialisation du code PIN') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment réinitialiser le code PIN de cet employé ?') }}</p>
                                <p class="fw-bold" id="details_employee_pin"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                <form id="resetPinForm" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">{{ __('Réinitialiser') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Aperçu de la nouvelle photo
        const faceInput = document.getElementById('FaceEncodingFile');
        if (faceInput) {
            faceInput.addEventListener('change', function(e) {
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
        }

        // Validation du PIN pour Super Admin
        @if(Auth::user()->IsSuperAdmin)
        const pinInput = document.getElementById('Pin');
        if (pinInput) {
            pinInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });
        }
        @endif

        function setResetPinAction(url, nom) {
            document.getElementById('resetPinForm').action = url;
            document.getElementById('details_employee_pin').textContent = nom;
        }

    </script>
    @endpush
</x-app-layout>