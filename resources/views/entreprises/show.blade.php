@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Détails de l\'entreprise') }}</h5>
                    <div>
                        <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                        </a>
                        <a href="{{ route('entreprises.edit', $entreprise->ID) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($entreprise->Logo)
                                <img src="{{ route('entreprises.logo', $entreprise->ID) }}" alt="{{ $entreprise->Nom }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 200px; width: 100%;">
                                    <i class="bi bi-building" style="font-size: 5rem; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <h3>{{ $entreprise->Nom }}</h3>
                            
                            <div class="mb-2">
                                <span class="badge bg-{{ $entreprise->Actived ? 'success' : 'danger' }}">
                                    {{ $entreprise->Actived ? __('Actif') : __('Inactif') }}
                                </span>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">{{ __('Informations générales') }}</h6>
                                    
                                    <dl class="row mb-0">
                                        <dt class="col-sm-3">{{ __('Siège') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->siege->Nom }}</dd>
                                        
                                        <dt class="col-sm-3">{{ __('Email') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->Email ?: 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">{{ __('Téléphone') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->Telephone ?: 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">{{ __('Adresse') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->Adresse ?: 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->created_at ? $entreprise->created_at->format('d/m/Y H:i') : 'N/A' }}</dd>
                                        
                                        <dt class="col-sm-3">{{ __('Modifié le') }}</dt>
                                        <dd class="col-sm-9">{{ $entreprise->updated_at ? $entreprise->updated_at->format('d/m/Y H:i') : 'N/A' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>{{ __('Employés associés') }}</h6>
                            
                            @if($entreprise->employes && $entreprise->employes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Nom') }}</th>
                                                <th>{{ __('Badge') }}</th>
                                                <th>{{ __('Statut') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entreprise->employes as $employe)
                                                <tr>
                                                    <td>{{ $employe->ID }}</td>
                                                    <td>{{ $employe->Nom }}</td>
                                                    <td>{{ $employe->BadgeID }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $employe->Actived ? 'success' : 'danger' }}">
                                                            {{ $employe->Actived ? __('Actif') : __('Inactif') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('employes.show', $employe->ID) }}" class="btn btn-info btn-sm">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    {{ __('Aucun employé associé à cette entreprise.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <form action="{{ route('entreprises.destroy', $entreprise->ID) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette entreprise?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> {{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection