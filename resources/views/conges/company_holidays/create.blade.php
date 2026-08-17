{{-- resources/views/conges/company_holidays/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau Jour Férié') }}
            </h2>
            <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.company-holidays.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="date" :value="__('Date')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="date" name="date" type="date" class="form-control mt-1" :value="old('date')" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            <small class="text-muted">{{ __('Sélectionnez la date du jour férié') }}</small>
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="name" :value="__('Nom du jour férié')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name')" placeholder="{{ __('Ex: Noël, Jour de l\'An, etc.') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="is_recurring" :value="__('Récurrent')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_recurring" value="0">
                                <input type="checkbox" id="is_recurring" name="is_recurring" value="1" 
                                    {{ old('is_recurring') ? 'checked' : '' }} class="form-check-input">
                                <label for="is_recurring" class="form-check-label ms-2">
                                    {{ __('Ce jour férié revient chaque année') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_recurring')" class="mt-2" />
                            <small class="text-muted">{{ __('Cochez si ce jour férié est récurrent chaque année (ex: Noël, 1er Mai)') }}</small>
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Jour férié actif') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    @php
                        $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    @endphp

                    @if($sites->count() > 0)
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="site_id" :value="__('Siège')" />
                                <select id="site_id" name="site_id" class="form-select mt-1">
                                    <option value="">{{ __('Global (tous les sièges)') }}</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                            {{ $site->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                                <small class="text-muted">{{ __('Sélectionnez un siège pour un jour férié spécifique, ou laissez global pour tous') }}</small>
                            </div>

                            @if($isSuperAdmin)
                                <div class="col-lg-6">
                                    <x-input-label for="is_customizable" :value="__('Personnalisable par siège')" />
                                    <div class="mt-1">
                                        <input type="hidden" name="is_customizable" value="0">
                                        <input type="checkbox" id="is_customizable" name="is_customizable" value="1" 
                                            {{ old('is_customizable') ? 'checked' : '' }} class="form-check-input">
                                        <label for="is_customizable" class="form-check-label ms-2">
                                            {{ __('Les administrateurs de siège peuvent personnaliser ce jour férié') }}
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                                    <small class="text-muted">{{ __('Permet aux admins de siège de modifier ce jour férié pour leur site') }}</small>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>