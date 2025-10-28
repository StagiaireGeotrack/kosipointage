{{-- resources/views/employes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Modification employé') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('employes.update', $employe->ID) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div class="mb-3">
                            <x-input-label for="Nom" :value="__('Nom')" />
                            <x-text-input id="Nom" class="form-control mt-1" type="text" name="Nom" :value="old('Nom', $employe->Nom)" required autofocus />
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

                        <!-- BadgeID -->
                        <div class="mb-3">
                            <x-input-label for="BadgeID" :value="__('Badge ID')" />
                            <x-text-input id="BadgeID" class="form-control mt-1" type="text" name="BadgeID" :value="old('BadgeID', $employe->BadgeID)" required />
                            <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
                        </div>

                        <!-- SiegeID -->
                        <div class="mb-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionner un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID', $employe->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <!-- Pin -->
                        <div class="mb-3">
                            <x-input-label for="Pin" :value="__('Code PIN à 6 chiffres')" />
                            <x-text-input id="Pin" class="form-control mt-1" type="text" name="Pin" :value="old('Pin', $employe->Pin)" />
                            <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                        </div>

                        <!-- FaceEncodingFile -->
                        <div class="mb-3">
                            <x-input-label for="FaceEncodingFile" :value="__('Face image')" />
                            @if($employe->HasFaceSetup)
                                <div class="mb-2">
                                    <img src="{{ route('employes.face.thumbnail', $employe->ID) }}" alt="{{ $employe->Nom }}" class="rounded" style="height: 80px; width: 80px; object-fit: cover;">
                                </div>
                            @endif
                            <input id="FaceEncodingFile" name="FaceEncodingFile" type="file" class="form-control mt-1" accept="image/*" />
                            <x-input-error :messages="$errors->get('FaceEncodingFile')" class="mt-2" />
                        </div>

                        <!-- Options (HasBiometricSetup) -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="HasBiometricSetup" type="checkbox" name="HasBiometricSetup" value="1" {{ old('HasBiometricSetup', $employe->HasBiometricSetup) ? 'checked' : '' }} class="form-check-input">
                                <label for="HasBiometricSetup" class="form-check-label">{{ __('Empreinte') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('HasBiometricSetup')" class="mt-2" />
                        </div>

                        <!-- Actived -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input id="Actived" type="checkbox" name="Actived" value="1" {{ old('Actived', $employe->Actived) ? 'checked' : '' }} class="form-check-input">
                                <label for="Actived" class="form-check-label">{{ __('Activer') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                        </div>

                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a href="{{ route('employes.index') }}" class="btn btn-secondary me-2">
                                {{ __('ANNULER') }}
                            </a>
                            <x-primary-button class="btn btn-primary">
                                {{ __('MODIFIER') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>