{{-- resources/views/employes/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Employés') }}
            </h2>
            @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
            <a href="{{ route('employes.create') }}" class="btn btn-primary">
                {{ __('Nouveau employé') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('employes.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="$filters['search'] ?? ''" placeholder="{{ __('Nom ou N° Matricule') }}" />
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <x-input-label for="Actived" :value="__('Statut')" />
                            <select id="Actived" name="Actived" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ isset($filters['Actived']) && $filters['Actived'] == '1' ? 'selected' : '' }}>{{ __('Activé') }}</option>
                                <option value="0" {{ isset($filters['Actived']) && $filters['Actived'] == '0' ? 'selected' : '' }}>{{ __('Désactivé') }}</option>
                            </select>
                        </div>
                        
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Valider') }}
                        </button>
                        <a href="{{ route('employes.index') }}" class="btn btn-secondary">
                            {{ __('Réinitialiser') }}
                        </a>
                    </div>
                </form>
                
                <!-- Exports -->
                <div class="d-flex justify-content-end mb-3 gap-2">
                    <a href="{{ route('employes.export.excel', request()->query()) }}" class="btn btn-success">
                        {{ __('Export en EXCEL') }}
                    </a>
                    <a href="{{ route('employes.export.pdf', request()->query()) }}" class="btn btn-danger">
                        {{ __('Export en PDF') }}
                    </a>
                </div>

                <!-- Tableau des employés -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('N° Matricule') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nom') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Siège') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Méthode') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date de création') }}
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
                            @forelse ($employes as $employe)
                                <tr>
                                    <td class="align-middle">
                                        {{ $employe->num_mat ?? "-"}}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="height: 40px; width: 40px;">
                                                <span class="small fw-semibold text-white">{{ substr($employe->Nom, 0, 1) }}</span>
                                            </div>
                                            {{ $employe->Nom }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        {{ $employe->siege->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            @if ($employe->BadgeID )
                                                <span class="badge bg-info">
                                                    {{ __('Badge') }}
                                                </span>
                                            @endif
                                            @if ($employe->HasFaceSetup)
                                                <span class="badge bg-primary">
                                                    {{ __('Face image') }}
                                                </span>
                                            @endif
                                            @if ($employe->Pin)
                                                <span class="badge bg-secondary">
                                                    {{ __('Code Pin') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <x-local-date-time :datetime="$employe->CreatedAt"/>
                                    </td>
                                    <td class="align-middle">
                                        @if ($employe->Actived)
                                            <span class="badge bg-success">
                                                {{ __('Activé') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                {{ __('Désactivé') }}
                                            </span>
                                        @endif
                                        @if ($employe->deleted)
                                            <span class="badge bg-danger">
                                                {{ __('Supprimé') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">

                                            @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )

                                                <a href="{{ route('employes.show', $employe->ID) }}" class="text-primary" title="Voir ou modifier">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>

                                                     
                                                @if ($employe->deleted)                                
                                                <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="setResetAction('{{ route('employes.reset', $employe->ID) }}', '{{ $employe->Nom }}')" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#resetModal" 
                                                        title="Réinitialiser">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                @else                                   
                                                <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="setDeleteAction('{{ route('employes.destroy', $employe->ID) }}', '{{ $employe->Nom }}')" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal" 
                                                        title="Supprimer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                @endif

                                                @if( auth()->user()->isSimpleAdmin() )
                                                    @if( !empty($employe->Pin) )
                                                        <button type="button" class="btn btn-link text-warning p-0 border-0"
                                                            onclick="setResetPinAction('{{ route('employes.reset-pin', $employe->ID) }}', '{{ $employe->Nom }}')"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#resetPinModal"
                                                            title="Réinitialiser le Code PIN">
                                                            <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M17.65 6.35A7.96 7.96 0 0012 4C7.58 4 4.01 7.58 4.01 12S7.58 20 12 20c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                @endif

                                            @endif

                                            @if (auth()->user()->isSeller() )
                                            
                                            <a href="{{ route('employe.show.seller', $employe->ID) }}" title="Voir les détails">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        {{ __('Aucun employé pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal de confirmation Delete Employé -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirmation de suppression') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment supprimer cet employé ?') }}</p>
                                <p class="fw-bold" id="details_employee"></p>
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


                <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reseteModalLabel">{{ __('Confirmation de réinitialisation') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment supprimer cet employé ?') }}</p>
                                <p class="fw-bold" id="employee_"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                <form id="resetForm" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-success">{{ __('Réinitialiser') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de confirmation réinitialisation Pin Employé -->
                <div class="modal fade" id="resetPinModal" tabindex="-1" aria-labelledby="resetPinModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="resetPinModalLabel">{{ __('Réinitialisation du code PIN') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment réinitialiser le code PIN de cet employé ?') }}</p>
                                <p class="fw-bold" id="details_employee_pin"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                <form id="resetPinForm" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">{{ __('Réinitialiser') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="mt-1">
                    {{ $employes->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>

    @push("scripts")

        <script>

            function setDeleteAction(url, name) {
                document.getElementById('deleteForm').action = url;
                document.getElementById('details_employee').textContent = name;
            }

            function setResetAction(url, name) {
                document.getElementById('resetForm').action = url;
                document.getElementById('employee_').textContent = name;
            }

            function setResetPinAction(url, nom) {
                document.getElementById('resetPinForm').action = url;
                document.getElementById('details_employee_pin').textContent = nom;
            }
            
        </script>
        
    @endpush

</x-app-layout>