{{-- resources/views/employe/leave_requests/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-list-ul"></i> {{ __('Mes demandes de congé') }}
            </h2>
            <a href="{{ route('employe.leave-requests.create') }}" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 6px 16px;">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle demande') }}
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
                        <thead>
                            <tr>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Période') }}</th>
                                <th>{{ __('Durée') }}</th>
                                <th>{{ __('Statut') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th style="min-width: 350px;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr>
                                    <td>
                                        <span class="badge" style="background-color: {{ $request->leaveType->color ?? '#4f8a8b' }}; color: #fff;">
                                            {{ $request->leaveType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}</td>
                                    <td>{{ number_format($request->duration, 2) }} {{ $request->duration > 1 ? 'jours' : 'jour' }}</td>
                                    <td>
                                        @php
                                            $statusBadge = match($request->status) {
                                                'draft' => ['bg' => 'secondary', 'text' => 'Brouillon'],
                                                'pending' => ['bg' => 'warning', 'text' => 'En attente'],
                                                'approved' => ['bg' => 'success', 'text' => 'Approuvé'],
                                                'rejected' => ['bg' => 'danger', 'text' => 'Refusé'],
                                                'cancelled' => ['bg' => 'secondary', 'text' => 'Annulé'],
                                                default => ['bg' => 'secondary', 'text' => $request->status],
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge['bg'] }}">
                                            {{ $statusBadge['text'] }}
                                        </span>
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <!-- Bouton Voir avec texte -->
                                            <a href="{{ route('employe.leave-requests.show', $request->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="Voir les détails">
                                                <i class="bi bi-eye"></i> Voir
                                            </a>

                                            <!-- Boutons pour les brouillons -->
                                            @if($request->status == 'draft')
                                                <!-- Bouton Modifier avec texte -->
                                                <a href="{{ route('employe.leave-requests.edit', $request->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Modifier la demande">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </a>
                                                
                                                <!-- Bouton Soumettre avec texte -->
                                                <form action="{{ route('employe.leave-requests.submit', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Soumettre la demande">
                                                        <i class="bi bi-send"></i> Soumettre
                                                    </button>
                                                </form>
                                                
                                                <!-- Bouton Supprimer avec texte -->
                                                <form action="{{ route('employe.leave-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette demande ?')" title="Supprimer la demande">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Bouton Annuler pour les approuvés -->
                                            @if($request->status == 'approved')
                                                <form action="{{ route('employe.leave-requests.cancel-approved', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm" style="background-color: #d97706; color: #fff; border: none; border-radius: 4px;" onclick="return confirm('Annuler ce congé validé ?')" title="Annuler le congé">
                                                        <i class="bi bi-x-circle"></i> Annuler
                                                    </button>
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
                                            <p>{{ __('Aucune demande de congé') }}</p>
                                            <a href="{{ route('employe.leave-requests.create') }}" class="btn btn-sm" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 4px;">
                                                <i class="bi bi-plus-circle"></i> {{ __('Faire une demande') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>