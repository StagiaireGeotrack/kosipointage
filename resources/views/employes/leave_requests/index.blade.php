<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0" style="font-family: 'Inter', sans-serif; font-weight: 600; color: #2d3748;">
                <i class="bi bi-list-ul" style="color: #5a7d8a;"></i> {{ __('Mes demandes de congé') }}
            </h2>
            <a href="{{ route('employe.leave-requests.create') }}" class="btn" style="background: linear-gradient(135deg, #5a7d8a, #4a6a78); color: #fff; border: none; border-radius: 10px; padding: 8px 20px; font-weight: 500; font-size: 0.9rem; transition: all 0.3s; box-shadow: 0 2px 8px rgba(90, 125, 138, 0.25);">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle demande') }}
            </a>
        </div>
    </x-slot>

    <div class="p-3" style="background: #f5f7fa; min-height: 100vh;">
        <!-- ============================================ -->
        <!-- SECTION SOLDES DE CONGÉS                      -->
        <!-- ============================================ -->
        @if(isset($balances) && $balances->isNotEmpty())
            <div class="card border-0 mb-4" style="border-radius: 16px; background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 style="font-family: 'Inter', sans-serif; font-weight: 600; color: #2d3748; font-size: 0.95rem; margin: 0;">
                            <i class="bi bi-wallet2" style="color: #5a7d8a;"></i> {{ __('Mes soldes de congés') }}
                        </h6>
                        <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 500; font-size: 0.7rem;">
                            <i class="bi bi-calendar3"></i> {{ __('Période en cours') }}
                        </span>
                    </div>
                    
                    <div class="row g-3">
                        @foreach($balances as $balance)
                            <div class="col-md-3 col-sm-6">
                                <div class="border rounded-3 p-3" style="background: #f7fafc; border-color: #edf2f7 !important; transition: all 0.2s;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span style="font-size: 0.7rem; color: #718096; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">
                                                {{ $balance->leaveType->name ?? 'N/A' }}
                                            </span>
                                            <div style="font-size: 1.5rem; font-weight: 700; color: #2d3748; margin-top: 2px;">
                                                {{ number_format($balance->balance ?? 0, 1) }}
                                                <span style="font-size: 0.8rem; font-weight: 400; color: #718096;">jours</span>
                                            </div>
                                        </div>
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: {{ $balance->leaveType->color ?? '#e2e8f0' }}20; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-calendar-check" style="color: {{ $balance->leaveType->color ?? '#5a7d8a' }};"></i>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <div class="progress" style="height: 4px; border-radius: 4px; background: #edf2f7;">
                                            @php
                                                $maxBalance = 30; // Valeur par défaut, à ajuster selon vos besoins
                                                $percentage = min(($balance->balance / $maxBalance) * 100, 100);
                                            @endphp
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $percentage }}%; background: {{ $balance->leaveType->color ?? '#5a7d8a' }}; border-radius: 4px;" 
                                                 aria-valuenow="{{ $balance->balance }}" aria-valuemin="0" aria-valuemax="{{ $maxBalance }}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <span style="font-size: 0.6rem; color: #a0aec0;">Utilisé: {{ number_format($balance->used ?? 0, 1) }}</span>
                                            <span style="font-size: 0.6rem; color: #a0aec0;">Restant: {{ number_format($balance->balance ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- ============================================ -->
        <!-- LISTE DES DEMANDES                           -->
        <!-- ============================================ -->
        <div class="card border-0" style="border-radius: 16px; background: #ffffff; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
            <div class="card-body p-4">
                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none; background: #f0fff4; color: #276749;">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; border: none; background: #fff5f5; color: #9b2c2c;">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Formulaire de recherche et filtres -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="input-group" style="border-radius: 8px; overflow: hidden;">
                            <span class="input-group-text" style="background: #f7fafc; border: 1px solid #e2e8f0; border-right: none; color: #a0aec0;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par type, motif..." style="border-color: #e2e8f0; font-size: 0.9rem;">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="dateFrom" class="form-control" placeholder="Date de début" style="border-color: #e2e8f0; font-size: 0.9rem;">
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="dateTo" class="form-control" placeholder="Date de fin" style="border-color: #e2e8f0; font-size: 0.9rem;">
                    </div>
                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select" style="border-color: #e2e8f0; font-size: 0.9rem;">
                            <option value="all">Tous les statuts</option>
                            <option value="draft">Brouillon</option>
                            <option value="pending">En attente</option>
                            <option value="approved">Approuvé</option>
                            <option value="rejected">Refusé</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div>
                </div>

                <!-- Résultats -->
                <div class="table-responsive">
                    <table class="table table-hover" id="leaveTable" style="border-collapse: separate; border-spacing: 0 6px;">
                        <thead>
                            <tr style="background: #f7fafc; border-radius: 10px;">
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; border-radius: 10px 0 0 10px;">{{ __('Type') }}</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Période') }}</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Durée') }}</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Statut') }}</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">{{ __('Date') }}</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; border-radius: 0 10px 10px 0;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr class="leave-row" data-status="{{ $request->status }}" style="background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-radius: 10px; transition: all 0.2s;">
                                    <td style="padding: 10px 16px; border-radius: 10px 0 0 10px;">
                                        <span class="badge" style="background: {{ $request->leaveType->color ?? '#e2e8f0' }}; color: {{ $request->leaveType->color ? '#fff' : '#4a5568' }}; border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                                            {{ $request->leaveType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 16px; color: #2d3748; font-size: 0.9rem;">
                                        <i class="bi bi-calendar2" style="color: #a0aec0; font-size: 0.8rem;"></i>
                                        {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 10px 16px; color: #2d3748; font-weight: 500; font-size: 0.9rem;">
                                        {{ number_format($request->duration, 2) }} {{ $request->duration > 1 ? 'jours' : 'jour' }}
                                    </td>
                                    <td style="padding: 10px 16px;">
                                        @php
                                            $statusBadge = match($request->status) {
                                                'draft' => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => 'Brouillon'],
                                                'pending' => ['bg' => '#fefcbf', 'color' => '#975a16', 'text' => 'En attente'],
                                                'approved' => ['bg' => '#c6f6d5', 'color' => '#276749', 'text' => 'Approuvé'],
                                                'rejected' => ['bg' => '#fed7d7', 'color' => '#9b2c2c', 'text' => 'Refusé'],
                                                'cancelled' => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => 'Annulé'],
                                                default => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => $request->status],
                                            };
                                        @endphp
                                        <span class="badge" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                                            {{ $statusBadge['text'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 16px; color: #718096; font-size: 0.85rem;">
                                        {{ $request->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td style="padding: 10px 16px; border-radius: 0 10px 10px 0;">
                                        <div class="d-flex gap-1 flex-wrap">
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('employe.leave-requests.show', $request->id) }}" 
                                               class="btn btn-sm" 
                                               style="background: #edf2f7; color: #4a5568; border: none; border-radius: 6px; padding: 4px 12px; transition: all 0.2s;"
                                               title="Voir les détails">
                                                <i class="bi bi-eye"></i> Voir
                                            </a>

                                            <!-- Boutons pour les brouillons -->
                                            @if($request->status == 'draft')
                                                <a href="{{ route('employe.leave-requests.edit', $request->id) }}" 
                                                   class="btn btn-sm" 
                                                   style="background: #fefcbf; color: #975a16; border: none; border-radius: 6px; padding: 4px 12px; transition: all 0.2s;"
                                                   title="Modifier la demande">
                                                    <i class="bi bi-pencil"></i> Modifier
                                                </a>
                                                
                                                <form action="{{ route('employe.leave-requests.submit', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm" style="background: #c6f6d5; color: #276749; border: none; border-radius: 6px; padding: 4px 12px; transition: all 0.2s;" title="Soumettre la demande">
                                                        <i class="bi bi-send"></i> Soumettre
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('employe.leave-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm" style="background: #fed7d7; color: #9b2c2c; border: none; border-radius: 6px; padding: 4px 12px; transition: all 0.2s;" onclick="return confirm('Supprimer cette demande ?')" title="Supprimer la demande">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Bouton Annuler pour les approuvés -->
                                            @if($request->status == 'approved')
                                                <form action="{{ route('employe.leave-requests.cancel-approved', $request->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm" style="background: #fefcbf; color: #975a16; border: none; border-radius: 6px; padding: 4px 12px;" onclick="return confirm('Annuler ce congé validé ?')" title="Annuler le congé">
                                                        <i class="bi bi-x-circle"></i> Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4" style="color: #a0aec0;">
                                        <i class="bi bi-inbox fs-3 d-block mb-2" style="color: #cbd5e0;"></i>
                                        <p style="font-family: 'Inter', sans-serif;">{{ __('Aucune demande de congé') }}</p>
                                        <a href="{{ route('employe.leave-requests.create') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #5a7d8a, #4a6a78); color: #fff; border: none; border-radius: 8px; padding: 6px 20px;">
                                            <i class="bi bi-plus-circle"></i> {{ __('Faire une demande') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa !important;
            font-family: 'Inter', sans-serif !important;
        }
        .table-hover tbody tr {
            transition: all 0.2s ease;
            cursor: default;
        }
        .table-hover tbody tr:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
            transform: translateX(4px);
        }
        .btn-sm {
            transition: all 0.2s ease;
        }
        .btn-sm:hover {
            transform: translateY(-1px);
            opacity: 0.85;
        }
        input.form-control, select.form-select {
            border-radius: 8px !important;
            border-color: #e2e8f0 !important;
            font-size: 0.9rem;
        }
        input.form-control:focus, select.form-select:focus {
            border-color: #5a7d8a !important;
            box-shadow: 0 0 0 3px rgba(90, 125, 138, 0.1) !important;
        }
        /* Animation d'apparition */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card {
            animation: fadeIn 0.5s ease forwards;
        }
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f5f7fa;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            const statusFilter = document.getElementById('statusFilter');
            const rows = document.querySelectorAll('.leave-row');

            function filterTable() {
                const search = searchInput.value.toLowerCase();
                const from = dateFrom.value;
                const to = dateTo.value;
                const status = statusFilter.value;

                rows.forEach(row => {
                    let show = true;
                    
                    if (search) {
                        const text = row.textContent.toLowerCase();
                        if (!text.includes(search)) {
                            show = false;
                        }
                    }

                    if (status !== 'all' && show) {
                        const rowStatus = row.getAttribute('data-status');
                        if (rowStatus !== status) {
                            show = false;
                        }
                    }

                    if (show && (from || to)) {
                        const dateRange = row.querySelector('.date-range');
                        if (dateRange) {
                            const dates = dateRange.textContent.split(' - ');
                            if (dates.length === 2) {
                                const start = dates[0].trim();
                                const end = dates[1].trim();
                                const startParts = start.split('/');
                                const endParts = end.split('/');
                                if (startParts.length === 3 && endParts.length === 3) {
                                    const startDate = startParts[2] + '-' + startParts[1] + '-' + startParts[0];
                                    const endDate = endParts[2] + '-' + endParts[1] + '-' + endParts[0];
                                    
                                    if (from && startDate < from) {
                                        show = false;
                                    }
                                    if (to && endDate > to) {
                                        show = false;
                                    }
                                }
                            }
                        }
                    }

                    row.style.display = show ? '' : 'none';
                });
            }

            searchInput.addEventListener('keyup', filterTable);
            dateFrom.addEventListener('change', filterTable);
            dateTo.addEventListener('change', filterTable);
            statusFilter.addEventListener('change', filterTable);
        });
    </script>
    @endpush
</x-app-layout>