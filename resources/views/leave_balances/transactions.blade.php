{{-- resources/views/leave_balances/transactions.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-clock-history"></i> {{ __('Historique des transactions') }}
            </h2>
            <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>{{ $balance->employee->Nom ?? 'N/A' }}</strong> - 
                    {{ $balance->leaveType->name ?? 'N/A' }} - 
                    {{ $balance->period->name ?? 'N/A' }}
                    <br>
                    Solde actuel: <strong>{{ number_format($balance->remaining, 2) }}</strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Date') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Type') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Montant') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Description') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Créé par') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="align-middle">
                                        {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-{{ $transaction->type === 'opening' ? 'primary' : ($transaction->type === 'credit' ? 'success' : ($transaction->type === 'debit' ? 'danger' : ($transaction->type === 'carryover' ? 'info' : 'warning'))) }}">
                                            {{ $transaction->type_label }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        {{ $transaction->description ?? '-' }}
                                        @if($transaction->reference_id)
                                            <br><small class="text-muted">Réf: #{{ $transaction->reference_id }}</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $transaction->creator->Nom ?? 'Système' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <p>{{ __('Aucune transaction') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(isset($transactions) && method_exists($transactions, 'links'))
                    <div class="mt-3">
                        {{ $transactions->links('pagination.custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>