{{-- resources/views/hierarchy_levels/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-layers"></i> {{ __('Détails du Niveau') }} : {{ $hierarchyLevel->code }}
            </h2>
            <div>
                <a href="{{ route('admin.hierarchy-levels.edit', $hierarchyLevel) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.hierarchy-levels.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($hierarchyLevel->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce niveau a été supprimé le ') . $hierarchyLevel->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.hierarchy-levels.restore', $hierarchyLevel->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">{{ __('Code KOSI') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-primary fs-4">{{ $hierarchyLevel->code }}</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Nom') }}</dt>
                            <dd class="col-sm-8"><strong>{{ $hierarchyLevel->name }}</strong></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Rang') }}</dt>
                            <dd class="col-sm-8"><span class="badge bg-secondary">{{ $hierarchyLevel->rank }}</span></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Rôle') }}</dt>
                            <dd class="col-sm-8">
                                @if($hierarchyLevel->is_managerial)
                                    <span class="badge bg-success"><i class="bi bi-person-badge"></i> Managerial</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-person"></i> Non managerial</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Employés') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-info">{{ $hierarchyLevel->employees->count() }}</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Créé le') }}</dt>
                            <dd class="col-sm-8">{{ $hierarchyLevel->created_at ? $hierarchyLevel->created_at->format('d/m/Y H:i') : '-' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Modifié le') }}</dt>
                            <dd class="col-sm-8">{{ $hierarchyLevel->updated_at ? $hierarchyLevel->updated_at->format('d/m/Y H:i') : '-' }}</dd>

                            @if($hierarchyLevel->deleted_at)
                                <dt class="col-sm-4 fw-bold">{{ __('Supprimé le') }}</dt>
                                <dd class="col-sm-8">{{ $hierarchyLevel->deleted_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-people"></i> {{ __('Employés à ce niveau') }}</h6>
                                @php
                                    $employees = $hierarchyLevel->employees()->take(10)->get();
                                @endphp
                                @if($employees->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($employees as $employee)
                                            <li><i class="bi bi-person"></i> {{ $employee->Nom }}</li>
                                        @endforeach
                                        @if($hierarchyLevel->employees->count() > 10)
                                            <li class="text-muted"><i class="bi bi-plus-circle"></i> {{ $hierarchyLevel->employees->count() - 10 }} {{ __('autres') }}...</li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted">{{ __('Aucun employé à ce niveau') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$hierarchyLevel->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.hierarchy-levels.edit', $hierarchyLevel) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if($hierarchyLevel->employees->count() == 0)
                                <button type="button" class="btn btn-danger" 
                                        onclick="if(confirm('Voulez-vous vraiment supprimer ce niveau ?')) {
                                            document.getElementById('delete-form').submit();
                                        }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.hierarchy-levels.destroy', $hierarchyLevel) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <span class="text-muted" title="Impossible de supprimer : {{ $hierarchyLevel->employees->count() }} employé(s) sont à ce niveau">
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="bi bi-lock"></i> {{ __('Supprimer') }}
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>