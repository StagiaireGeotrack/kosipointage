{{-- resources/views/hierarchy_levels/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-layers"></i> {{ __('Niveaux Hiérarchiques KOSI') }}
            </h2>
            <a href="{{ route('hierarchy-levels.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> {{ __('Nouveau niveau') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Niveau') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Nom') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Rang') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Rôle') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Employés') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($levels as $level)
                                <tr>
                                    <td class="align-middle">
                                        <span class="badge bg-primary fs-6">{{ $level->code }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong>{{ $level->name }}</strong>
                                            @if($level->trashed())
                                                <span class="badge bg-danger">Supprimé</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-secondary">{{ $level->rank }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($level->is_managerial)
                                            <span class="badge bg-success"><i class="bi bi-person-badge"></i> Managerial</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-person"></i> Non managerial</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-info">{{ $level->employees->count() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('hierarchy-levels.show', $level) }}" 
                                               class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>

                                            @if(!$level->trashed())
                                                <a href="{{ route('hierarchy-levels.edit', $level) }}" 
                                                   class="text-warning" title="Modifier">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            @if(!$level->trashed() && $level->employees->count() == 0)
                                                <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                        onclick="if(confirm('Voulez-vous vraiment supprimer ce niveau ?')) {
                                                            document.getElementById('delete-form-{{ $level->id }}').submit();
                                                        }" title="Supprimer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <form id="delete-form-{{ $level->id }}" 
                                                      action="{{ route('hierarchy-levels.destroy', $level) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @elseif($level->trashed())
                                                <button type="button" class="btn btn-link text-success p-0 border-0" 
                                                        onclick="if(confirm('Voulez-vous vraiment restaurer ce niveau ?')) {
                                                            document.getElementById('restore-form-{{ $level->id }}').submit();
                                                        }" title="Restaurer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                                <form id="restore-form-{{ $level->id }}" 
                                                      action="{{ route('hierarchy-levels.restore', $level->id) }}" 
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
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <p>{{ __('Aucun niveau hiérarchique défini') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($levels) && method_exists($levels, 'links'))
                    <div class="mt-3">
                        {{ $levels->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>