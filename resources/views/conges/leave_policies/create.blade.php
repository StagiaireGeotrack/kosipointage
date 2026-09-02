{{-- resources/views/conges/leave_policies/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouvelle Politique de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-policies.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-12">
                            <x-input-label for="name" :value="__('Nom de la politique')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name')" placeholder="{{ __('Ex: Politique CP Standard') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="calculation_method" :value="__('Méthode de calcul')" />
                            <span class="text-danger">*</span>
                            <select id="calculation_method" name="calculation_method" class="form-select mt-1" required>
                                <option value="working_days" {{ old('calculation_method') == 'working_days' ? 'selected' : '' }}>
                                    Jours ouvrés (Lundi-Vendredi)
                                </option>
                                <option value="business_days" {{ old('calculation_method') == 'business_days' ? 'selected' : '' }}>
                                    Jours ouvrables (Lundi-Samedi)
                                </option>
                                
                            </select>
                            <x-input-error :messages="$errors->get('calculation_method')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="weekend_days" :value="__('Jours de week-end')" />
                            <span class="text-danger">*</span>
                            <select id="weekend_days" name="weekend_days" class="form-select mt-1" required>
                                <option value="saturday_sunday" {{ old('weekend_days') == 'saturday_sunday' ? 'selected' : '' }}>
                                    Samedi et Dimanche
                                </option>
                                <option value="friday_saturday" {{ old('weekend_days') == 'friday_saturday' ? 'selected' : '' }}>
                                    Vendredi et Samedi
                                </option>
                                <option value="sunday_only" {{ old('weekend_days') == 'sunday_only' ? 'selected' : '' }}>
                                    Dimanche uniquement
                                </option>
                                <option value="none" {{ old('weekend_days') == 'none' ? 'selected' : '' }}>
                                    Aucun (7 jours sur 7)
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('weekend_days')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-4">
                            <x-input-label for="holiday_handling" :value="__('Gestion des jours fériés')" />
                            <span class="text-danger">*</span>
                            <select id="holiday_handling" name="holiday_handling" class="form-select mt-1" required>
                                <option value="skip" {{ old('holiday_handling') == 'skip' ? 'selected' : '' }}>
                                    Ignorer (ne pas compter)
                                </option>
                                <option value="count" {{ old('holiday_handling') == 'count' ? 'selected' : '' }}>
                                    Compter comme des jours travaillés
                                </option>
                               
                            </select>
                            <x-input-error :messages="$errors->get('holiday_handling')" class="mt-2" />
                        </div>

                        <div class="col-lg-4">
                            <x-input-label for="rounding_rule" :value="__('Règle d\'arrondi')" />
                            <span class="text-danger">*</span>
                            <select id="rounding_rule" name="rounding_rule" class="form-select mt-1" required>
                                <option value="none" {{ old('rounding_rule') == 'none' ? 'selected' : '' }}>
                                    Aucun arrondi
                                </option>
                               
                                <option value="full_day" {{ old('rounding_rule') == 'full_day' ? 'selected' : '' }}>
                                    Journée entière
                                </option>
                                
                            </select>
                            <x-input-error :messages="$errors->get('rounding_rule')" class="mt-2" />
                        </div>

                       

                    <div class="row g-3 mb-4">
                        <div class="col-lg-3">
                            <x-input-label for="exclude_holidays" :value="__('Exclure les jours fériés')" />
                            <div class="mt-1">
                                <input type="hidden" name="exclude_holidays" value="0">
                                <input type="checkbox" id="exclude_holidays" name="exclude_holidays" value="1" 
                                    {{ old('exclude_holidays', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="exclude_holidays" class="form-check-label ms-2">
                                    {{ __('Exclure automatiquement') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('exclude_holidays')" class="mt-2" />
                        </div>

                        
                        <div class="col-lg-3">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">
                                    {{ __('Politique active') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>

                        
                    </div>

                    @if($sites->count() > 0 && auth()->user()->IsSuperAdmin)
                        <div class="row g-3 mb-4">
                            <div class="col-lg-12">
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
                                <small class="text-muted">{{ __('Sélectionnez un siège spécifique ou laissez global') }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>