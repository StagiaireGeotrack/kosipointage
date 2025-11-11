{{-- resources/views/entreprises/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Sites ou établissements') }}
            </h2>
            @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
            <a href="{{ route('entreprises.create') }}" class="btn btn-primary">
                {{ __('Nouveau site ou établissement') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('entreprises.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="$filters['search'] ?? ''" />
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
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
                        <div class="col-12 col-sm-6 col-md-3">
                            <x-input-label for="Actived" :value="__('Statut')" />
                            <select id="Actived" name="Actived" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ isset($filters['Actived']) && $filters['Actived'] == '1' ? 'selected' : '' }}>{{ __('Activé') }}</option>
                                <option value="0" {{ isset($filters['Actived']) && $filters['Actived'] == '0' ? 'selected' : '' }}>{{ __('Désactivé') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Rechercher') }}
                            </button>
                            <a href="{{ route('entreprises.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>
                
                <!-- Exports -->
                <div class="d-flex justify-content-end mb-3 gap-2">
                    <a href="{{ route('entreprises.export.excel', request()->query()) }}" class="btn btn-success">
                        {{ __('Exporter en EXCEL') }}
                    </a>
                    <a href="{{ route('entreprises.export.pdf', request()->query()) }}" class="btn btn-danger">
                        {{ __('Exporter en PDF') }}
                    </a>
                </div>

                <!-- Tableau des entreprises -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Logo') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nom') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Adresse ou ville') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Siège') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date de création') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Statut') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($entreprises as $entreprise)
                                <tr>
                                    <td class="align-middle">
                                        @if ($entreprise->Logo)
                                            <img src="{{ route('entreprises.logo.thumbnail', $entreprise->ID) }}" alt="{{ $entreprise->Nom }}" class="rounded-circle" style="height: 40px; width: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;">
                                                <span class="small fw-semibold text-white">{{ substr($entreprise->Nom, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $entreprise->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $entreprise->Nom_Lieu_Ville }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $entreprise->siege->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        {{ ucfirst($entreprise->CreatedAt->isoFormat('dddd D MMMM YYYY')) }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($entreprise->Actived)
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
                                            <a href="{{ route('entreprises.show', $entreprise->ID) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin() )
                                            <a href="{{ route('entreprises.edit', $entreprise->ID) }}" class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('entreprises.destroy', $entreprise->ID) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce site ou établissement ?') }}')" title="Supprimer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                            
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        {{ __('Aucun site ou établissement pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-1">
                    {{ $entreprises->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>