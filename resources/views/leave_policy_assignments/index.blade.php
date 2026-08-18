{{-- resources/views/leave_policy_assignments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-link-45deg"></i> {{ __('Assignations des Politiques de Congé') }}
            </h2>
            <a href="{{ route('leave-policy-assignments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle assignation') }}
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

                <!-- Filtres -->
                <form action="{{ route('leave-policy-assignments.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input-label for="leave_policy_id" :value="__('Politique')" />
                            <select id="leave_policy_id" name="leave_policy_id" class="form-select mt-1">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach($leavePolicies as $policy)
                                    <option value="{{ $policy->id }}" {{ request('leave_policy_id') == $policy->id ? 'selected' : '' }}>
                                        {{ $policy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <x-input-label for="target_type" :value="__('Type de cible')" />
                            <select id="target_type" name="target_type" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                <option value="employee" {{ request('target_type') == 'employee' ? 'selected' : '' }}>Employé</option>
                                <option value="department" {{ request('target_type') == 'department' ? 'selected' : '' }}>Service</option>
                                <option value="job_title" {{ request('target_type') == 'job_title' ? 'selected' : '' }}>Poste</option>
                                <option value="hierarchy_level" {{ request('target_type') == 'hierarchy_level' ? 'selected' : '' }}>Niveau KOSI</option>
                                <option value="site" {{ request('target_type') == 'site' ? 'selected' : '' }}>Siège</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> {{ __('Valider') }}
                                </button>
                                <a href="{{ route('leave-policy-assignments.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> {{ __('Réinitialiser') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Politique') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Cible') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Priorité') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Statut') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $assignment)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $assignment->leavePolicy->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $targetName = '';
                                            if ($assignment->employee_id) {
                                                $targetName = '👤 ' . ($assignment->employee->Nom ?? 'N/A');
                                            } elseif ($assignment->department_id) {
                                                $targetName = '🏢 ' . ($assignment->department->name ?? 'N/A');
                                            } elseif ($assignment->job_title_id) {
                                                $targetName = '💼 ' . ($assignment->jobTitle->name ?? 'N/A');
                                            } elseif ($assignment->hierarchy_level_id) {
                                                $targetName = '📊 ' . ($assignment->hierarchyLevel->code ?? 'N/A');
                                            } elseif ($assignment->site_id) {
                                                $targetName = '🏛️ ' . ($assignment->site->Nom ?? 'N/A');
                                            } else {
                                                $targetName = '🌍 Global';
                                            }
                                        @endphp
                                        <span class="badge bg-info">{{ $targetName }}</span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $priorityColor = match(true) {
                                                $assignment->priority >= 75 => 'danger',
                                                $assignment->priority >= 50 => 'warning',
                                                $assignment->priority >= 25 => 'info',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $priorityColor }}">
                                            {{ $assignment->priority }}%
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if($assignment->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('leave-policy-assignments.show', $assignment) }}" 
                                               class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('leave-policy-assignments.edit', $assignment) }}" 
                                               class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                    onclick="if(confirm('Voulez-vous vraiment supprimer cette assignation ?')) {
                                                        document.getElementById('delete-form-{{ $assignment->id }}').submit();
                                                    }" title="Supprimer">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $assignment->id }}" 
                                                  action="{{ route('leave-policy-assignments.destroy', $assignment) }}" 
                                                  method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <p>{{ __('Aucune assignation pour le moment') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($assignments) && method_exists($assignments, 'links'))
                    <div class="mt-3">
                        {{ $assignments->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>