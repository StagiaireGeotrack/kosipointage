{{-- resources/views/leave_balances/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-wallet2"></i> {{ __('Gestion des Soldes') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('leave-balances.initialize') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> {{ __('Initialiser un solde') }}
                </a>
                <a href="{{ route('leave-balances.export') }}" class="btn btn-info">
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> {{ __('Valider') }}
                                </button>
                                <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
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
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Employé') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Siège') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Type') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Période') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Acquis') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Pris') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Restant') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary text-end">{{ __('Actions') }}</th>
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
                                        {{ $balance->employee->siege->Nom ?? 'N/A' }}
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge" style="background-color: {{ $balance->leaveType->color ?? '#6c757d' }}">
                                            {{ $balance->leaveType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <small>{{ $balance->period->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="align-middle text-end">
                                        <strong>{{ number_format($balance->total_entitled, 2) }}</strong>
                                    </td>
                                    <td class="align-middle text-end">
                                        <span class="text-danger">{{ number_format($balance->total_taken, 2) }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <span class="badge bg-{{ $balance->remaining > 0 ? 'success' : ($balance->remaining == 0 ? 'secondary' : 'danger') }} fs-6">
                                            {{ number_format($balance->remaining, 2) }}
                                        </span>
                                        <br>
                                        @php
                                            $available = $balance->remaining - $balance->total_pending;
                                        @endphp
                                        <small class="text-muted">Disponible: {{ number_format($available, 2) }}</small>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#adjustModal{{ $balance->id }}"
                                                    title="Ajuster">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="{{ route('leave-balances.transactions', $balance->id) }}" 
                                               class="btn btn-sm btn-info" title="Transactions">
                                                <i class="bi bi-clock-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal d'ajustement -->
                                <div class="modal fade" id="adjustModal{{ $balance->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('Ajuster le solde') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('leave-balances.adjust', $balance->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>{{ $balance->employee->Nom ?? 'N/A' }}</strong> - {{ $balance->leaveType->name ?? 'N/A' }}</p>
                                                    <p class="text-muted">Solde actuel: <strong>{{ number_format($balance->remaining, 2) }}</strong></p>

                                                    <div class="mb-3">
                                                        <label for="amount" class="form-label">{{ __('Montant') }} <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.5" name="amount" id="amount" class="form-control" required>
                                                        <small class="text-muted">Positif = ajouter, Négatif = retirer</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="description" class="form-label">{{ __('Motif') }} <span class="text-danger">*</span></label>
                                                        <input type="text" name="description" id="description" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                                    <button type="submit" class="btn btn-primary">{{ __('Ajuster') }}</button>
                                                </div>
                                            </form>
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