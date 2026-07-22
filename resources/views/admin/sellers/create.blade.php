{{-- resources/views/admin/sellers/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark">
                {{ __('Nouveau revendeur') }}
            </h2>
            <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('sellers.store') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Adresse email')" />
                        <x-text-input id="email" name="email" type="email" class="form-control mt-1" 
                            :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Mot de passe')" />
                        <x-text-input id="password" name="password" type="password" class="form-control mt-1" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <small class="text-muted">{{ __('Minimum 8 caractères') }}</small>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control mt-1" required />
                    </div>

                    <!-- IsManager -->
                    @if(auth()->user()->isTrueSuperAdmin())
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="IsManager" type="checkbox" name="IsManager" value="1" 
                                {{ old('IsManager') ? 'checked' : '' }} 
                                class="form-check-input">
                            <label for="IsManager" class="form-check-label">{{ __('Est un Manager (hérite des droits mais ne peut créer ses pairs)') }}</label>
                        </div>
                        <x-input-error :messages="$errors->get('IsManager')" class="mt-2" />
                    </div>
                    @elseif(auth()->user()->isSeller())
                    <div class="mb-3">
                        <div class="form-check">
                            <input id="IsManager" type="checkbox" name="IsManager" value="1" checked disabled class="form-check-input">
                            <label for="IsManager" class="form-check-label">{{ __('Est un Manager Vendeur (création obligatoire)') }}</label>
                        </div>
                    </div>
                    @endif

                    <!-- Sièges -->
                    <div class="mb-3">
                        <x-input-label for="sieges" :value="__('Sièges à associer')" />
                        <small class="text-muted d-block mb-2">
                            {{ __('Seuls les sièges non encore associés à un vendeur sont affichés') }}
                        </small>
                        
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @forelse($sieges as $siege)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="sieges[]" 
                                           value="{{ $siege->ID }}" 
                                           id="siege{{ $siege->ID }}"
                                           {{ in_array($siege->ID, old('sieges', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="siege{{ $siege->ID }}">
                                        <strong>{{ $siege->Nom }}</strong>    
                                        @if($siege->Pays && country($siege->Pays))
                                            - {{ strtoupper($siege->Pays) . " " . country($siege->Pays)->getName() }} - 
                                        @endif    
                                        @if($siege->Nom_Lieu_Ville)
                                            <small class="text-muted">({{ $siege->Nom_Lieu_Ville }})</small>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted text-center py-3">
                                    {{ __('Aucun siège disponible. Tous les sièges sont déjà associés à des vendeurs.') }}
                                </p>
                            @endforelse
                        </div>
                        <x-input-error :messages="$errors->get('sieges')" class="mt-2" />
                    </div>

                    <!-- Boutons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary" {{ $sieges->isEmpty() ? 'disabled' : '' }}>
                            {{ __('Créer le revendeur') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>