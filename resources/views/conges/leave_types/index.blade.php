{{-- resources/views/conges/leave_types/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Gestion des Types de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-types.create') }}" class="btn btn-primary">
                {{ __('Nouveau type') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('admin.leave-types.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="search" :value="__('Recherche')" />
                            <x-text-input id="search" name="search" type="text" class="form-control mt-1" 
                                :value="request('search')" placeholder="{{ __('Nom ou code') }}" />
                        </div>

                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="is_active" :value="__('Statut')" />
                            <select id="is_active" name="is_active" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>{{ __('Actif') }}</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>{{ __('Inactif') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Valider') }}
                        </button>
                        <a href="{{ route('admin.leave-types.index') }}" class="btn btn-secondary">
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

                <!-- Tableau -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Code') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Nom') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Couleur') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Unité') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Délai prévenance') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Durée max') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Siège') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Personnalisable') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Statut') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveTypes as $type)
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary">{{ $type->code }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>{{ $type->name }}</strong>
                                            @if(isset($type->is_global) && $type->is_global)
                                                <span class="badge bg-info" title="Type global">Global</span>
                                            @endif
                                            @if(isset($type->is_overridden) && $type->is_overridden)
                                                <span class="badge bg-warning text-dark" title="Personnalisé pour ce siège">Override</span>
                                            @endif
                                            @if(isset($type->deleted_at) && $type->deleted_at)
                                                <span class="badge bg-danger">Supprimé</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge" style="background-color: {{ $type->color ?? '#10B981' }}; width: 30px; height: 20px;">&nbsp;</span>
                                            <span class="text-muted small">{{ $type->color ?? '#10B981' }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $unitLabels = [
                                                'days' => 'Jours',
                                                'half_days' => 'Demi-journées',
                                                'hours' => 'Heures'
                                            ];
                                        @endphp
                                        <span class="badge bg-secondary">{{ $unitLabels[$type->unit] ?? $type->unit }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $noticeDays = $type->min_notice_days ?? 0;
                                        @endphp
                                        <span class="badge {{ $noticeDays > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                            {{ $noticeDays }}j
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $maxDuration = $type->max_duration_per_request ?? null;
                                        @endphp
                                        @if($maxDuration)
                                            <span class="badge bg-warning text-dark">{{ $maxDuration }}j</span>
                                        @else
                                            <span class="badge bg-secondary">∞</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if(isset($type->is_global) && $type->is_global)
                                            <span class="text-muted">—</span>
                                        @else
                                            {{ $type->site->Nom ?? $type->site_name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @if(isset($type->is_global) && $type->is_global)
                                            @if(isset($type->is_customizable) && $type->is_customizable)
                                                <span class="badge bg-primary">Oui</span>
                                            @else
                                                <span class="badge bg-secondary">Non</span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge {{ ($type->is_active ?? true) ? 'bg-success' : 'bg-danger' }}">
                                            {{ ($type->is_active ?? true) ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.leave-types.show', $type->id) }}" 
                                               class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            @php
                                                $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                                                $isGlobal = isset($type->is_global) && $type->is_global;
                                                $isCustomizable = isset($type->is_customizable) && $type->is_customizable;
                                                $isDeleted = isset($type->deleted_at) && $type->deleted_at;
                                                
                                                $canEdit = false;
                                                if ($isSuperAdmin) {
                                                    $canEdit = true;
                                                } elseif ($isGlobal && $isCustomizable) {
                                                    $canEdit = true;
                                                } elseif (!$isGlobal) {
                                                    $canEdit = true;
                                                }
                                            @endphp

                                            @if($canEdit && !$isDeleted)
                                                <a href="{{ route('admin.leave-types.edit', $type->id) }}" 
                                                   class="text-warning" title="{{ $isGlobal && !$isSuperAdmin ? 'Personnaliser pour ce siège' : 'Modifier' }}">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    @if($isGlobal && !$isSuperAdmin)
                                                        <span class="small text-muted">Personnaliser</span>
                                                    @endif
                                                </a>
                                            @endif

                                            @if((!$isGlobal || $isSuperAdmin) && !$isDeleted)
                                                <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="setDeleteAction('{{ route('admin.leave-types.destroy', $type->id) }}', '{{ $type->name }}')" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal" 
                                                        title="Supprimer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @endif

                                            @if($isDeleted)
                                                <button type="button" class="btn btn-link text-success p-0 border-0" 
                                                        onclick="if(confirm('Voulez-vous vraiment restaurer ce type de congé ?')) { 
                                                            document.getElementById('restore-form-{{ $type->id }}').submit(); 
                                                        }" title="Restaurer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <form id="restore-form-{{ $type->id }}" 
                                                    action="{{ route('admin.leave-types.restore', $type->id) }}" 
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PATCH')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <svg class="bi mb-2" width="48" height="48" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <p class="mb-0">{{ __('Aucun type de congé pour le moment') }}</p>
                                        </div>
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
                                <p>{{ __('Voulez-vous vraiment supprimer ce type de congé ?') }}</p>
                                <p class="fw-bold" id="details_type"></p>
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
                @if(isset($leaveTypes) && method_exists($leaveTypes, 'links'))
                    <div class="mt-1">
                        {{ $leaveTypes->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            function setDeleteAction(url, name) {
                document.getElementById('deleteForm').action = url;
                document.getElementById('details_type').textContent = name;
            }
        </script>
    @endpush
</x-app-layout>