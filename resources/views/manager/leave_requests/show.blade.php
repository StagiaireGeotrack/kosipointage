<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-file-text"></i> Détails de la demande
            </h2>
            <a href="{{ route('manager.leave-requests.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">Employé</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->employee->Nom ?? 'N/A' }}</dd>

                            <dt class="col-sm-4 fw-bold">Type de congé</dt>
                            <dd class="col-sm-8">
                                <span class="badge" style="background: {{ $leaveRequest->leaveType->color ?? '#6c757d' }};">
                                    {{ $leaveRequest->leaveType->name ?? 'N/A' }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">Date de début</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->start_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold">Date de fin</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->end_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold">Durée</dt>
                            <dd class="col-sm-8">{{ number_format($leaveRequest->duration, 1) }} jours</dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">Statut</dt>
                            <dd class="col-sm-8">
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Brouillon',
                                        'pending' => 'En attente',
                                        'approved' => 'Approuvé',
                                        'rejected' => 'Refusé',
                                        'cancelled' => 'Annulé'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$leaveRequest->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$leaveRequest->status] ?? $leaveRequest->status }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">Motif</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->reason ?? '—' }}</dd>

                            <dt class="col-sm-4 fw-bold">Commentaire</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->comment ?? '—' }}</dd>

                            @if($leaveRequest->rejection_reason)
                                <dt class="col-sm-4 fw-bold">Motif du refus</dt>
                                <dd class="col-sm-8">{{ $leaveRequest->rejection_reason }}</dd>
                            @endif

                            <dt class="col-sm-4 fw-bold">Date de création</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>

                <!-- Pièces jointes -->
                @if($leaveRequest->attachments->isNotEmpty())
                    <div class="mt-3 pt-3 border-top">
                        <h6>Pièces jointes</h6>
                        @foreach($leaveRequest->attachments as $attachment)
                            <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-1">
                                <span>{{ $attachment->file_name }}</span>
                                <a href="{{ route('employe.leave-requests.download-attachment', $attachment->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Actions manager -->
                @if($leaveRequest->status == 'pending')
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <form action="{{ route('manager.leave-requests.approve', $leaveRequest->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Approuver
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Refuser
                            </button>
                        </div>
                    </div>
                @endif

                @if($leaveRequest->status == 'approved')
                    <div class="mt-3 pt-3 border-top">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Cette demande a été approuvée le {{ $leaveRequest->approved_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endif

                @if($leaveRequest->status == 'rejected')
                    <div class="mt-3 pt-3 border-top">
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i> Cette demande a été refusée le {{ $leaveRequest->rejected_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Refus -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('manager.leave-requests.reject', $leaveRequest->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Refuser la demande</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Motif du refus <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Expliquez pourquoi cette demande est refusée..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Confirmer le refus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>