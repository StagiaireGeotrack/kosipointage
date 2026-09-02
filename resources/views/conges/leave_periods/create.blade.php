{{-- resources/views/conges/leave_periods/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouvelle Période de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.leave-periods.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="name" :value="__('Nom de la période')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type de congé') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-4">
                            <x-input-label for="start_date" :value="__('Date de début')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="start_date" name="start_date" type="date" class="form-control mt-1" :value="old('start_date')" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="col-lg-4">
                            <x-input-label for="end_date" :value="__('Date de fin')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="end_date" name="end_date" type="date" class="form-control mt-1" :value="old('end_date')" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="col-lg-4">
                            <x-input-label for="submission_deadline" :value="__('Date limite de pose')" />
                            <x-text-input id="submission_deadline" name="submission_deadline" type="date" class="form-control mt-1" :value="old('submission_deadline')" />
                            <x-input-error :messages="$errors->get('submission_deadline')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-4">
                            <x-input-label for="status" :value="__('Statut')" />
                            <span class="text-danger">*</span>
                            <select id="status" name="status" class="form-select mt-1" required>
                                <option value="preparing" {{ old('status') == 'preparing' ? 'selected' : '' }}>{{ __('Préparation') }}</option>
                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>{{ __('Ouvert') }}</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>{{ __('Fermé') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        

                        <div class="col-lg-4">
                            <x-input-label for="is_active" :value="__('Actif')" />
                            <div class="mt-1">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input">
                                <label for="is_active" class="form-check-label ms-2">{{ __('Période active') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="allow_rollover" :value="__('Report des jours')" />
                            <div class="mt-1">
                                <input type="hidden" name="allow_rollover" value="0">
                                <input type="checkbox" id="allow_rollover" name="allow_rollover" value="1" {{ old('allow_rollover') ? 'checked' : '' }} class="form-check-input" onchange="toggleRolloverFields()">
                                <label for="allow_rollover" class="form-check-label ms-2">{{ __('Autoriser le report des jours non pris') }}</label>
                            </div>
                            <x-input-error :messages="$errors->get('allow_rollover')" class="mt-2" />
                        </div>

                        <div class="col-lg-6" id="rollover_fields" style="{{ old('allow_rollover') ? '' : 'display: none;' }}">
                            <div class="row g-3">
                                <div class="col-6">
                                    <x-input-label for="max_rollover_days" :value="__('Nombre max de jours reportés')" />
                                    <x-text-input id="max_rollover_days" name="max_rollover_days" type="number" class="form-control mt-1" :value="old('max_rollover_days')" min="0" />
                                    <x-input-error :messages="$errors->get('max_rollover_days')" class="mt-2" />
                                    <small class="text-muted">{{ __('Laissez vide pour illimité') }}</small>
                                </div>

                                <div class="col-6">
                                    <x-input-label for="rollover_expiry_date" :value="__("Date d'expiration du report")" />
                                    <x-text-input id="rollover_expiry_date" name="rollover_expiry_date" type="date" class="form-control mt-1" :value="old('rollover_expiry_date')" />
                                    <x-input-error :messages="$errors->get('rollover_expiry_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

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
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="is_customizable" :value="__('Personnalisable par siège')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_customizable" value="0">
                                    <input type="checkbox" id="is_customizable" name="is_customizable" value="1" {{ old('is_customizable') ? 'checked' : '' }} class="form-check-input">
                                    <label for="is_customizable" class="form-check-label ms-2">{{ __('Les administrateurs de siège peuvent personnaliser cette période') }}</label>
                                </div>
                                <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                            </div>
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            function toggleRolloverFields() {
                const checkbox = document.getElementById('allow_rollover');
                const fields = document.getElementById('rollover_fields');
                if (checkbox.checked) {
                    fields.style.display = 'block';
                } else {
                    fields.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                toggleRolloverFields();
            });
        </script>
    @endpush
</x-app-layout>