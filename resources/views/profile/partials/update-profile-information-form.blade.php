<section>
    <header class="mb-3">
        <h2 class="fs-5 fw-medium text-dark">
            {{ __('Modification Identifiant ou E-mail') }}
        </h2>

        <p class="mt-1 small text-muted">
            {{ __("Veuillez changer par votre vrai adresse E-mail si possible.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update_Identifiant_email') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="Identifiant_email" :value="__('Identifiantou E-mail')" />
            <x-text-input id="Identifiant_email" name="Identifiant_email" type="text" class="form-control mt-1" value="{{ $Identifiant_email }}" required autofocus autocomplete="Identifiant_email" />
            <x-input-error class="mt-2" :messages="$errors->get('Identifiant_email')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button class="btn btn-primary">{{ __('Enregistrer') }}</x-primary-button>
        </div>
    </form>
</section>