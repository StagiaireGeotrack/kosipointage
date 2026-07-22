<x-employe-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Mon Profil') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('employe.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="form-label fw-semibold">{{ __('Adresse e-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $employe->email) }}" required>
                            <small class="form-text text-muted">{{ __('Cette adresse est utilisée pour votre connexion.') }}</small>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Téléphone -->
                        <div class="form-group mb-4">
                            <label for="telephone" class="form-label fw-semibold">{{ __('Téléphone') }}</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone', $employe->telephone) }}">
                            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">{{ __('Changer le mot de passe') }}</h5>
                        <p class="text-muted small">{{ __('Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.') }}</p>

                        <!-- Nouveau mot de passe -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label fw-semibold">{{ __('Nouveau mot de passe') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="6">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">{{ __('Confirmer le mot de passe') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i> {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-employe-layout>
