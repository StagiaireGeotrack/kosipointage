{{-- resources/views/conges/leave_types/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du Type de Congé') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leave-types.edit', $leaveType->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
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
                    $isGlobal = $leaveType->isGlobal();
                    $hasOverride = isset($resolved) && isset($resolved->is_overridden) && $resolved->is_overridden;
                @endphp

                @if($isGlobal && !$hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Ce type est global et s\'applique à tous les sièges.') }}
                        @if($leaveType->is_customizable)
                            <span class="badge bg-primary ms-2">Personnalisable</span>
                        @endif
                    </div>
                @endif

                @if($hasOverride)
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Ce type est personnalisé pour votre siège.') }}
                    </div>
                @endif

                @if($leaveType->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce type a été supprimé le ') . $leaveType->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.leave-types.restore', $leaveType->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                <dl class="row">
                    <dt class="col-sm-3">{{ __('Code') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-secondary">{{ $leaveType->code }}</span>
                        @if($isGlobal)
                            <span class="badge bg-info ms-2">Global</span>
                        @endif
                        @if($hasOverride)
                            <span class="badge bg-warning text-dark ms-2">Personnalisé</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Nom') }}</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $leaveType->name }}</strong>
                        @if($hasOverride && isset($resolved->name) && $resolved->name != $leaveType->name)
                            <br><small class="text-muted">Valeur globale : {{ $leaveType->name }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Couleur') }}</dt>
                    <dd class="col-sm-9">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background-color: {{ $leaveType->color ?? '#10B981' }}; width: 30px; height: 20px;">&nbsp;</span>
                            <span>{{ $leaveType->color ?? '#10B981' }}</span>
                        </div>
                        @if($hasOverride && isset($resolved->color) && $resolved->color != $leaveType->color)
                            <br><small class="text-muted">Valeur globale : {{ $leaveType->color }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Unité') }}</dt>
                    <dd class="col-sm-9">
                        @php
                            $unitLabels = [
                                'days' => 'Jours',
                                'half_days' => 'Demi-journées',
                                'hours' => 'Heures'
                            ];
                        @endphp
                        <span class="badge bg-secondary">{{ $unitLabels[$leaveType->unit] ?? $leaveType->unit }}</span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">
                        @if($isGlobal)
                            <span class="text-muted">Tous les sièges</span>
                        @else
                            {{ $leaveType->site->Nom ?? 'N/A' }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Justificatif requis') }}</dt>
                    <dd class="col-sm-9">
                        @php
                            $attachmentLabels = [
                                'never' => 'Jamais',
                                'always' => 'Toujours',
                                'after_duration' => 'Après ' . ($leaveType->requires_attachment_after ?? 'X') . ' jours'
                            ];
                        @endphp
                        <span class="badge bg-secondary">{{ $attachmentLabels[$leaveType->requires_attachment] ?? $leaveType->requires_attachment }}</span>
                        @if($hasOverride && isset($resolved->requires_attachment) && $resolved->requires_attachment != $leaveType->requires_attachment)
                            <br><small class="text-muted">Valeur globale : {{ $attachmentLabels[$leaveType->requires_attachment] ?? $leaveType->requires_attachment }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Déduire du solde') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveType->deducts_balance ? 'bg-success' : 'bg-danger' }}">
                            {{ $leaveType->deducts_balance ? 'Oui' : 'Non' }}
                        </span>
                        @if($hasOverride && isset($resolved->deducts_balance) && $resolved->deducts_balance != $leaveType->deducts_balance)
                            <br><small class="text-muted">Valeur globale : {{ $leaveType->deducts_balance ? 'Oui' : 'Non' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Solde négatif autorisé') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveType->allow_negative_balance ? 'bg-warning' : 'bg-secondary' }}">
                            {{ $leaveType->allow_negative_balance ? 'Oui' : 'Non' }}
                        </span>
                        @if($leaveType->max_negative_limit)
                            <span class="ms-2">(Limite: {{ $leaveType->max_negative_limit }})</span>
                        @endif
                        @if($hasOverride && isset($resolved->allow_negative_balance) && $resolved->allow_negative_balance != $leaveType->allow_negative_balance)
                            <br><small class="text-muted">Valeur globale : {{ $leaveType->allow_negative_balance ? 'Oui' : 'Non' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Personnalisable') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveType->is_customizable ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $leaveType->is_customizable ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveType->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $leaveType->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                    <dd class="col-sm-9">{{ $leaveType->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">{{ __('Dernière mise à jour') }}</dt>
                    <dd class="col-sm-9">{{ $leaveType->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($leaveType->deleted_at)
                        <dt class="col-sm-3">{{ __('Supprimé le') }}</dt>
                        <dd class="col-sm-9">{{ $leaveType->deleted_at->format('d/m/Y H:i') }}</dd>
                    @endif

                    @if($hasOverride && isset($resolved->override_id))
                        <dt class="col-sm-3">{{ __('Override ID') }}</dt>
                        <dd class="col-sm-9">{{ $resolved->override_id }}</dd>
                    @endif
                </dl>

                @if(!$leaveType->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.leave-types.edit', $leaveType->id) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if(!$isGlobal || $isSuperAdmin)
                                <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Voulez-vous vraiment supprimer ce type de congé ?')) { 
                                        document.getElementById('delete-form').submit(); 
                                    }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.leave-types.destroy', $leaveType->id) }}" method="POST" style="display: none;">
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