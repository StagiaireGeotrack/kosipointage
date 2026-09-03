{{-- resources/views/employe/validations/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-check-circle"></i> {{ __('Mes validations') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-3">
        <!-- Demandes en attente -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ __('Demandes à valider') }}</h5>
            </div>
            <div class="card-body">
                @if($pendingApprovals->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>{{ __('Aucune demande en attente de votre validation.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Employé') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Période') }}</th>
                                    <th>{{ __('Durée') }}</th>
                                    <th>{{ __('Étape') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingApprovals as $approval)
                                    @php $request = $approval->leaveRequest; @endphp
                                    <tr>
                                        <td>{{ $request->employee->Nom ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge" style="background: {{ $request->leaveType->color ?? '#6c757d' }};">
                                                {{ $request->leaveType->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($request->duration, 1) }} jours</td>
                                        <td>
                                            <span class="badge bg-primary">Étape {{ $approval->step_order }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('employe.validations.approve', $approval->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Approuver
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $approval->id }}">
                                                <i class="bi bi-x-circle"></i> Refuser
                                            </button>
                                            <!-- Modal Rejet -->
                                            <div class="modal fade" id="rejectModal{{ $approval->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('employe.validations.reject', $approval->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ __('Refuser la demande') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">{{ __('Motif du refus') }} <span class="text-danger">*</span></label>
                                                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('Confirmer le refus') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $pendingApprovals->links() }}
                @endif
            </div>
        </div>

        <!-- Historique -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ __('Historique des validations') }}</h5>
            </div>
            <div class="card-body">
                @if($history->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <p>{{ __('Aucune validation effectuée.') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>{{ __('Employé') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Période') }}</th>
                                    <th>{{ __('Statut') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $approval)
                                    @php $request = $approval->leaveRequest; @endphp
                                    <tr>
                                        <td>{{ $request->employee->Nom ?? 'N/A' }}</td>
                                        <td>{{ $request->leaveType->name ?? 'N/A' }}</td>
                                        <td>{{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $approval->status == 'approved' ? 'success' : 'danger' }}">
                                                {{ $approval->status == 'approved' ? 'Approuvé' : 'Refusé' }}
                                            </span>
                                        </td>
                                        <td>{{ $approval->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $history->links() }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>