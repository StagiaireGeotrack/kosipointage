{{-- resources/views/employe/validations/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-file-text"></i> {{ __('Détails de la demande') }}
            </h2>
            <a href="{{ route('employe.validations.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Informations de la demande -->
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Employé') }}</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->employee->Nom ?? 'N/A' }}</dd>

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Type de congé') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge" style="background: {{ $leaveRequest->leaveType->color ?? '#6c757d' }}; color: #fff;">
                                    {{ $leaveRequest->leaveType->name ?? 'N/A' }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Période') }}</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->period->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Date de début') }}</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->start_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Date de fin') }}</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->end_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Durée') }}</dt>
                            <dd class="col-sm-8">{{ number_format($leaveRequest->duration, 1) }} jours</dd>

                            @if($leaveRequest->reason)
                                <dt class="col-sm-4 fw-bold text-secondary">{{ __('Motif') }}</dt>
                                <dd class="col-sm-8">{{ $leaveRequest->reason }}</dd>
                            @endif
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Statut global') }}</dt>
                            <dd class="col-sm-8">
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'cancelled' => 'secondary'
                                    ];
                                    $statusLabels = [
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

                            @if($leaveRequest->rejection_reason)
                                <dt class="col-sm-4 fw-bold text-secondary">{{ __('Motif final') }}</dt>
                                <dd class="col-sm-8 text-danger">{{ $leaveRequest->rejection_reason }}</dd>
                            @endif

                            <dt class="col-sm-4 fw-bold text-secondary">{{ __('Créé le') }}</dt>
                            <dd class="col-sm-8">{{ $leaveRequest->created_at->format('d/m/Y H:i') }}</dd>

                            @if($leaveRequest->approved_at)
                                <dt class="col-sm-4 fw-bold text-secondary">{{ __('Approuvé le') }}</dt>
                                <dd class="col-sm-8">{{ $leaveRequest->approved_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Workflow de validation -->
                <div class="mt-4 pt-3 border-top">
                    <h5 class="mb-3 fw-semibold">{{ __('Workflow de validation') }}</h5>
                    <div class="timeline">
                        @foreach($leaveRequest->approvals as $step)
                            @php
                                $statusClass = match($step->status) {
                                    'approved' => 'border-start border-4 border-success',
                                    'rejected' => 'border-start border-4 border-danger',
                                    'pending' => 'border-start border-4 border-warning',
                                    default => 'border-start border-4 border-secondary'
                                };
                                $bgClass = $step->is_current ? 'bg-light' : '';
                                $statusBadge = match($step->status) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'pending' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                                $statusLabel = match($step->status) {
                                    'approved' => 'Approuvé',
                                    'rejected' => 'Refusé',
                                    'pending' => 'En attente',
                                    default => $step->status
                                };
                            @endphp
                            <div class="card mb-3 shadow-sm {{ $statusClass }} {{ $bgClass }}">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <span class="fw-bold text-dark">Étape {{ $step->step_order }}</span>
                                            @if($step->approver)
                                                <span class="text-muted ms-2">— {{ $step->approver->Nom }}</span>
                                            @endif
                                            @if($step->is_current)
                                                <span class="badge bg-warning text-dark ms-2">En cours</span>
                                            @endif
                                            <span class="badge {{ $statusBadge }} ms-2">{{ $statusLabel }}</span>
                                        </div>
                                        <div class="text-muted small">
                                            @if($step->approved_at)
                                                <span class="me-2">
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                    {{ \Carbon\Carbon::parse($step->approved_at)->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                            @if($step->rejected_at)
                                                <span>
                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                    {{ \Carbon\Carbon::parse($step->rejected_at)->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($step->rejection_reason)
                                        <div class="mt-2 text-danger small bg-danger bg-opacity-10 p-2 rounded">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <strong>Motif :</strong> {{ $step->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                @if($currentApproval && $currentApproval->status == 'pending' && $currentApproval->approver_id == Auth::guard('employe')->user()->ID)
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold mb-3">{{ __('Votre décision') }}</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <form action="{{ route('employe.validations.approve', $currentApproval->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Voulez-vous vraiment approuver cette demande ?')">
                                    <i class="bi bi-check-circle"></i> Approuver
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Refuser
                            </button>
                        </div>
                    </div>

                    <!-- Modal Refus -->
                    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('employe.validations.reject', $currentApproval->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-semibold">{{ __('Refuser la demande') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $previousRejections = $leaveRequest->approvals()
                                                ->where('status', 'rejected')
                                                ->where('step_order', '<', $currentApproval->step_order)
                                                ->get();
                                        @endphp
                                        @if($previousRejections->isNotEmpty())
                                            <div class="alert alert-warning mb-3">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                                <strong class="ms-1">Avis des étapes précédentes :</strong>
                                                @foreach($previousRejections as $rej)
                                                    <br>• <strong>Étape {{ $rej->step_order }}</strong> : {{ $rej->rejection_reason }}
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">{{ __('Motif du refus') }} <span class="text-danger">*</span></label>
                                            <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Expliquez le motif du refus..."></textarea>
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
                @endif

                <!-- Pièces jointes -->
                @if($leaveRequest->attachments->isNotEmpty())
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="fw-semibold mb-3">{{ __('Pièces jointes') }}</h6>
                        <div class="list-group">
                            @foreach($leaveRequest->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        @php
                                            $icon = 'bi-file-earmark';
                                            if (str_contains($attachment->mime_type, 'pdf')) {
                                                $icon = 'bi-file-pdf text-danger';
                                            } elseif (str_contains($attachment->mime_type, 'image')) {
                                                $icon = 'bi-file-image text-success';
                                            } elseif (str_contains($attachment->mime_type, 'word') || str_contains($attachment->mime_type, 'document')) {
                                                $icon = 'bi-file-word text-primary';
                                            } elseif (str_contains($attachment->mime_type, 'excel') || str_contains($attachment->mime_type, 'sheet')) {
                                                $icon = 'bi-file-excel text-success';
                                            } else {
                                                $icon = 'bi-file-earmark';
                                            }
                                        @endphp
                                        <i class="bi {{ $icon }} me-2"></i>
                                        <span>{{ $attachment->file_name }}</span>
                                        <small class="text-muted ms-2">
                                            ({{ number_format($attachment->file_size / 1024, 1) }} KB)
                                        </small>
                                    </div>
                                    <a href="{{ route('employe.leave-requests.download-attachment', $attachment->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .border-start {
            border-left-width: 4px !important;
        }
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        .card {
            transition: all 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
        }
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        dl dt {
            font-weight: 600;
            color: #6c757d;
        }
        .list-group-item {
            transition: background-color 0.2s ease;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
    @endpush
</x-app-layout>