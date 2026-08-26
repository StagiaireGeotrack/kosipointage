{{-- resources/views/leave_policy_assignments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-link-45deg"></i> {{ __('Assignations des Politiques de Congé') }}
            </h2>
            <a href="{{ route('admin.leave-policy-assignments.create') }}" class="btn btn-primary">
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

                <!-- Filtres -->
                <form action="{{ route('admin.leave-policy-assignments.index') }}" method="GET" class="mb-4">
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
                                <a href="{{ route('admin.leave-policy-assignments.index') }}" class="btn btn-secondary">
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
                                            <a href="{{ route('admin.leave-policy-assignments.show', $assignment) }}" 
                                               class="text-primary" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.leave-policy-assignments.edit', $assignment) }}" 
                                               class="text-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-link text-danger p-0 border-0" 
                                                    onclick="if(confirm('Voulez-vous vraiment supprimer cette assignation ?')) {
                                                        document.getElementById('delete-form-{{ $assignment->id }}').submit();
                                                    }" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $assignment->id }}" 
                                                  action="{{ route('admin.leave-policy-assignments.destroy', $assignment) }}" 
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