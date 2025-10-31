<section>
    <header class="mb-3">
        <h2 class="fs-5 fw-medium text-dark">
            {{ __('Modification mot de passe') }}
        </h2>
    </header>

    <form method="post" action="{{ route('profile.update_Password') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control mt-1" autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" />
            <x-text-input id="update_password_password" name="password" type="password" class="form-control mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le nouveau mot de passe')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button class="btn btn-primary">{{ __('Enregistrer') }}</x-primary-button>
        </div>
    </form>
</section>