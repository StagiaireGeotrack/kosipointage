{{-- resources/views/employes/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('app.create_employee') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('employes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <x-input-label for="Nom" :value="__('app.name')" />
                            <x-text-input id="Nom" class="form-control mt-1" type="text" name="Nom" :value="old('Nom')" required autofocus />
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

                        <!-- BadgeID -->
                        <div class="mb-3">
                            <x-input-label for="BadgeID" :value="__('app.badge_id')" />
                            <x-text-input id="BadgeID" class="form-control mt-1" type="text" name="BadgeID" :value="old('BadgeID')" required />
                            <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
                        </div>

                        <!-- SiegeID -->
                        <div class="mb-3">
                            <x-input-label for="SiegeID" :value="__('app.office')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1" required>
                                <option value="">{{ __('app.select_office') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID') == $siege->ID || (request()->has('SiegeID') && request()->SiegeID == $siege->ID) ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <!-- Pin -->
                        <div class="mb-3">
                            <x-input-label for="Pin" :value="__('app.pin')" />
                            <x-text-input id="Pin" class="form-control mt-1" type="text" name="Pin" :value="old('Pin')" />
                            <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                        </div>

                        <!-- FaceEncodingFile -->
                        <div class="mb-3">
                            <x-input-label for="FaceEncodingFile" :value="__('app.face_encoding')" />
                            <input id="FaceEncodingFile" name="FaceEncodingFile" type="file" class="form-control mt-1" accept="image/*" />
                            <small class="form-text text-muted">{{ __('app.face_encoding_help') }}</small>
                            <x-input-error :messages="$errors->get('FaceEncodingFile')" class="mt-2" />
                        </div>

                        <!-- Options (HasBiometricSetup) -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="HasBiometricSetup" type="checkbox" name="HasBiometricSetup" value="1" {{ old('HasBiometricSetup') ? 'checked' : '' }} class="form-check-input">
                                <label for="HasBiometricSetup" class="form-check-label">{{ __('app.has_biometric_setup') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('HasBiometricSetup')" class="mt-2" />
                        </div>

                        <!-- Actived -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="Actived" type="checkbox" name="Actived" value="1" {{ old('Actived', '1') ? 'checked' : '' }} class="form-check-input">
                                <label for="Actived" class="form-check-label">{{ __('app.active') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a href="{{ route('employes.index') }}" class="btn btn-secondary me-2">
                                {{ __('app.cancel') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('app.create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>