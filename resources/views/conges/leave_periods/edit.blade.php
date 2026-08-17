{{-- resources/views/conges/leave_periods/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                @if($override)
                    {{ __('Personnalisation de la période globale') }}
                @elseif(auth()->user()->IsSuperAdmin ?? false)
                    {{ __('Modifier la Période de Congé (Globale)') }}
                @else
                    {{ __('Modifier la Période de Congé') }}
                @endif
            </h2>
            <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                @endphp

                @if($override)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Vous personnalisez une période globale pour votre siège. Les modifications n\'affecteront que votre siège.') }}
                        @if($leavePeriod->deleted_at)
                            <div class="mt-2">
                                <span class="badge bg-danger">{{ __('Cette période globale a été supprimée par le Super Admin') }}</span>
                            </div>
                        @endif
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

                <form method="POST" action="{{ route('admin.leave-periods.update', $leavePeriod->id) }}">
                    @csrf
                    @method('PUT')

                    @if($override)
                        <input type="hidden" name="is_override" value="1">
                    @endif

                    @if($isSuperAdmin || !$override || ($override && $leavePeriod->is_customizable))
                        <div class="row g-3 mb-4">
                            <div class="col-lg-6">
                                <x-input-label for="name" :value="__('Nom de la période')" />
                                @if($override && !$isSuperAdmin)
                                    <span class="text-muted small ms-2">{{ __('(Laisser vide pour hériter du global)') }}</span>
                                @elseif(!$isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="form-control mt-1 @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $override->name ?? $leavePeriod->name) }}"
                                    {{ ($isSuperAdmin || !$override) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                @if($override && !$isSuperAdmin && $leavePeriod->name)
                                    <small class="text-muted">Valeur globale : {{ $leavePeriod->name }}</small>
                                @endif
                            </div>

                            <div class="col-lg-6">
                                <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                                @if(!$override || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="leave_type_id" name="leave_type_id" class="form-select mt-1" 
                                    {{ ($override && !$isSuperAdmin) ? 'disabled' : '' }}>
                                    <option value="">{{ __('Sélectionnez un type de congé') }}</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" 
                                            {{ old('leave_type_id', $leavePeriod->leave_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ({{ $type->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @if($override && !$isSuperAdmin)
                                    <input type="hidden" name="leave_type_id" value="{{ $leavePeriod->leave_type_id }}">
                                @endif
                                <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="start_date" :value="__('Date de début')" />
                                @if(!$override || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="start_date" 
                                    name="start_date" 
                                    type="date" 
                                    class="form-control mt-1 @error('start_date') is-invalid @enderror" 
                                    value="{{ old('start_date', isset($override->start_date) && !$isSuperAdmin ? $override->start_date->format('Y-m-d') : ($leavePeriod->start_date ? $leavePeriod->start_date->format('Y-m-d') : '')) }}"
                                    {{ ($isSuperAdmin || !$override) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                @if($override && !$isSuperAdmin && $leavePeriod->start_date)
                                    <small class="text-muted">Valeur globale : {{ $leavePeriod->start_date->format('d/m/Y') }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="end_date" :value="__('Date de fin')" />
                                @if(!$override || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <input 
                                    id="end_date" 
                                    name="end_date" 
                                    type="date" 
                                    class="form-control mt-1 @error('end_date') is-invalid @enderror" 
                                    value="{{ old('end_date', isset($override->end_date) && !$isSuperAdmin ? $override->end_date->format('Y-m-d') : ($leavePeriod->end_date ? $leavePeriod->end_date->format('Y-m-d') : '')) }}"
                                    {{ ($isSuperAdmin || !$override) ? 'required' : '' }} />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                @if($override && !$isSuperAdmin && $leavePeriod->end_date)
                                    <small class="text-muted">Valeur globale : {{ $leavePeriod->end_date->format('d/m/Y') }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="submission_deadline" :value="__('Date limite de pose')" />
                                <input 
                                    id="submission_deadline" 
                                    name="submission_deadline" 
                                    type="date" 
                                    class="form-control mt-1 @error('submission_deadline') is-invalid @enderror" 
                                    value="{{ old('submission_deadline', isset($override->submission_deadline) && !$isSuperAdmin ? $override->submission_deadline->format('Y-m-d') : ($leavePeriod->submission_deadline ? $leavePeriod->submission_deadline->format('Y-m-d') : '')) }}" />
                                <x-input-error :messages="$errors->get('submission_deadline')" class="mt-2" />
                                @if($override && !$isSuperAdmin && $leavePeriod->submission_deadline)
                                    <small class="text-muted">Valeur globale : {{ $leavePeriod->submission_deadline->format('d/m/Y') }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-lg-4">
                                <x-input-label for="status" :value="__('Statut')" />
                                @if(!$override || $isSuperAdmin)
                                    <span class="text-danger">*</span>
                                @endif
                                <select id="status" name="status" class="form-select mt-1" {{ ($override && !$isSuperAdmin) ? '' : 'required' }}>
                                    <option value="preparing" {{ old('status', $override->status ?? $leavePeriod->status) == 'preparing' ? 'selected' : '' }}>{{ __('Préparation') }}</option>
                                    <option value="open" {{ old('status', $override->status ?? $leavePeriod->status) == 'open' ? 'selected' : '' }}>{{ __('Ouvert') }}</option>
                                    <option value="closed" {{ old('status', $override->status ?? $leavePeriod->status) == 'closed' ? 'selected' : '' }}>{{ __('Fermé') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                @if($override && !$isSuperAdmin && $leavePeriod->status)
                                    <small class="text-muted">Valeur globale : {{ $leavePeriod->status }}</small>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="is_default" :value="__('Période par défaut')" />
                                <div class="mt-1">
                                    @if($override && !$isSuperAdmin)
                                        <input type="hidden" name="is_default" value="0">
                                        <input type="checkbox" id="is_default" name="is_default" value="1" 
                                            {{ old('is_default', $override->is_default ?? false) ? 'checked' : '' }} class="form-check-input">
                                        <label for="is_default" class="form-check-label ms-2">{{ __('Définir comme période par défaut') }}</label>
                                        @if($leavePeriod->is_default)
                                            <small class="d-block text-muted">Valeur globale : Par défaut</small>
                                        @endif
                                    @else
                                        <input type="hidden" name="is_default" value="0">
                                        <input type="checkbox" id="is_default" name="is_default" value="1" 
                                            {{ old('is_default', $leavePeriod->is_default) ? 'checked' : '' }} class="form-check-input">
                                        <label for="is_default" class="form-check-label ms-2">{{ __('Définir comme période par défaut') }}</label>
                                    @endif
                                </div>
                                <x-input-error :messages="$errors->get('is_default')" class="mt-2" />
                            </div>

                            <div class="col-lg-4">
                                <x-input-label for="is_active" :value="__('Actif')" />
                                <div class="mt-1">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                        {{ old('is_active', $override->is_active ?? $leavePeriod->is_active) ? 'checked' : '' }} class="form-check-input">
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
                                    <input type="checkbox" id="allow_rollover" name="allow_rollover" value="1" 
                                        {{ old('allow_rollover', $override->allow_rollover ?? $leavePeriod->allow_rollover) ? 'checked' : '' }} 
                                        class="form-check-input" onchange="toggleRolloverFields()">
                                    <label for="allow_rollover" class="form-check-label ms-2">{{ __('Autoriser le report des jours non pris') }}</label>
                                </div>
                                <x-input-error :messages="$errors->get('allow_rollover')" class="mt-2" />
                            </div>

                            <div class="col-lg-6" id="rollover_fields" style="{{ old('allow_rollover', $override->allow_rollover ?? $leavePeriod->allow_rollover) ? '' : 'display: none;' }}">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <x-input-label for="max_rollover_days" :value="__('Nombre max de jours reportés')" />
                                        <input 
                                            id="max_rollover_days" 
                                            name="max_rollover_days" 
                                            type="number" 
                                            class="form-control mt-1 @error('max_rollover_days') is-invalid @enderror" 
                                            value="{{ old('max_rollover_days', $override->max_rollover_days ?? $leavePeriod->max_rollover_days) }}"
                                            min="0" />
                                        <x-input-error :messages="$errors->get('max_rollover_days')" class="mt-2" />
                                        <small class="text-muted">{{ __('Laissez vide pour illimité') }}</small>
                                        @if($override && !$isSuperAdmin && $leavePeriod->max_rollover_days !== null)
                                            <small class="d-block text-muted">Valeur globale : {{ $leavePeriod->max_rollover_days }}</small>
                                        @endif
                                    </div>

                                    <div class="col-6">
                                        <x-input-label for="rollover_expiry_date" :value="__("Date d'expiration du report")" />
                                        <input 
                                            id="rollover_expiry_date" 
                                            name="rollover_expiry_date" 
                                            type="date" 
                                            class="form-control mt-1 @error('rollover_expiry_date') is-invalid @enderror" 
                                            value="{{ old('rollover_expiry_date', isset($override->rollover_expiry_date) && !$isSuperAdmin ? $override->rollover_expiry_date->format('Y-m-d') : ($leavePeriod->rollover_expiry_date ? $leavePeriod->rollover_expiry_date->format('Y-m-d') : '')) }}" />
                                        <x-input-error :messages="$errors->get('rollover_expiry_date')" class="mt-2" />
                                        @if($override && !$isSuperAdmin && $leavePeriod->rollover_expiry_date)
                                            <small class="text-muted">Valeur globale : {{ $leavePeriod->rollover_expiry_date->format('d/m/Y') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section Siège - Uniquement pour le Super Admin --}}
                        @if($isSuperAdmin)
                            <div class="row g-3 mb-4">
                                <div class="col-lg-6">
                                    <x-input-label for="site_id" :value="__('Siège')" />
                                    <select id="site_id" name="site_id" class="form-select mt-1">
                                        <option value="">{{ __('Global (tous les sièges)') }}</option>
                                        @foreach($sites as $site)
                                            <option value="{{ $site->ID }}" 
                                                {{ old('site_id', $leavePeriod->site_id) == $site->ID ? 'selected' : '' }}>
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
                                        <input type="checkbox" id="is_customizable" name="is_customizable" value="1" 
                                            {{ old('is_customizable', $leavePeriod->is_customizable) ? 'checked' : '' }} class="form-check-input">
                                        <label for="is_customizable" class="form-check-label ms-2">{{ __('Les administrateurs de siège peuvent personnaliser cette période') }}</label>
                                    </div>
                                    <x-input-error :messages="$errors->get('is_customizable')" class="mt-2" />
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <x-primary-button class="btn btn-primary">
                                @if($override && !$isSuperAdmin)
                                    {{ __('Personnaliser') }}
                                @else
                                    {{ __('Mettre à jour') }}
                                @endif
                            </x-primary-button>
                            <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            {{ __('Cette période globale n\'est pas personnalisable. Vous ne pouvez pas la modifier.') }}
                        </div>
                        <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                            {{ __('Retour à la liste') }}
                        </a>
                    @endif
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