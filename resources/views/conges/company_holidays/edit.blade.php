{{-- resources/views/conges/company_holidays/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $companyHoliday->isGlobal();
                    $hasOverride = isset($override) && $override;
                @endphp

                @if($hasOverride && !$isSuperAdmin)
                    {{ __('Personnalisation du jour férié global') }}
                @elseif($isGlobal && $isSuperAdmin)
                    {{ __('Modifier le Jour Férié (Global)') }}
                @elseif($isGlobal && !$isSuperAdmin)
                    {{ __('Personnaliser le Jour Férié Global') }}
                @else
                    {{ __('Modifier le Jour Férié') }}
                @endif
            </h2>
            <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $companyHoliday->isGlobal();
                    $hasOverride = isset($override) && $override;
                    $isCustomizable = $companyHoliday->is_customizable ?? false;
                    $isDeleted = $companyHoliday->trashed();
                @endphp

                @if($hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez un jour férié global pour votre siège. Les modifications n\'affecteront que votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$hasOverride && $isCustomizable)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez ce jour férié global pour votre siège. Les modifications ne seront visibles que pour votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce jour férié global n\'est pas personnalisable. Vous ne pouvez pas le modifier.') }}
                    </div>
                @endif

                @if($isDeleted)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce jour férié a été supprimé le ') . $companyHoliday->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.company-holidays.restore', $companyHoliday->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if((!$isGlobal || $isSuperAdmin || ($isGlobal && $isCustomizable)) && !$isDeleted)
                    <form method="POST" action="{{ route('admin.company-holidays.update', $companyHoliday->id) }}">
                        @csrf
                        @method('PUT')

                        @if($hasOverride)
                            <input type="hidden" name="is_override" value="1">
                        @endif

                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="date" :value="__('Date')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="date" 
                                    name="date" 
                                    type="date" 
                                    class="form-control mt-1 @error('date') is-invalid @enderror" 
                                    value="{{ old('date', isset($override->date) && !$isSuperAdmin ? $override->date->format('Y-m-d') : ($companyHoliday->date ? $companyHoliday->date->format('Y-m-d') : '')) }}"
                                    {{ ($isSuperAdmin || !$hasOverride) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $companyHoliday->date)
                                    <small class="text-muted">Valeur globale : {{ $companyHoliday->date->format('d/m/Y') }}</small>
                                @endif
                                <small class="text-muted">{{ __('Sélectionnez la date du jour férié') }}</small>
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="name" :value="__('Nom du jour férié')" />
                                @if($hasOverride && !$isSuperAdmin)
                                    <span class="text-muted small ms-2">{{ __('(Laisser vide pour hériter du global)') }}</span>
                                @else
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="form-control mt-1 @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $override->name ?? $companyHoliday->name) }}"
                                    placeholder="{{ __('Ex: Noël, Jour de l\'An, etc.') }}"
                                    {{ ($isSuperAdmin || !$hasOverride) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $companyHoliday->name)
                                    <small class="text-muted">Valeur globale : {{ $companyHoliday->name }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="is_recurring" :value="__('Récurrent')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_recurring" value="0">
                                    <input type="checkbox" id="is_recurring" name="is_recurring" value="1" 
                                        {{ old('is_recurring', $override->is_recurring ?? $companyHoliday->is_recurring) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="is_recurring" class="form-check-label ms-2">
                                        {{ __('Ce jour férié revient chaque année') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('is_recurring')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $companyHoliday->is_recurring !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $companyHoliday->is_recurring ? 'Oui' : 'Non' }}</small>
                                @endif
                                <small class="text-muted">{{ __('Cochez si ce jour férié est récurrent chaque année') }}</small>
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="is_active" :value="__('Actif')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                        {{ old('is_active', $override->is_active ?? $companyHoliday->is_active) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="is_active" class="form-check-label ms-2">
                                        {{ __('Jour férié actif') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>
                        </div>

                        @if($isSuperAdmin)
                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <x-input-label for="site_id" :value="__('Siège')" />
                                    <select id="site_id" name="site_id" class="form-select mt-1">
                                        <option value="">{{ __('Global (tous les sièges)') }}</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->ID }}" 
                                                {{ old('site_id', $companyHoliday->site_id) == $site->ID ? 'selected' : '' }}>
                                                {{ $site->Nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                                    <small class="text-muted">{{ __('Sélectionnez un siège pour un jour férié spécifique') }}</small>
                                </div>

                                <div class="col-lg-6">
                                    <x-input-label for="is_customizable" :value="__('Personnalisable par siège')" />
                                    <div class="mt-1">
                                        <input type="hidden" name="is_customizable" value="0">
                                        <input type="checkbox" id="is_customizable" name="is_customizable" value="1" 
                                            {{ old('is_customizable', $companyHoliday->is_customizable) ? 'checked' : '' }} 
                                            class="form-check-input">
                                        <label for="is_customizable" class="form-check-label ms-2">
                                            {{ __('Les administrateurs de siège peuvent personnaliser ce jour férié') }}
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                                    <small class="text-muted">{{ __('Permet aux admins de siège de modifier ce jour férié pour leur site') }}</small>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <x-primary-button class="btn btn-primary">
                                @if($hasOverride && !$isSuperAdmin)
                                    {{ __('Personnaliser') }}
                                @elseif($isGlobal && !$isSuperAdmin)
                                    {{ __('Personnaliser pour mon siège') }}
                                @else
                                    {{ __('Mettre à jour') }}
                                @endif
                            </x-primary-button>
                            <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                @elseif($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce jour férié global n\'est pas personnalisable. Vous ne pouvez pas le modifier.') }}
                    </div>
                    <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Ce jour férié est en lecture seule.') }}
                    </div>
                    <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>