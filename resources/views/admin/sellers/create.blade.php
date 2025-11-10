{{-- resources/views/admin/sellers/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Créer un Nouveau Vendeur') }}
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

                <form action="{{ route('sellers.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <x-input-label for="email" :value="__('Email')" />
                            <span class="text-danger">*</span>
                            <x-text-input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="form-control mt-1" 
                                :value="old('email')"
                                required
                                placeholder="vendeur@example.com"
                            />
                            @error('email')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <x-input-label for="password" :value="__('Mot de passe')" />
                            <span class="text-danger">*</span>
                            <x-text-input 
                                id="password" 
                                name="password" 
                                type="password" 
                                class="form-control mt-1"
                                required
                                minlength="6"
                                placeholder="{{ __('Minimum 6 caractères') }}"
                            />
                            @error('password')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <x-input-label :value="__('Sièges accessibles')" />
                            <span class="text-danger">*</span>
                            <div class="border rounded p-3 mt-1" style="max-height: 400px; overflow-y: auto;">
                                @if($sieges->isEmpty())
                                    <p class="text-muted mb-0">{{ __('Aucun siège disponible') }}</p>
                                @else
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
                                                        {{ in_array($siege->ID, old('siege_ids', [])) ? 'checked' : '' }}
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
                                {{ __('Sélectionnez au moins un siège auquel le vendeur aura accès') }}
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Créer le Vendeur') }}
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
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    const checkboxes = document.querySelectorAll('.siege-checkbox');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked;
                    });
                    
                    selectAllBtn.textContent = allChecked ? '{{ __("Tout sélectionner") }}' : '{{ __("Tout désélectionner") }}';
                });
            }
        });
    </script>
    @endpush
</x-app-layout>