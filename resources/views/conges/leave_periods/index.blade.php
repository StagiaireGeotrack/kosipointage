{{-- resources/views/conges/leave_periods/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Gestion des Périodes de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-periods.create') }}" class="btn btn-primary">
                {{ __('Nouvelle période') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('admin.leave-periods.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="status" :value="__('Statut')" />
                            <select id="status" name="status" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>{{ __('Préparation') }}</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>{{ __('Ouvert') }}</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>{{ __('Fermé') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="request('search')" placeholder="{{ __('Nom de la période') }}" />
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Valider') }}
                        </button>
                        <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">
                            {{ __('Réinitialiser') }}
                        </a>
                    </div>
                </form>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tableau des périodes -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Nom') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Type de congé') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Siège') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date début') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date fin') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Statut') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Report') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Personnalisable') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($periods as $period)
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>{{ $period->name }}</strong>
                                            @if(isset($period->is_global) && $period->is_global)
                                                <span class="badge bg-info" title="Période globale">Global</span>
                                            @endif
                                            @if(isset($period->is_overridden) && $period->is_overridden)
                                                <span class="badge bg-warning text-dark" title="Configuration locale personnalisée">Override</span>
                                            @endif
                                            @if(isset($period->is_default) && $period->is_default)
                                                <span class="badge bg-success">Par défaut</span>
                                            @endif
                                            @if(isset($period->deleted_at) && $period->deleted_at)
                                                <span class="badge bg-danger">Supprimé</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        {{ $period->leaveType->name ?? 'N/A' }}
                                    </td>
                                    <td class="align-middle">
                                        @if(isset($period->is_global) && $period->is_global)
                                            <span class="text-muted">—</span>
                                        @else
                                            {{ $period->site_name ?? $period->site->Nom ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="align-middle">
                                        {{ \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $statusBadge = match($period->status) {
                                                'preparing' => 'bg-secondary',
                                                'open' => 'bg-success',
                                                'closed' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $statusLabel = match($period->status) {
                                                'preparing' => 'Préparation',
                                                'open' => 'Ouvert',
                                                'closed' => 'Fermé',
                                                default => $period->status
                                            };
                                        @endphp
                                        <span class="badge {{ $statusBadge }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($period->allow_rollover ?? false)
                                            <span class="badge bg-info">
                                                {{ $period->max_rollover_days ?? 'Illimité' }} jours
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if(isset($period->is_customizable) && $period->is_customizable)
                                            <span class="badge bg-primary">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.leave-periods.show', $period->id) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            <a href="{{ route('admin.leave-periods.edit', $period->id) }}" class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>

                                            @if(!(isset($period->is_global) && $period->is_global) || (auth()->user()->IsSuperAdmin ?? false))
                                                <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="setDeleteAction('{{ route('admin.leave-periods.destroy', $period->id) }}', '{{ $period->name }}')" 
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
                                    <td colspan="9" class="text-center py-4">
                                        {{ __('Aucune période de congé pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal de confirmation -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirmation de suppression') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Voulez-vous vraiment supprimer cette période de congé ?') }}</p>
                                <p class="fw-bold" id="details_period"></p>
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
                @if(isset($periods) && method_exists($periods, 'links'))
                    <div class="mt-1">
                        {{ $periods->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
        function setDeleteAction(url, name) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('details_period').textContent = name;
        }
        </script>
    @endpush
</x-app-layout>