{{-- resources/views/admin/sellers/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Modifier le Vendeur') }}
            </h2>
            <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('sellers.update', $seller->ID) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <x-input-label for="email" :value="__('Email')" />
                            <span class="text-danger">*</span>
                            <x-text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="form-control mt-1" 
                                :value="old('email', $seller->Identifiant_email)"
                                required
                            />
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
                            <x-text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="form-control mt-1"
                                minlength="6"
                                placeholder="{{ __('Laisser vide pour ne pas modifier') }}"
                            />
                            @error('password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-1">
                                {{ __('Laisser vide pour conserver le mot de passe actuel') }}
                            </small>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="actived" 
                                    name="actived"
                                    value="1"
                                    {{ old('actived', $seller->Actived) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="actived">
                                    {{ __('Compte actif') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <x-input-label :value="__('Sièges accessibles')" />
                            <span class="text-danger">*</span>
                            <div class="border rounded p-3 mt-1" style="max-height: 400px; overflow-y: auto;">
                                @if($sieges->isEmpty())
                                    <p class="text-muted mb-0">{{ __('Aucun siège disponible') }}</p>
                                @else
                                    @php
                                        $selectedSiegeIds = old('siege_ids', $seller->sellerSieges->pluck('ID')->toArray());
                                    @endphp
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                            {{ __('Tout sélectionner') }}
                                        </button>
                                    </div>
                                    <div class="row g-2">
                                        @foreach($sieges as $siege)
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input siege-checkbox" 
                                                        type="checkbox" 
                                                        name="siege_ids[]" 
                                                        value="{{ $siege->ID }}"
                                                        id="siege_{{ $siege->ID }}"
                                                        {{ in_array($siege->ID, $selectedSiegeIds) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="siege_{{ $siege->ID }}">
                                                        <strong>{{ $siege->Nom }}</strong>
                                                        @if($siege->Nom_Lieu_Ville)
                                                            <small class="text-muted d-block">{{ $siege->Nom_Lieu_Ville }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('siege_ids')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-1">
                                {{ __('Le vendeur doit avoir accès à au moins un siège') }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Enregistrer les modifications') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllBtn = document.getElementById('selectAllBtn');
            const checkboxes = document.querySelectorAll('.siege-checkbox');
            
            const updateButtonText = () => {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                selectAllBtn.textContent = allChecked ? '{{ __("Tout désélectionner") }}' : '{{ __("Tout sélectionner") }}';
            };
            
            if (selectAllBtn) {
                updateButtonText();
                
                selectAllBtn.addEventListener('click', function() {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                    });
                    
                    updateButtonText();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>