{{-- resources/views/admin/sellers/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Détails du Vendeur</h2>
                <a href="{{ route('admin.sellers.edit', $seller->ID) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations Générales</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">ID :</dt>
                        <dd class="col-sm-8">{{ $seller->ID }}</dd>

                        <dt class="col-sm-4">Email :</dt>
                        <dd class="col-sm-8">{{ $seller->Identifiant_email }}</dd>

                        <dt class="col-sm-4">Statut :</dt>
                        <dd class="col-sm-8">
                            @if($seller->Actived)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Type :</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-info">Vendeur (Super Admin Limité)</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        Sièges Accessibles 
                        <span class="badge bg-light text-dark">{{ $seller->sellerSieges->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($seller->sellerSieges->isEmpty())
                        <p class="text-muted mb-0">Aucun siège assigné</p>
                    @else
                        <ul class="list-group">
                            @foreach($seller->sellerSieges as $siege)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{ $siege->Nom }}</div>
                                        @if($siege->Nom_Lieu_Ville)
                                            <small class="text-muted">{{ $siege->Nom_Lieu_Ville }}</small>
                                        @endif
                                    </div>
                                    @if($siege->Actived)
                                        <span class="badge bg-success rounded-pill">Actif</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Inactif</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
                <div>
                    <a href="{{ route('admin.sellers.edit', $seller->ID) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <button type="button" 
                            class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer le vendeur 
                <strong>{{ $seller->Identifiant_email }}</strong> ?
                <br><br>
                Cette action supprimera également tous ses accès aux sièges et est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <form action="{{ route('admin.sellers.destroy', $seller->ID) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection