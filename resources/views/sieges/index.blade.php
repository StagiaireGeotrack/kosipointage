{{-- resources/views/sieges/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Sièges') }}
            </h2>

            @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
            <a href="{{ route('sieges.create') }}" class="btn btn-primary">
                {{ __('Nouveau siège') }}
            </a>
            @endif
            
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('sieges.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="$filters['search'] ?? ''" />
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="Actived" :value="__('Statut')" />
                            <select id="Actived" name="Actived" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ isset($filters['Actived']) && $filters['Actived'] == '1' ? 'selected' : '' }}>{{ __('Activé') }}</option>
                                <option value="0" {{ isset($filters['Actived']) && $filters['Actived'] == '0' ? 'selected' : '' }}>{{ __('Désactivé') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Valider') }}
                            </button>
                            <a href="{{ route('sieges.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>
                
                <!-- Exports -->
                <div class="d-flex justify-content-end mb-3 gap-2">
                    <a href="{{ route('sieges.export.excel', request()->query()) }}" class="btn btn-success">
                        {{ __('Exporter en EXCEL') }}
                    </a>
                    <a href="{{ route('sieges.export.pdf', request()->query()) }}" class="btn btn-danger">
                        {{ __('Exporter en PDF') }}
                    </a>
                </div>

                <!-- Tableau des sièges -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nom') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Pays') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Adresse ou ville') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nombre de sites ou établissement') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nombre d\'employés') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date création') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Statut') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sieges as $siege)
                                <tr>
                                    <td class="align-middle">
                                        {{ $siege->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        @if($siege->Pays && country($siege->Pays))
                                            {{ strtoupper($siege->Pays) . " " . country($siege->Pays)->getName() }}
                                        @else
                                            <em>-</em>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $siege->Nom_Lieu_Ville }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $siege->entreprises()->count() }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $siege->employes()->count() }}
                                    </td>
                                    <td class="align-middle">
                                        <x-local-date-time :datetime="$siege->CreatedAt"/>
                                    </td>
                                    <td class="align-middle">
                                        @if ($siege->Actived)
                                            <span class="badge bg-success">
                                                {{ __('Activé') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                {{ __('Désactivé') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">

                                            <a href="{{ route('sieges.show', $siege->ID) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                        @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
                                            <a href="{{ route('sieges.edit', $siege->ID) }}" class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            <!-- Bouton pour ouvrir le modal -->

                                            @php
                                                $siege_details = $siege->Nom;
                                                if ($siege->Pays && country($siege->Pays)) 
                                                {
                                                    $siege_details .= " (" . strtoupper($siege->Pays) . " - " . country($siege->Pays)->getName() . ")";
                                                }
                                            @endphp

                                            <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                    onclick="setDeleteAction('{{ route('sieges.destroy', $siege->ID) }}', '{{ $siege_details }}')" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal" 
                                                    title="Supprimer">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        {{ __('Aucun siège pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal de confirmation (à placer en dehors de la boucle) -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirmation de suppression') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment supprimer ce siège ?') }}</p>
                                <p class="fw-bold" id="details_siege"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                <form id="deleteForm" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">{{ __('Supprimer') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $sieges->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>

    @push("scripts")

        <script>
        function setDeleteAction(url, name) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('details_siege').textContent = name;
        }
        </script>
        
    @endpush

</x-app-layout>