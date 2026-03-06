{{-- resources/views/activity-logs/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Logs d\'activité') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Filtres --}}
                <form action="{{ route('activity-logs.index') }}" method="GET" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Date début') }}</label>
                            <input type="date" name="date_from" class="form-control"
                                   value="{{ $filters['date_from'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Date fin') }}</label>
                            <input type="date" name="date_to" class="form-control"
                                   value="{{ $filters['date_to'] ?? '' }}">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Email utilisateur') }}</label>
                            <input type="text" name="user_email" class="form-control"
                                   value="{{ $filters['user_email'] ?? '' }}" placeholder="admin@...">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Action') }}</label>
                            <select name="action" class="form-select">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach ($actions as $act)
                                    <option value="{{ $act }}"
                                        {{ ($filters['action'] ?? '') === $act ? 'selected' : '' }}>
                                        {{ $act }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Entité') }}</label>
                            <select name="model_type" class="form-select">
                                <option value="">{{ __('Toutes') }}</option>
                                @foreach ($modelTypes as $mt)
                                    <option value="{{ $mt }}"
                                        {{ ($filters['model_type'] ?? '') === $mt ? 'selected' : '' }}>
                                        {{ $mt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label">{{ __('Siège') }}</label>
                            <select name="SiegeID" class="form-select">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach ($sieges as $siege)
                                    <option value="{{ $siege->ID }}"
                                        {{ ($filters['SiegeID'] ?? '') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Valider') }}</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                            <a href="{{ route('activity-logs.export.csv') . (count(array_filter($filters)) ? '?' . http_build_query(array_filter($filters)) : '') }}"
                               class="btn btn-success">
                                {{ __('Export CSV') }}
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Compteur --}}
                <p class="text-muted mb-2">
                    {{ $logs->total() }} {{ __('résultat(s) trouvé(s)') }}
                </p>

                {{-- Tableau --}}
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Date / Heure') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Utilisateur') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Rôle') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Action') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Entité') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Détails') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('IP') }}</th>
                                <th class="text-uppercase small fw-semibold text-secondary">{{ __('Siège') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td>{{ $log->user_email }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($log->user_role) {
                                                'superadmin'   => 'bg-danger',
                                                'simple_admin' => 'bg-primary',
                                                'seller'       => 'bg-success',
                                                default        => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $log->user_role ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $actionClass = match(true) {
                                                str_starts_with($log->action, 'login_failed') => 'text-danger fw-bold',
                                                str_starts_with($log->action, 'delete')       => 'text-danger',
                                                str_starts_with($log->action, 'create')       => 'text-success',
                                                str_starts_with($log->action, 'update')       => 'text-warning',
                                                str_starts_with($log->action, 'export')       => 'text-info',
                                                default                                        => '',
                                            };
                                        @endphp
                                        <span class="{{ $actionClass }}">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        @if ($log->model_type)
                                            {{ $log->model_type }}
                                            @if ($log->model_id)
                                                <small class="text-muted">#{{ $log->model_id }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->model_label ?? $log->description ?? '—' }}</td>
                                    <td class="text-nowrap">{{ $log->ip_address }}</td>
                                    <td>
                                        @if ($log->SiegeID)
                                            {{ $sieges->firstWhere('ID', $log->SiegeID)?->Nom ?? $log->SiegeID }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        {{ __('Aucun log trouvé.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($logs->hasPages())
                <div class="mt-3">
                    {{ $logs->links("pagination.custom") }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
