{{-- resources/views/employes/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau employé') }}
            </h2>
            <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('employes.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                    
                        <!-- num_mat - Super Admin ET Simple Admin -->
                        <div class="form-group mb-3">
                            <label for="num_mat">Numéro matricule</label>
                            <input type="text" 
                                    class="form-control @error('num_mat') is-invalid @enderror" 
                                    id="num_mat" 
                                    name="num_mat" 
                                    value="{{ old('num_mat') }}" 
                                    maxlength="50">
                            <x-input-error :messages="$errors->get('num_mat')" class="mt-2" />
                            <small class="form-text text-muted">Optionnel — unique par siège</small>
                        </div>

                        <!-- Nom -->
                        <div class="form-group mb-3">
                            <label for="Nom">Nom <span class="text-danger">*</span></label>
                            <input type="text" 
                                    class="form-control @error('Nom') is-invalid @enderror" 
                                    id="Nom" 
                                    name="Nom" 
                                    value="{{ old('Nom') }}" 
                                    required 
                                    autofocus>
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

                        <!-- BadgeID - SEULEMENT SUPER ADMIN -->
                        @if(Auth::user()->IsSuperAdmin)
                        <div class="form-group mb-3">
                            <label for="BadgeID">Badge ID <span class="text-danger">*</span></label>
                            <input type="password" 
                                    class="form-control @error('BadgeID') is-invalid @enderror" 
                                    id="BadgeID" 
                                    name="BadgeID" 
                                    value="{{ old('BadgeID') }}" 
                                    maxlength="25"
                                    required>
                            <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
                        </div>
                        @endif

                        <!-- SiegeID -->
                        <div class="form-group mb-3">
                            <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                            <select class="form-control @error('SiegeID') is-invalid @enderror" 
                                    id="SiegeID" 
                                    name="SiegeID" 
                                    required>
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" 
                                            {{ old('SiegeID') == $siege->ID || (request()->has('SiegeID') && request()->SiegeID == $siege->ID) ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <!-- Pin - SEULEMENT SIMPLE ADMIN -->
                        @if(Auth::user()->isSimpleAdmin())
                        <div class="form-group mb-3">
                            <label for="Pin">Code PIN</label>
                            <input type="password" 
                                            class="form-control @error('Pin') is-invalid @enderror" 
                                            id="Pin" 
                                            name="Pin" 
                                            value="{{ old('Pin') }}" 
                                            maxlength="6"
                                            inputmode="numeric"
                                            pattern="[0-9]{6}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                            <small class="form-text text-muted">Code PIN à 6 chiffres (optionnel)</small>
                        </div>
                        @endif

                        <!-- Actived -->
                        <div class="form-group my-3">
                            <div class="form-check">
                                <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                        type="checkbox" 
                                        value="1" 
                                        id="Actived" 
                                        name="Actived" 
                                        {{ old('Actived') ? 'checked' : '' }}
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
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validation du PIN (6 chiffres uniquement) - uniquement si le champ existe
        const pinInput = document.getElementById('Pin');
        if (pinInput) {
            pinInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });
        }
    </script>
    @endpush
</x-app-layout>