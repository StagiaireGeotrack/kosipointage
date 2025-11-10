{{-- resources/views/admin/sellers/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Gestion des Vendeurs</h2>
                <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouveau Revendeur
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($sellers->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3 text-muted">Aucun revendeur enregistré</p>
                    <a href="{{ route('admin.sellers.create') }}" class="btn btn-primary mt-2">
                        Créer le premier revendeur
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Nombre de Sièges</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->ID }}</td>
                                    <td>{{ $seller->Identifiant_email }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $seller->sellerSieges->count() }} siège(s)
                                        </span>
                                    </td>
                                    <td>
                                        @if($seller->Actived)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.sellers.show', $seller->ID) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sellers.edit', $seller->ID) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.sellers.toggle-active', $seller->ID) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $seller->Actived ? 'btn-secondary' : 'btn-success' }}" 
                                                        title="{{ $seller->Actived ? 'Désactiver' : 'Activer' }}">
                                                    <i class="bi bi-{{ $seller->Actived ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $seller->ID }}"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal de confirmation de suppression -->
                                <div class="modal fade" id="deleteModal{{ $seller->ID }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer le revendeur 
                                                <strong>{{ $seller->Identifiant_email }}</strong> ?
                                                <br><br>
                                                Cette action est irréversible.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Annuler
                                                </button>
                                                <form action="{{ route('admin.sellers.destroy', $seller->ID) }}" 
                                                      method="POST" 
                                                      class="d-inline">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection