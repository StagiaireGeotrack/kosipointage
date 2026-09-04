{{-- resources/views/leave_balances/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-wallet2"></i> {{ __('Gestion des Soldes') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('leave-balances.initialize') }}" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-size: 0.875rem;">
                    <i class="bi bi-plus-circle"></i> {{ __('Initialiser') }}
                </a>
                <a href="{{ route('leave-balances.import') }}" class="btn" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-size: 0.875rem;">
                    <i class="bi bi-upload"></i> {{ __('Importer') }}
                </a>
                <a href="{{ route('leave-balances.export') }}" class="btn" style="background-color: #7a8c8d; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-size: 0.875rem;">
                    <i class="bi bi-download"></i> {{ __('Exporter') }}
                </a>
            </div>
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
                <form action="{{ route('leave-balances.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-input-label for="site_id" :value="__('Siège')" />
                            <select id="site_id" name="site_id" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" {{ request('site_id') == $site->ID ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <select id="employee_id" name="employee_id" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->ID }}" {{ request('employee_id') == $employee->ID ? 'selected' : '' }}>
                                        {{ $employee->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 6px; padding: 6px 16px; font-size: 0.875rem;">
                                    <i class="bi bi-search"></i> {{ __('Valider') }}
                                </button>
                                <a href="{{ route('leave-balances.index') }}" class="btn" style="background-color: #d1d5db; color: #374151; border: none; border-radius: 6px; padding: 6px 16px; font-size: 0.875rem;">
                                    <i class="bi bi-arrow-counterclockwise"></i> {{ __('Réinitialiser') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Résumé des soldes -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card" style="background-color: #f3f4f6;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">{{ __('Total soldes') }}</h6>
                                <h3 class="mb-0">{{ $balances->total() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card" style="background-color: #f3f4f6;">
                            <div class="card-body text-center">
                                <h6 class="text-muted">{{ __('Employés') }}</h6>
                                <h3 class="mb-0">{{ $employees->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Employé') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Siège') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Type') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Période') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Acquis') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Pris') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Restant') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($balances as $balance)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $balance->employee->Nom ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">#{{ $balance->employee->num_mat ?? 'N/A' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge" style="background-color: #d1d5db; color: #374151; font-weight: 400;">{{ $balance->employee->siege->Nom ?? 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge" style="background-color: {{ $balance->leaveType->color ?? '#4f8a8b' }}; color: #fff;">
                                            {{ $balance->leaveType->name ?? 'N/A' }}
                                        </span>
                                        <br><small class="text-muted">{{ $balance->leaveType->code ?? '' }}</small>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $balance->period->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <strong>{{ number_format($balance->total_entitled, 2) }}</strong>
                                    </td>
                                    <td class="align-middle text-end">
                                        <span style="color: #b91c1c;">{{ number_format($balance->total_taken, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <span class="badge" style="background-color: {{ $balance->remaining > 0 ? '#4f8a8b' : ($balance->remaining == 0 ? '#d1d5db' : '#b91c1c') }}; color: #fff;">
                                            {{ number_format($balance->remaining, 2) }}
                                        </span>
                                        <br>
                                        @php
                                            $available = $balance->remaining - $balance->total_pending;
                                        @endphp
                                        <small class="text-muted">Disponible: {{ number_format($available, 2) }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            {{-- Bouton Ajuster --}}
                                            <button type="button" class="btn btn-sm" 
                                                    style="background-color: #d97706; color: #fff; border: none; border-radius: 4px; padding: 4px 12px; font-size: 0.75rem;"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#adjustModal{{ $balance->id }}"
                                                    title="Ajuster le solde manuellement">
                                                <i class="bi bi-pencil"></i>
                                                <span class="d-none d-md-inline">Ajuster</span>
                                            </button>

                                            {{-- Bouton Transactions --}}
                                            <a href="{{ route('leave-balances.transactions', $balance->id) }}" 
                                               class="btn btn-sm" 
                                               style="background-color: #5b7f95; color: #fff; border: none; border-radius: 4px; padding: 4px 12px; font-size: 0.75rem;"
                                               title="Voir l'historique des transactions">
                                                <i class="bi bi-clock-history"></i>
                                                <span class="d-none d-md-inline">Transactions</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal d'ajustement -->
                                <!-- Modal d'ajustement -->
<div class="modal fade" id="adjustModal{{ $balance->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Ajuster le solde') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>{{ $balance->employee->Nom ?? 'N/A' }}</strong> - {{ $balance->leaveType->name ?? 'N/A' }}</p>
                <p class="text-muted">Solde actuel: <strong>{{ number_format($balance->remaining, 2) }}</strong></p>

                <div class="row g-3">
                    {{-- Formulaire Ajouter (crédit) --}}
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 2px solid #22c55e;">
                            <div class="card-body text-center">
                                <h6 class="text-success mb-3"><i class="bi bi-plus-circle"></i> Ajouter des jours</h6>
                                <form method="POST" action="{{ route('leave-balances.adjust', $balance->id) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="credit">
                                    <div class="mb-2">
                                        <input type="number" step="0.5" name="amount" class="form-control text-center" placeholder="Montant" required min="0.5">
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="description" class="form-control" placeholder="Motif (ex: Rattrapage)" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-plus-circle"></i> Ajouter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Formulaire Retirer (débit) --}}
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 2px solid #ef4444;">
                            <div class="card-body text-center">
                                <h6 class="text-danger mb-3"><i class="bi bi-dash-circle"></i> Retirer des jours</h6>
                                <form method="POST" action="{{ route('leave-balances.adjust', $balance->id) }}">
                                    @csrf
                                    <input type="hidden" name="type" value="debit">
                                    <div class="mb-2">
                                        <input type="number" step="0.5" name="amount" class="form-control text-center" placeholder="Montant" required min="0.5">
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="description" class="form-control" placeholder="Motif (ex: Correction)" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="bi bi-dash-circle"></i> Retirer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
            </div>
        </div>
    </div>
</div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <p>{{ __('Aucun solde pour le moment') }}</p>
                                            <p class="small">{{ __('Commencez par initialiser les soldes des employés.') }}</p>
                                            <div class="mt-2">
                                                <a href="{{ route('leave-balances.initialize') }}" class="btn btn-sm" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 4px;">
                                                    <i class="bi bi-plus-circle"></i> {{ __('Initialiser un solde') }}
                                                </a>
                                                <a href="{{ route('leave-balances.import') }}" class="btn btn-sm" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 4px;">
                                                    <i class="bi bi-upload"></i> {{ __('Importer') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($balances) && method_exists($balances, 'links'))
                    <div class="mt-3">
                        {{ $balances->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>