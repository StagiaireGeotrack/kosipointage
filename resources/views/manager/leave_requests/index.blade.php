<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-list-ul"></i> Demandes de congé - Mon équipe
            </h2>
            <a href="{{ route('manager.notifications.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                <!-- Statistiques -->
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">En attente</h6>
                            <h3 class="text-warning">{{ $pendingCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Approuvées</h6>
                            <h3 class="text-success">{{ $approvedCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Refusées</h6>
                            <h3 class="text-danger">{{ $rejectedCount ?? 0 }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded p-3 text-center">
                            <h6 class="text-muted">Total</h6>
                            <h3>{{ $totalCount ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-3">
                    <form method="GET" class="row g-2">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="all">Tous les statuts</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusé</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </form>
                </div>

                <!-- Liste des demandes -->
                @if(isset($leaveRequests) && $leaveRequests->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>Aucune demande de congé dans votre équipe</p>
                    </div>
                @elseif(!isset($leaveRequests))
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>Aucune demande de congé</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Période</th>
                                    <th>Durée</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveRequests as $request)
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
                                            <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$request->status] ?? $request->status }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('manager.leave-requests.show', $request->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $leaveRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>