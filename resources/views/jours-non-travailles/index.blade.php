{{-- resources/views/jours-non-travailles/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Jours non travaillés') }}
            </h2>
            <a href="{{ route('jours-non-travailles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>{{ __('Nouveau jour férié') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('jours-non-travailles.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control" :value="$filters['search'] ?? ''" placeholder="{{ __('Nom du jour') }}" />
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-3">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                                <option value="null" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == 'null' ? 'selected' : '' }}>
                                    {{ __('National (tous les sièges)') }}
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-2">
                            <x-input-label for="Type" :value="__('Type')" />
                            <select id="Type" name="Type" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="ferie" {{ isset($filters['Type']) && $filters['Type'] == 'ferie' ? 'selected' : '' }}>{{ __('Férié') }}</option>
                                <option value="fermeture" {{ isset($filters['Type']) && $filters['Type'] == 'fermeture' ? 'selected' : '' }}>{{ __('Fermeture') }}</option>
                                <option value="autre" {{ isset($filters['Type']) && $filters['Type'] == 'autre' ? 'selected' : '' }}>{{ __('Autre') }}</option>
                            </select>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-2">
                            <x-input-label for="annee" :value="__('Année')" />
                            <select id="annee" name="annee" class="form-select">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee }}" {{ isset($filters['annee']) && $filters['annee'] == $annee ? 'selected' : '' }}>
                                        {{ $annee }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-2">
                            <x-input-label for="Recurrent" :value="__('Récurrent')" />
                            <select id="Recurrent" name="Recurrent" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ isset($filters['Recurrent']) && $filters['Recurrent'] == '1' ? 'selected' : '' }}>{{ __('Oui') }}</option>
                                <option value="0" {{ isset($filters['Recurrent']) && $filters['Recurrent'] == '0' ? 'selected' : '' }}>{{ __('Non') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>{{ __('Rechercher') }}
                            </button>
                            <a href="{{ route('jours-non-travailles.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>{{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>
                    
                <div class="d-flex mb-3 gap-2">
                    <a href="{{ route('jours-non-travailles.export.excel', request()->query()) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel me-1"></i>{{ __('Exporter en EXCEL') }}
                    </a>
                    <a href="{{ route('jours-non-travailles.export.pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i>{{ __('Exporter en PDF') }}
                    </a>
                </div>
                    
                <!-- Tableau -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    <a href="{{ route('jours-non-travailles.index', array_merge(request()->query(), ['sort_by' => 'Date', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-secondary">
                                        {{ __('Date') }}
                                        @if(request('sort_by') === 'Date')
                                            <i class="bi bi-arrow-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    <a href="{{ route('jours-non-travailles.index', array_merge(request()->query(), ['sort_by' => 'Nom', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-secondary">
                                        {{ __('Nom') }}
                                        @if(request('sort_by') === 'Nom')
                                            <i class="bi bi-arrow-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Type') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Siège') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Récurrent') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Statut') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($joursNonTravailles as $jour)
                                <tr>
                                    <td class="align-middle">
                                        {{ ucfirst($jour->Date->isoFormat('dddd D MMMM YYYY')) }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $jour->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $badgeClass = match($jour->Type) {
                                                'ferie' => 'bg-primary',
                                                'fermeture' => 'bg-warning text-dark',
                                                'autre' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($jour->Type) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if($jour->SiegeID)
                                            {{ $jour->siege->Nom }}
                                        @else
                                            <span class="badge bg-info">{{ __('National') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($jour->Recurrent)
                                            <span class="badge bg-success">
                                                <i class="bi bi-arrow-repeat me-1"></i>{{ __('Oui') }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Non') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($jour->Actived)
                                            <span class="badge bg-success">{{ __('Actif') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Inactif') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('jours-non-travailles.show', $jour->ID) }}" class="text-primary" title="{{ __('Voir') }}">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            
                                            <a href="{{ route('jours-non-travailles.edit', $jour->ID) }}" class="text-warning" title="{{ __('Modifier') }}">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            
                                            <form action="{{ route('jours-non-travailles.destroy', $jour->ID) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce jour ?') }}')" title="{{ __('Supprimer') }}">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        {{ __('Aucun jour non travaillé enregistré') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3">
                    {{ $joursNonTravailles->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>