@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-people-fill me-2 text-primary"></i>
                {{ __('Responsables de service') }}
            </h1>
            <p class="text-muted mb-0">
                {{ __('Gérez les comptes responsables et leurs services affectés.') }}
            </p>
        </div>
        <a href="{{ route('supervisors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            {{ __('Créer un responsable') }}
        </a>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($supervisors->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                    <p class="mb-0">{{ __('Aucun responsable de service pour le moment.') }}</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Services supervisés') }}</th>
                            <th class="text-center">{{ __('Statut') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supervisors as $sup)
                        <tr>
                            <td>
                                <i class="bi bi-person-circle text-secondary me-2"></i>
                                {{ $sup->Identifiant_email }}
                            </td>
                            <td>
                                @if($sup->services->isEmpty())
                                    <span class="text-muted fst-italic">{{ __('Aucun service') }}</span>
                                @else
                                    @foreach($sup->services as $srv)
                                        <span class="badge bg-info text-dark me-1 mb-1">
                                            {{ $srv->name }}
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @if($sup->Actived)
                                    <span class="badge bg-success">{{ __('Actif') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactif') }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('supervisors.edit', $sup->ID) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="{{ __('Modifier') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('supervisors.toggle', $sup->ID) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-warning"
                                            title="{{ $sup->Actived ? __('Désactiver') : __('Activer') }}">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>

                                <form action="{{ route('supervisors.destroy', $sup->ID) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ __('Confirmer la suppression ?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="{{ __('Supprimer') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection