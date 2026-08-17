{{-- resources/views/conges/leave_periods/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails de la Période de Congé') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leave-periods.edit', $leavePeriod->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($leavePeriod->isGlobal() && !$leavePeriod->is_customizable)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Cette période est globale et non personnalisable.') }}
                    </div>
                @endif

                @if($leavePeriod->isGlobal() && $leavePeriod->is_customizable)
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        {{ __('Cette période est globale et personnalisable par les administrateurs de siège.') }}
                    </div>
                @endif

                @if(isset($resolved) && ($resolved->is_overridden ?? false))
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Cette période est personnalisée pour votre siège.') }}
                    </div>
                @endif

                @if($leavePeriod->deleted_at)
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Cette période a été supprimée le ') . $leavePeriod->deleted_at->format('d/m/Y H:i') }}
                    </div>
                @endif

                <dl class="row">
                    <dt class="col-sm-3">{{ __('Nom') }}</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $leavePeriod->name }}</strong>
                        @if($leavePeriod->isGlobal())
                            <span class="badge bg-info ms-2">Global</span>
                        @endif
                        @if(isset($resolved) && ($resolved->is_overridden ?? false))
                            <span class="badge bg-warning text-dark ms-2">Personnalisé</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Type de congé') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->leaveType->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">
                        @if($leavePeriod->isGlobal())
                            <span class="text-muted">Global (tous les sièges)</span>
                        @else
                            {{ $leavePeriod->site->Nom ?? 'N/A' }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Date de début') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->start_date->format('d/m/Y') }}</dd>

                    <dt class="col-sm-3">{{ __('Date de fin') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->end_date->format('d/m/Y') }}</dd>

                    <dt class="col-sm-3">{{ __('Date limite de pose') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->submission_deadline ? $leavePeriod->submission_deadline->format('d/m/Y') : '—' }}</dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        @php
                            $statusBadge = match($leavePeriod->status) {
                                'preparing' => 'bg-secondary',
                                'open' => 'bg-success',
                                'closed' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            $statusLabel = match($leavePeriod->status) {
                                'preparing' => 'Préparation',
                                'open' => 'Ouvert',
                                'closed' => 'Fermé',
                                default => $leavePeriod->status
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Report des jours') }}</dt>
                    <dd class="col-sm-9">
                        @if($leavePeriod->allow_rollover)
                            <span class="badge bg-success">Oui</span>
                            @if($leavePeriod->max_rollover_days)
                                <span class="ms-2">Max : {{ $leavePeriod->max_rollover_days }} jours</span>
                            @endif
                            @if($leavePeriod->rollover_expiry_date)
                                <span class="ms-2">Expire le : {{ $leavePeriod->rollover_expiry_date->format('d/m/Y') }}</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Période par défaut') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePeriod->is_default ? 'bg-success' : 'bg-secondary' }}">
                            {{ $leavePeriod->is_default ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Personnalisable') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePeriod->is_customizable ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $leavePeriod->is_customizable ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Actif') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leavePeriod->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $leavePeriod->is_active ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">{{ __('Dernière mise à jour') }}</dt>
                    <dd class="col-sm-9">{{ $leavePeriod->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($leavePeriod->deleted_at)
                        <dt class="col-sm-3">{{ __('Supprimé le') }}</dt>
                        <dd class="col-sm-9">{{ $leavePeriod->deleted_at->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>

                @if($leavePeriod->deleted_at)
                    <div class="mt-3">
                        <form action="{{ route('admin.leave-periods.restore', $leavePeriod->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>