{{-- resources/views/conges/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Gestion des Congés') }}
            </h2>
            <a href="{{ route('conges.create') }}" class="btn btn-primary">
                {{ __('Nouveau congé') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('conges.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control" :value="$filters['search'] ?? ''" placeholder="{{ __('Nom de l\'employé') }}" />
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <select id="employee_id" name="employee_id" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->ID }}" {{ isset($filters['employee_id']) && $filters['employee_id'] == $employe->ID ? 'selected' : '' }}>
                                        {{ $employe->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="type_conge" :value="__('Type de congé')" />
                            <select id="type_conge" name="type_conge" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="CP" {{ isset($filters['type_conge']) && $filters['type_conge'] == 'CP' ? 'selected' : '' }}>{{ __('CP') }}</option>
                                <option value="RTT" {{ isset($filters['type_conge']) && $filters['type_conge'] == 'RTT' ? 'selected' : '' }}>{{ __('RTT') }}</option>
                                <option value="Maladie" {{ isset($filters['type_conge']) && $filters['type_conge'] == 'Maladie' ? 'selected' : '' }}>{{ __('Maladie') }}</option>
                                <option value="Autres" {{ isset($filters['type_conge']) && $filters['type_conge'] == 'Autres' ? 'selected' : '' }}>{{ __('Autres') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Rechercher') }}
                        </button>
                        <a href="{{ route('conges.index') }}" class="btn btn-secondary">
                            {{ __('Réinitialiser') }}
                        </a>
                    </div>
                </form>

                <!-- Tableau des congés -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Employé') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Type') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date début') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date fin') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Durée') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date de création') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conges as $conge)
                                <tr>
                                    <td class="align-middle">
                                        {{ $conge->employe->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $badgeClass = match($conge->type_conge) {
                                                'CP' => 'bg-primary',
                                                'RTT' => 'bg-info',
                                                'Maladie' => 'bg-danger',
                                                'Autres' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $conge->type_conge }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        {{ ucfirst($conge->date_debut->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
                                    </td>
                                    <td class="align-middle">
                                        {{ ucfirst($conge->date_fin->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
                                    </td>
                                    {{-- resources/views/conges/index.blade.php --}}
                                    <td class="align-middle">
                                        @php
                                            $jourOuvrableService = app(\App\Services\JourOuvrableService::class);
                                            $resultat = $jourOuvrableService->calculerJoursOuvrables(
                                                $conge->date_debut, 
                                                $conge->date_fin,
                                                $conge->employe->SiegeID ?? null
                                            );
                                        @endphp
                                        
                                        <div>
                                            @if($resultat['jours'] > 0)
                                                <span class="text-muted">{{ round( $resultat['jours'] ) }} jour(s) ouvrable(s)</span>
                                            @endif
                                            
                                            @if($resultat['heures'] > 0)
                                                @if($resultat['jours'] > 0) et @endif
                                                <span class="text-muted">{{ round( $resultat['heures'] ) }} h</span>
                                            @endif
                                            
                                            @if($resultat['jours'] == 0 && $resultat['heures'] == 0)
                                                <span class="badge bg-warning text-dark">{{ __('Aucun jour ouvrable') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        {{ ucfirst($conge->created_at->isoFormat('dddd D MMMM YYYY - HH:mm:ss')) }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('conges.show', $conge->id) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            
                                            <a href="{{ route('conges.edit', $conge->id) }}" class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            
                                            <form action="{{ route('conges.destroy', $conge->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce congé ?') }}')" title="Supprimer">
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
                                        {{ __('Aucun congé pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-1">
                    {{ $conges->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>