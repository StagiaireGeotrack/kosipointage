{{-- resources/views/conges/leave_policies/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $leavePolicy->isGlobal();
                    $hasOverride = isset($override) && $override;
                    $isCustomizable = $leavePolicy->is_customizable ?? false;
                    $isDeleted = $leavePolicy->trashed();
                @endphp

                @if($hasOverride && !$isSuperAdmin)
                    {{ __('Personnalisation de la politique globale') }}
                @elseif($isGlobal && $isSuperAdmin)
                    {{ __('Modifier la Politique (Globale)') }}
                @elseif($isGlobal && !$isSuperAdmin)
                    {{ __('Personnaliser la Politique Globale') }}
                @else
                    {{ __('Modifier la Politique') }}
                @endif
            </h2>
            <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez une politique globale pour votre siège. Les modifications n\'affecteront que votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$hasOverride && $isCustomizable)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez cette politique globale pour votre siège. Les modifications ne seront visibles que pour votre siège.') }}
                    </div>
                @endif

                @if($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Cette politique globale n\'est pas personnalisable. Vous ne pouvez pas la modifier.') }}
                    </div>
                @endif

                @if($isDeleted)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Cette politique a été supprimée le ') . $leavePolicy->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.leave-policies.restore', $leavePolicy->id) }}" method="POST" class="d-inline">
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
                    <form method="POST" action="{{ route('admin.leave-policies.update', $leavePolicy->id) }}">
                        @csrf
                        @method('PUT')

                        @if($hasOverride)
                            <input type="hidden" name="is_override" value="1">
                        @endif

                        <div class="row g-3 mb-4">
                            <div class="col-lg-12">
                                <x-input-label for="name" :value="__('Nom de la politique')" />
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
                                    value="{{ old('name', $override->name ?? $leavePolicy->name) }}"
                                    placeholder="{{ __('Ex: Politique CP Standard') }}"
                                    {{ ($isSuperAdmin || !$hasOverride) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->name)
                                    <small class="text-muted">Valeur globale : {{ $leavePolicy->name }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="calculation_method" :value="__('Méthode de calcul')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="calculation_method" name="calculation_method" class="form-select mt-1" 
                                    {{ ($hasOverride && !$isSuperAdmin) ? '' : 'required' }}>
                                    <option value="working_days" {{ old('calculation_method', $override->calculation_method ?? $leavePolicy->calculation_method) == 'working_days' ? 'selected' : '' }}>
                                        Jours ouvrés (Lundi-Vendredi)
                                    </option>
                                    <option value="business_days" {{ old('calculation_method', $override->calculation_method ?? $leavePolicy->calculation_method) == 'business_days' ? 'selected' : '' }}>
                                        Jours ouvrables (Lundi-Samedi)
                                    </option>
                                    
                                </select>
                                <x-input-error :messages="$errors->get('calculation_method')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->calculation_method)
                                    <small class="text-muted">Valeur globale : {{ $leavePolicy->getCalculationMethodLabel() }}</small>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="weekend_days" :value="__('Jours de week-end')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="weekend_days" name="weekend_days" class="form-select mt-1" 
                                    {{ ($hasOverride && !$isSuperAdmin) ? '' : 'required' }}>
                                    <option value="saturday_sunday" {{ old('weekend_days', $override->weekend_days ?? $leavePolicy->weekend_days) == 'saturday_sunday' ? 'selected' : '' }}>
                                        Samedi et Dimanche
                                    </option>
                                    <option value="friday_saturday" {{ old('weekend_days', $override->weekend_days ?? $leavePolicy->weekend_days) == 'friday_saturday' ? 'selected' : '' }}>
                                        Vendredi et Samedi
                                    </option>
                                    <option value="sunday_only" {{ old('weekend_days', $override->weekend_days ?? $leavePolicy->weekend_days) == 'sunday_only' ? 'selected' : '' }}>
                                        Dimanche uniquement
                                    </option>
                                    <option value="none" {{ old('weekend_days', $override->weekend_days ?? $leavePolicy->weekend_days) == 'none' ? 'selected' : '' }}>
                                        Aucun (7 jours sur 7)
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('weekend_days')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->weekend_days)
                                    <small class="text-muted">Valeur globale : {{ $leavePolicy->getWeekendDaysLabel() }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="holiday_handling" :value="__('Gestion des jours fériés')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="holiday_handling" name="holiday_handling" class="form-select mt-1" 
                                    {{ ($hasOverride && !$isSuperAdmin) ? '' : 'required' }}>
                                    <option value="skip" {{ old('holiday_handling', $override->holiday_handling ?? $leavePolicy->holiday_handling) == 'skip' ? 'selected' : '' }}>
                                        Ignorer (ne pas compter)
                                    </option>
                                    <option value="count" {{ old('holiday_handling', $override->holiday_handling ?? $leavePolicy->holiday_handling) == 'count' ? 'selected' : '' }}>
                                        Compter comme des jours travaillés
                                    </option>
                                    
                                </select>
                                <x-input-error :messages="$errors->get('holiday_handling')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->holiday_handling)
                                    <small class="text-muted">Valeur globale : {{ $leavePolicy->getHolidayHandlingLabel() }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="rounding_rule" :value="__('Règle d\'arrondi')" />
                                @if(!$hasOverride || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="rounding_rule" name="rounding_rule" class="form-select mt-1" 
                                    {{ ($hasOverride && !$isSuperAdmin) ? '' : 'required' }}>
                                    <option value="none" {{ old('rounding_rule', $override->rounding_rule ?? $leavePolicy->rounding_rule) == 'none' ? 'selected' : '' }}>
                                        Aucun arrondi
                                    </option>
                                    <option value="half_day" {{ old('rounding_rule', $override->rounding_rule ?? $leavePolicy->rounding_rule) == 'half_day' ? 'selected' : '' }}>
                                        Demi-journée
                                    </option>
                                    <option value="full_day" {{ old('rounding_rule', $override->rounding_rule ?? $leavePolicy->rounding_rule) == 'full_day' ? 'selected' : '' }}>
                                        Journée entière
                                    </option>
                                    <option value="quarter_hour" {{ old('rounding_rule', $override->rounding_rule ?? $leavePolicy->rounding_rule) == 'quarter_hour' ? 'selected' : '' }}>
                                        Quart d'heure
                                    </option>
                                    <option value="half_hour" {{ old('rounding_rule', $override->rounding_rule ?? $leavePolicy->rounding_rule) == 'half_hour' ? 'selected' : '' }}>
                                        Demi-heure
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('rounding_rule')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->rounding_rule)
                                    <small class="text-muted">Valeur globale : {{ $leavePolicy->getRoundingRuleLabel() }}</small>
                                @endif
                            </div>

                           
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-3">
                                <x-input-label for="exclude_holidays" :value="__('Exclure les jours fériés')" />
                                <div class="mt-1">
                                    <input type="hidden" name="exclude_holidays" value="0">
                                    <input type="checkbox" id="exclude_holidays" name="exclude_holidays" value="1" 
                                        {{ old('exclude_holidays', $override->exclude_holidays ?? $leavePolicy->exclude_holidays) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="exclude_holidays" class="form-check-label ms-2">
                                        {{ __('Exclure automatiquement') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('exclude_holidays')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin && $leavePolicy->exclude_holidays !== null)
                                    <small class="d-block text-muted">Valeur globale : {{ $leavePolicy->exclude_holidays ? 'Oui' : 'Non' }}</small>
                                @endif
                            </div>

                           

                            <div class="col-lg-3">
                                <x-input-label for="is_active" :value="__('Actif')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                        {{ old('is_active', $override->is_active ?? $leavePolicy->is_active) ? 'checked' : '' }} 
                                        class="form-check-input">
                                    <label for="is_active" class="form-check-label ms-2">
                                        {{ __('Politique active') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                            </div>

                            <div class="col-lg-3">
                                <x-input-label for="is_customizable" :value="__('Personnalisable par siège')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_customizable" value="0">
                                    <input type="checkbox" id="is_customizable" name="is_customizable" value="1" 
                                        {{ old('is_customizable', $leavePolicy->is_customizable) ? 'checked' : '' }} 
                                        class="form-check-input" {{ $hasOverride && !$isSuperAdmin ? 'disabled' : '' }}>
                                    <label for="is_customizable" class="form-check-label ms-2">
                                        {{ __('Personnalisable') }}
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                                @if($hasOverride && !$isSuperAdmin)
                                    <small class="text-muted">Ce champ est géré au niveau global</small>
                                @endif
                            </div>
                        </div>

                        @if($isSuperAdmin)
                            <div class="row g-3 mb-4">
                                <div class="col-lg-12">
                                    <x-input-label for="site_id" :value="__('Siège')" />
                                    <select id="site_id" name="site_id" class="form-select mt-1">
                                        <option value="">{{ __('Global (tous les sièges)') }}</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->ID }}" 
                                                {{ old('site_id', $leavePolicy->site_id) == $site->ID ? 'selected' : '' }}>
                                                {{ $site->Nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                                    <small class="text-muted">{{ __('Sélectionnez un siège spécifique ou laissez global') }}</small>
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
                            <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                @elseif($isGlobal && !$isSuperAdmin && !$isCustomizable)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Cette politique globale n\'est pas personnalisable. Vous ne pouvez pas la modifier.') }}
                    </div>
                    <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Cette politique est en lecture seule.') }}
                    </div>
                    <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                        {{ __('Retour à la liste') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>