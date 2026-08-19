{{-- resources/views/employe/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-speedometer2"></i> {{ __('Mon Tableau de bord') }}
            </h2>
            <a href="{{ route('employe.leave-requests.create') }}" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 6px 16px;">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle demande') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="row g-3 mb-3">
            <!-- Statistiques -->
            <div class="col-md-3">
                <div class="card" style="background-color: #f3f4f6;">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Total demandes') }}</h6>
                        <h3 class="mb-0">{{ $stats['total_requests'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #fef3c7;">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('En attente') }}</h6>
                        <h3 class="mb-0" style="color: #d97706;">{{ $stats['pending'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #d1fae5;">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Approuvées') }}</h6>
                        <h3 class="mb-0" style="color: #065f46;">{{ $stats['approved'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #fee2e2;">
                    <div class="card-body text-center">
                        <h6 class="text-muted">{{ __('Refusées') }}</h6>
                        <h3 class="mb-0" style="color: #991b1b;">{{ $stats['rejected'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Soldes -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-wallet2"></i> {{ __('Mes soldes') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Type de congé') }}</th>
                                <th class="text-end">{{ __('Acquis') }}</th>
                                <th class="text-end">{{ __('Pris') }}</th>
                                <th class="text-end">{{ __('En attente') }}</th>
                                <th class="text-end">{{ __('Restant') }}</th>
                                <th class="text-end">{{ __('Disponible') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($balances as $balance)
                                <tr>
                                    <td>
                                        <span class="badge" style="background-color: {{ $balance->leaveType->color ?? '#4f8a8b' }}; color: #fff;">
                                            {{ $balance->leaveType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($balance->total_entitled, 2) }}</td>
                                    <td class="text-end">{{ number_format($balance->total_taken, 2) }}</td>
                                    <td class="text-end">{{ number_format($balance->total_pending, 2) }}</td>
                                    <td class="text-end">
                                        <strong>{{ number_format($balance->remaining, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span style="color: {{ $balance->available > 0 ? '#065f46' : '#991b1b' }};">
                                            {{ number_format($balance->available, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        {{ __('Aucun solde disponible') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Dernières demandes -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> {{ __('Mes dernières demandes') }}</h5>
                <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-sm" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 4px;">
                    {{ __('Voir tout') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Période') }}</th>
                                <th>{{ __('Durée') }}</th>
                                <th>{{ __('Statut') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests->take(5) as $request)
                                <tr>
                                    <td>{{ $request->leaveType->name ?? 'N/A' }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        {{ __('Aucune demande de congé') }}
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