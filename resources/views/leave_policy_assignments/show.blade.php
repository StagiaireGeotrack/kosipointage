{{-- resources/views/leave_policy_assignments/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-link-45deg"></i> {{ __('Détails de l\'Assignation') }}
            </h2>
            <div>
                <a href="{{ route('admin.leave-policy-assignments.edit', $leavePolicyAssignment) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.leave-policy-assignments.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">{{ __('Politique') }}</dt>
                            <dd class="col-sm-8">
                                <strong>{{ $leavePolicyAssignment->leavePolicy->name ?? 'N/A' }}</strong>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Type d\'assignation') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-info">{{ $leavePolicyAssignment->assignment_type_label }}</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Cible') }}</dt>
                            <dd class="col-sm-8">
                                @if($leavePolicyAssignment->employee_id)
                                    👤 {{ $leavePolicyAssignment->employee->Nom ?? 'N/A' }}
                                @elseif($leavePolicyAssignment->department_id)
                                    🏢 {{ $leavePolicyAssignment->department->name ?? 'N/A' }}
                                @elseif($leavePolicyAssignment->site_id)
                                    🏛️ {{ $leavePolicyAssignment->site->Nom ?? 'N/A' }}
                                @else
                                    🌍 Global
                                @endif
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Priorité') }}</dt>
                            <dd class="col-sm-8">
                                @php
                                    $priorityColor = match(true) {
                                        $leavePolicyAssignment->priority >= 75 => 'danger',
                                        $leavePolicyAssignment->priority >= 50 => 'warning',
                                        $leavePolicyAssignment->priority >= 25 => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $priorityColor }} fs-6">
                                    {{ $leavePolicyAssignment->priority }}%
                                </span>
                                <span class="text-muted ms-2">({{ $leavePolicyAssignment->priority_label }})</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Statut') }}</dt>
                            <dd class="col-sm-8">
                                @if($leavePolicyAssignment->is_active)
                                    <span class="badge bg-success fs-6">Actif</span>
                                @else
                                    <span class="badge bg-danger fs-6">Inactif</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Créé le') }}</dt>
                            <dd class="col-sm-8">{{ $leavePolicyAssignment->created_at ? $leavePolicyAssignment->created_at->format('d/m/Y H:i') : '-' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Modifié le') }}</dt>
                            <dd class="col-sm-8">{{ $leavePolicyAssignment->updated_at ? $leavePolicyAssignment->updated_at->format('d/m/Y H:i') : '-' }}</dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-info-circle"></i> {{ __('Informations') }}</h6>
                                
                                <div class="alert alert-info">
                                    <i class="bi bi-lightbulb"></i>
                                    <strong>Règle de priorité :</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>L'assignation avec la priorité la plus élevée est appliquée en premier</li>
                                        <li>L'ordre de priorité : Employé > Service > Siège > Entreprise</li>
                                    </ul>
                                </div>

                                <div class="alert alert-secondary">
                                    <i class="bi bi-link"></i>
                                    <strong>Politique associée :</strong><br>
                                    {{ $leavePolicyAssignment->leavePolicy->name ?? 'N/A' }}
                                    @if($leavePolicyAssignment->leavePolicy)
                                        <br><small class="text-muted">Méthode: {{ $leavePolicyAssignment->leavePolicy->calculation_method ?? 'N/A' }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.leave-policy-assignments.edit', $leavePolicyAssignment) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                        </a>
                        <button type="button" class="btn btn-danger" 
                                onclick="if(confirm('Voulez-vous vraiment supprimer cette assignation ?')) {
                                    document.getElementById('delete-form').submit();
                                }">
                            <i class="bi bi-trash"></i> {{ __('Supprimer') }}
                        </button>
                        <form id="delete-form" action="{{ route('admin.leave-policy-assignments.destroy', $leavePolicyAssignment) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>