{{-- resources/views/departments/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-building"></i> {{ __('Détails du Service') }} : {{ $department->name }}
            </h2>
            <div>
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                </a>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($department->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce service a été supprimé le ') . $department->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('departments.restore', $department->id) }}" method="POST" class="d-inline">
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
                            <dt class="col-sm-4 fw-bold">{{ __('Nom') }}</dt>
                            <dd class="col-sm-8"><strong>{{ $department->name }}</strong></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Code') }}</dt>
                            <dd class="col-sm-8"><span class="badge bg-secondary">{{ $department->code ?? '-' }}</span></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Siège') }}</dt>
                            <dd class="col-sm-8">{{ $department->site->Nom ?? 'Non défini' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Responsable') }}</dt>
                            <dd class="col-sm-8">{{ $department->managerEmployee->Nom ?? 'Aucun responsable' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Nombre d\'employés') }}</dt>
                            <dd class="col-sm-8">
                                @php
                                    // Utiliser une requête directe si la relation n'est pas chargée
                                    $employeeCount = \App\Models\Employe::where('department_id', $department->id)->count();
                                @endphp
                                <span class="badge bg-info">{{ $employeeCount }}</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Créé le') }}</dt>
                            <dd class="col-sm-8">{{ $department->created_at ? $department->created_at->format('d/m/Y H:i') : '-' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Modifié le') }}</dt>
                            <dd class="col-sm-8">{{ $department->updated_at ? $department->updated_at->format('d/m/Y H:i') : '-' }}</dd>

                            @if($department->deleted_at)
                                <dt class="col-sm-4 fw-bold">{{ __('Supprimé le') }}</dt>
                                <dd class="col-sm-8">{{ $department->deleted_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-people"></i> {{ __('Employés de ce service') }}</h6>
                                @php
                                    $employees = \App\Models\Employe::where('department_id', $department->id)->take(10)->get();
                                    $totalEmployees = \App\Models\Employe::where('department_id', $department->id)->count();
                                @endphp
                                @if($employees->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($employees as $employee)
                                            <li><i class="bi bi-person"></i> {{ $employee->Nom }}</li>
                                        @endforeach
                                        @if($totalEmployees > 10)
                                            <li class="text-muted"><i class="bi bi-plus-circle"></i> {{ $totalEmployees - 10 }} {{ __('autres') }}...</li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted">{{ __('Aucun employé dans ce service') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$department->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @php
                                $employeeCount = \App\Models\Employe::where('department_id', $department->id)->count();
                            @endphp
                            @if($employeeCount == 0)
                                <button type="button" class="btn btn-danger" 
                                        onclick="if(confirm('Voulez-vous vraiment supprimer ce service ?')) {
                                            document.getElementById('delete-form').submit();
                                        }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('departments.destroy', $department) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <span class="text-muted" title="Impossible de supprimer : {{ $employeeCount }} employé(s) sont dans ce service">
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