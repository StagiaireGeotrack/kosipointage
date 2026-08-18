{{-- resources/views/conges/leave_policies/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails de la Politique de Congé') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leave-policies.edit', $leavePolicy->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.leave-policies.index') }}" class="btn btn-secondary">
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $leavePolicy->isGlobal();
                    $hasOverride = isset($resolved) && isset($resolved->is_overridden) && $resolved->is_overridden;
                @endphp

                @if($isGlobal && !$hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Cette politique est globale et s\'applique à tous les sièges.') }}
                        @if($leavePolicy->is_customizable)
                            <span class="badge bg-primary ms-2">Personnalisable</span>
                        @endif
                    </div>
                @endif

                @if($hasOverride)
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Cette politique est personnalisée pour votre siège.') }}
                    </div>
                @endif

                @if($leavePolicy->trashed())
                    <div class="alert alert-danger">
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

                <dl class="row">
                    <dt class="col-sm-3">{{ __('Nom') }}</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $leavePolicy->name }}</strong>
                        @if($isGlobal)
                            <span class="badge bg-info ms-2">Global</span>
                        @endif
                        @if($hasOverride)
                            <span class="badge bg-warning text-dark ms-2">Personnalisé</span>
                        @endif
                        @if($leavePolicy->is_default)
                            <span class="badge bg-success ms-2">Par défaut</span>
                        @endif
                        @if($hasOverride && isset($resolved->name) && $resolved->name != $leavePolicy->name)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->name }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">
                        @if($isGlobal)
                            <span class="text-muted">Tous les sièges</span>
                        @else
                            {{ $leavePolicy->site->Nom ?? 'N/A' }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Méthode de calcul') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $leavePolicy->getCalculationMethodLabel() }}</span>
                        @if($hasOverride && isset($resolved->calculation_method) && $resolved->calculation_method != $leavePolicy->calculation_method)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->getCalculationMethodLabel() }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Jours de week-end') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-info">{{ $leavePolicy->getWeekendDaysLabel() }}</span>
                        @if($hasOverride && isset($resolved->weekend_days) && $resolved->weekend_days != $leavePolicy->weekend_days)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->getWeekendDaysLabel() }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Gestion des jours fériés') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $leavePolicy->getHolidayHandlingLabel() }}</span>
                        @if($hasOverride && isset($resolved->holiday_handling) && $resolved->holiday_handling != $leavePolicy->holiday_handling)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->getHolidayHandlingLabel() }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Règle d\'arrondi') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $leavePolicy->getRoundingRuleLabel() }}</span>
                        @if($hasOverride && isset($resolved->rounding_rule) && $resolved->rounding_rule != $leavePolicy->rounding_rule)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->getRoundingRuleLabel() }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Planning de référence') }}</dt>
                    <dd class="col-sm-9">
                        @if($leavePolicy->reference_schedule_id)
                            <span class="badge bg-primary">ID: {{ $leavePolicy->reference_schedule_id }}</span>
                        @else
                            <span class="text-muted">Non défini</span>
                        @endif
                        @if($hasOverride && isset($resolved->reference_schedule_id) && $resolved->reference_schedule_id != $leavePolicy->reference_schedule_id)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->reference_schedule_id ?? 'Non défini' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Exclure les jours fériés') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePolicy->exclude_holidays ? 'bg-success' : 'bg-danger' }}">
                            {{ $leavePolicy->exclude_holidays ? 'Oui' : 'Non' }}
                        </span>
                        @if($hasOverride && isset($resolved->exclude_holidays) && $resolved->exclude_holidays != $leavePolicy->exclude_holidays)
                            <br><small class="text-muted">Valeur globale : {{ $leavePolicy->exclude_holidays ? 'Oui' : 'Non' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Personnalisable') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePolicy->is_customizable ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $leavePolicy->is_customizable ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePolicy->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $leavePolicy->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                    <dd class="col-sm-9">{{ $leavePolicy->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">{{ __('Dernière mise à jour') }}</dt>
                    <dd class="col-sm-9">{{ $leavePolicy->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($leavePolicy->deleted_at)
                        <dt class="col-sm-3">{{ __('Supprimé le') }}</dt>
                        <dd class="col-sm-9">{{ $leavePolicy->deleted_at->format('d/m/Y H:i') }}</dd>
                    @endif

                    @if($hasOverride && isset($resolved->override_id))
                        <dt class="col-sm-3">{{ __('Override ID') }}</dt>
                        <dd class="col-sm-9">{{ $resolved->override_id }}</dd>
                    @endif
                </dl>

                @if(!$leavePolicy->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.leave-policies.edit', $leavePolicy->id) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if(!$isGlobal || $isSuperAdmin)
                                <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Voulez-vous vraiment supprimer cette politique ?')) { 
                                        document.getElementById('delete-form').submit(); 
                                    }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.leave-policies.destroy', $leavePolicy->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>