<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-input-error :messages="$errors->get('actived')" class="alert alert-danger alert-dismissible fade show" role="alert"/>

        <!-- Email Address -->
        <div class="my-4">
            <x-input-label for="email" :value="__('Identifiant ou e-mail')" />
            <x-text-input id="email" class="form-control mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
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

        <div class="d-flex align-items-center justify-content-end mt-4">

            <x-primary-button class="btn btn-primary">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center pt-3 border-top">
            <p class="text-muted small mb-2">{{ __('Vous êtes un employé ?') }}</p>
            <a href="{{ route('employe.login') }}" class="btn btn-outline-secondary btn-sm w-100">
                <i class="bi bi-person-badge me-1"></i> {{ __('Se connecter à l\'espace Employé') }}
            </a>
        </div>
    </form>
</x-guest-layout>