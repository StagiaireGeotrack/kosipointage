<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <div class="text-center mb-4">
        <h4 class="fw-bold">{{ __('Portail Employé') }}</h4>
        <p class="text-muted">{{ __('Veuillez vous connecter pour accéder à votre espace.') }}</p>
    </div>

    <form method="POST" action="{{ route('employe.login') }}">
        @csrf
        @if ($errors->has('account_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ $errors->first('account_error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Email Address -->
        <div class="my-4">
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input id="email" class="form-control mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="exemple@entreprise.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="form-control mt-1"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Se souvenir de moi') }}</label>
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <x-primary-button class="btn btn-primary w-100">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>
        
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                {{ __('Aller vers l\'espace Administration') }}
            </a>
        </div>
    </form>
</x-guest-layout>
