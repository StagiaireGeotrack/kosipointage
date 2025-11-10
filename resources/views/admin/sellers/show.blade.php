{{-- resources/views/admin/sellers/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du Vendeur') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('sellers.edit', $seller->ID) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="row g-3">
            <!-- Informations Générales -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('Informations Générales') }}</h5>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-uppercase small fw-semibold text-secondary">{{ __('ID') }} :</dt>
                            <dd class="col-sm-8">{{ $seller->ID }}</dd>

                            <dt class="col-sm-4 text-uppercase small fw-semibold text-secondary">{{ __('Email') }} :</dt>
                            <dd class="col-sm-8">{{ $seller->Identifiant_email }}</dd>

                            <dt class="col-sm-4 text-uppercase small fw-semibold text-secondary">{{ __('Statut') }} :</dt>
                            <dd class="col-sm-8">
                                @if($seller->Actived)
                                    <span class="badge bg-success">{{ __('Actif') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('Inactif') }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-uppercase small fw-semibold text-secondary">{{ __('Type') }} :</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-info">{{ __('Vendeur (Super Admin Limité)') }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sièges Accessibles -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            {{ __('Sièges Accessibles') }}
                            <span class="badge bg-light text-dark">{{ $seller->sellerSieges->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($seller->sellerSieges->isEmpty())
                            <p class="text-muted mb-0">{{ __('Aucun siège assigné') }}</p>
                        @else
                            <div style="max-height: 300px; overflow-y: auto;">
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
                                                <span class="badge bg-success rounded-pill">{{ __('Actif') }}</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">{{ __('Inactif') }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                                {{ __('Retour à la liste') }}
                            </a>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sellers.edit', $seller->ID) }}" class="btn btn-warning">
                                    {{ __('Modifier') }}
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    {{ __('Supprimer') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirmer la suppression') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('Êtes-vous sûr de vouloir supprimer le vendeur') }} 
                    <strong>{{ $seller->Identifiant_email }}</strong> ?
                    <br><br>
                    {{ __('Cette action supprimera également tous ses accès aux sièges et est irréversible.') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Annuler') }}
                    </button>
                    <form action="{{ route('sellers.destroy', $seller->ID) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            {{ __('Supprimer') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>