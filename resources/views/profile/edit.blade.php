<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm mb-2">
            <div class="card-body p-2 p-sm-3">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card shadow-sm mt-3 mb-2">
            <div class="card-body p-2 p-sm-3">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>