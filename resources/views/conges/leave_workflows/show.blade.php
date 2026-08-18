{{-- resources/views/conges/leave_workflows/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du Workflow de Validation') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leave-workflows.edit', $leaveWorkflow->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.leave-workflows.index') }}" class="btn btn-secondary">
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
                    $isGlobal = $leaveWorkflow->isGlobal();
                    $hasOverride = isset($resolved) && isset($resolved->is_overridden) && $resolved->is_overridden;
                    $steps = $hasOverride ? ($resolved->steps ?? []) : ($leaveWorkflow->steps ?? []);
                @endphp

                @if($isGlobal && !$hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Ce workflow est global et s\'applique à tous les sièges.') }}
                        @if($leaveWorkflow->is_customizable)
                            <span class="badge bg-primary ms-2">Personnalisable</span>
                        @endif
                    </div>
                @endif

                @if($hasOverride)
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Ce workflow est personnalisé pour votre siège.') }}
                    </div>
                @endif

                @if($leaveWorkflow->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce workflow a été supprimé le ') . $leaveWorkflow->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.leave-workflows.restore', $leaveWorkflow->id) }}" method="POST" class="d-inline">
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
                        <strong>{{ $leaveWorkflow->name }}</strong>
                        @if($isGlobal)
                            <span class="badge bg-info ms-2">Global</span>
                        @endif
                        @if($hasOverride)
                            <span class="badge bg-warning text-dark ms-2">Personnalisé</span>
                        @endif
                        @if($leaveWorkflow->is_default)
                            <span class="badge bg-success ms-2">Par défaut</span>
                        @endif
                        @if($hasOverride && isset($resolved->name) && $resolved->name != $leaveWorkflow->name)
                            <br><small class="text-muted">Valeur globale : {{ $leaveWorkflow->name }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">
                        @if($isGlobal)
                            <span class="text-muted">Tous les sièges</span>
                        @else
                            {{ $leaveWorkflow->site->Nom ?? 'N/A' }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Description') }}</dt>
                    <dd class="col-sm-9">
                        {{ $leaveWorkflow->description ?? '—' }}
                        @if($hasOverride && isset($resolved->description) && $resolved->description != $leaveWorkflow->description)
                            <br><small class="text-muted">Valeur globale : {{ $leaveWorkflow->description ?? '—' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Nombre d\'étapes') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-primary">{{ count($steps) }}</span>
                        @if($hasOverride && isset($resolved->steps) && count($resolved->steps) != count($leaveWorkflow->steps ?? []))
                            <br><small class="text-muted">Valeur globale : {{ count($leaveWorkflow->steps ?? []) }} étape(s)</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Chemin de validation') }}</dt>
                    <dd class="col-sm-9">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @foreach($steps as $step)
                                <span class="badge bg-secondary p-2">
                                    <i class="bi bi-person"></i>
                                    {{ $step['label'] ?? $step['role'] ?? 'Étape' }}
                                    @if(isset($step['description']) && $step['description'])
                                        <span class="text-muted small d-block">{{ $step['description'] }}</span>
                                    @endif
                                </span>
                                @if(!$loop->last)
                                    <span class="text-muted">→</span>
                                @endif
                            @endforeach
                            @if(!empty($steps))
                                <span class="badge bg-success p-2">
                                    <i class="bi bi-check-circle"></i> Validé
                                </span>
                            @endif
                        </div>
                        @if($hasOverride && isset($resolved->steps) && json_encode($resolved->steps) != json_encode($leaveWorkflow->steps))
                            <div class="mt-2">
                                <small class="text-muted">Valeur globale : 
                                    @php
                                        $globalSteps = $leaveWorkflow->steps ?? [];
                                        $globalLabels = [];
                                        foreach ($globalSteps as $s) {
                                            $globalLabels[] = $s['label'] ?? $s['role'] ?? 'Étape';
                                        }
                                        $globalLabels[] = 'Validé';
                                    @endphp
                                    {{ implode(' → ', $globalLabels) }}
                                </small>
                            </div>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Détail des étapes') }}</dt>
                    <dd class="col-sm-9">
                        @if(!empty($steps))
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Ordre') }}</th>
                                        <th>{{ __('Rôle') }}</th>
                                        <th>{{ __('Label') }}</th>
                                        <th>{{ __('Description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($steps as $step)
                                        <tr>
                                            <td>{{ $step['order'] ?? '—' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $step['role'] ?? '—' }}
                                                </span>
                                            </td>
                                            <td>{{ $step['label'] ?? '—' }}</td>
                                            <td>{{ $step['description'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span class="text-muted">Aucune étape définie</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Personnalisable') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveWorkflow->is_customizable ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $leaveWorkflow->is_customizable ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $leaveWorkflow->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $leaveWorkflow->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                    <dd class="col-sm-9">{{ $leaveWorkflow->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">{{ __('Dernière mise à jour') }}</dt>
                    <dd class="col-sm-9">{{ $leaveWorkflow->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($leaveWorkflow->deleted_at)
                        <dt class="col-sm-3">{{ __('Supprimé le') }}</dt>
                        <dd class="col-sm-9">{{ $leaveWorkflow->deleted_at->format('d/m/Y H:i') }}</dd>
                    @endif

                    @if($hasOverride && isset($resolved->override_id))
                        <dt class="col-sm-3">{{ __('Override ID') }}</dt>
                        <dd class="col-sm-9">{{ $resolved->override_id }}</dd>
                    @endif
                </dl>

                @if(!$leaveWorkflow->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.leave-workflows.edit', $leaveWorkflow->id) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if(!$isGlobal || $isSuperAdmin)
                                <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Voulez-vous vraiment supprimer ce workflow ?')) { 
                                        document.getElementById('delete-form').submit(); 
                                    }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.leave-workflows.destroy', $leaveWorkflow->id) }}" method="POST" style="display: none;">
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