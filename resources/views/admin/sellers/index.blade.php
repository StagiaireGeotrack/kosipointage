{{-- resources/views/admin/sellers/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Gestion des Revendeurs') }}
            </h2>
            <a href="{{ route('sellers.create') }}" class="btn btn-primary">
                {{ __('Nouveau revendeur') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                
                <!-- Filtres -->
                <form action="{{ route('sellers.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="request('search')" placeholder="{{ __('Email du revendeur') }}" />
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="status" :value="__('Statut')" />
                            <select id="status" name="status" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Activé') }}</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Désactivé') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Valider') }}
                        </button>
                        <a href="{{ route('sellers.index') }}" class="btn btn-secondary">
                            {{ __('Réinitialiser') }}
                        </a>
                    </div>
                </form>

                
                
                <!-- Exports -->
                <div class="d-flex justify-content-end mb-3 gap-2">
                    <a href="{{ route('sellers.export.excel', request()->query()) }}" class="btn btn-success">
                        {{ __('Export en EXCEL') }}
                    </a>
                    <a href="{{ route('sellers.export.pdf', request()->query()) }}" class="btn btn-danger">
                        {{ __('Export en PDF') }}
                    </a>
                </div>

                <!-- Tableau des vendeurs -->
                @if($sellers->isEmpty())
                    <div class="text-center py-5">
                        <p class="mt-3 text-muted">{{ __('Aucun revendeur enregistré') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase small fw-semibold text-secondary">{{ __('Email') }}</th>
                                    <th class="text-uppercase small fw-semibold text-secondary">{{ __('Nombre de Sièges') }}</th>
                                    <th class="text-uppercase small fw-semibold text-secondary">{{ __('Statut') }}</th>
                                    <th class="text-uppercase small fw-semibold text-secondary">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sellers as $seller)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="height: 32px; width: 32px;">
                                                    <span class="small fw-semibold text-white">{{ substr($seller->Identifiant_email, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $seller->Identifiant_email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">
                                                {{ $seller->sellerSieges->count() }} {{ __('siège(s)') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @if($seller->Actived)
                                                <span class="badge bg-success">{{ __('Activé') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Désactivé') }}</span>
                                            @endif
                                            @if($seller->deleted)
                                                <span class="badge bg-danger">{{ __('Supprimé') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('sellers.show', $seller->ID) }}" class="text-primary" title="{{ __('Voir') }}">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('sellers.edit', $seller->ID) }}" class="text-warning" title="{{ __('Modifier') }}">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                @if($seller->deleted)
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0" data-bs-toggle="modal" data-bs-target="#resetModal{{ $seller->ID }}" title="{{ __('Réinitialiser') }}">
                                                        <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-link text-danger p-0 border-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $seller->ID }}" title="{{ __('Supprimer') }}">
                                                        <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $seller->ID }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Confirmation de suppression') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Êtes-vous sûr de vouloir supprimer ce revendeur ?') }} 
                                                    <br>
                                                    <strong>{{ $seller->Identifiant_email }}</strong> ?
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

                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="resetModal{{ $seller->ID }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Confirmation de réinitialisation') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('Êtes-vous sûr de vouloir réinitialiser ce revendeur ?') }} 
                                                    <br>
                                                    <strong>{{ $seller->Identifiant_email }}</strong> ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        {{ __('Annuler') }}
                                                    </button>
                                                    <form action="{{ route('sellers.reset', $seller->ID) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-success">
                                                            {{ __('Réinitialiser') }}
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
                    
                    <!-- Pagination -->
                    @if($sellers->hasPages())
                        <div class="mt-1">
                            {{ $sellers->links("pagination.custom") }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>