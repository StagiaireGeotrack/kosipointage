<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <!-- Titre à gauche -->
            <h2 class="fw-semibold fs-4 text-dark mb-0" style="font-family: 'Inter', sans-serif; font-weight: 600;">
                <i class="bi bi-grid-1x2-fill" style="color: #6c7a89;"></i> {{ __('Tableau de bord') }}
            </h2>
            <!-- Liens + Date à droite -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- ✅ LIEN POINTAGES -->
                <a href="{{ route('employe.pointages') }}" class="btn btn-sm" style="background: #f0fff4; color: #38a169; border: 1px solid #c6f6d5; border-radius: 8px; padding: 6px 16px; font-weight: 500; transition: all 0.2s;">
                    <i class="bi bi-clock-history"></i> Pointages
                </a>
                <!-- ✅ LIEN RAPPORT QUOTIDIEN -->
                <a href="{{ route('employe.rapports') }}" class="btn btn-sm" style="background: #ebf4ff; color: #4299e1; border: 1px solid #bee3f8; border-radius: 8px; padding: 6px 16px; font-weight: 500; transition: all 0.2s;">
                    <i class="bi bi-calendar-day"></i> Rapport quotidien
                </a>
                <!-- ✅ LIEN PROFIL -->
                <a href="{{ route('employe.profile') }}" class="btn btn-sm" style="background: #fefcbf; color: #975a16; border: 1px solid #f6e05e; border-radius: 8px; padding: 6px 16px; font-weight: 500; transition: all 0.2s;">
                    <i class="bi bi-person"></i> Profil
                </a>
                <!-- ✅ DATE -->
                <span class="badge" style="background: #eef2f7; color: #4a5568; padding: 8px 16px; border-radius: 8px; font-weight: 500; font-size: 0.85rem;">
                    <i class="bi bi-calendar3"></i> {{ date('d/m/Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="p-3" style="background: #f4f6f9; min-height: 100vh;">
        <!-- Salutation -->
        <div class="mb-4">
            <p style="color: #718096; font-size: 0.95rem;">
                <i class="bi bi-clock"></i> Aperçu de vos demandes de congé
            </p>
        </div>

        <!-- Cartes de statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1" style="font-weight: 400; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px; color: #a0aec0;">Total</h6>
                                <h3 class="mb-0" style="color: #2d3748; font-weight: 600; font-size: 2rem;">{{ $stats['total_requests'] ?? 0 }}</h3>
                            </div>
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #ebf4ff; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-text" style="color: #4299e1; font-size: 1.25rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 3px 10px; font-weight: 400; font-size: 0.7rem;">
                                <i class="bi bi-arrow-up-short"></i> +12%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1" style="font-weight: 400; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px; color: #a0aec0;">En attente</h6>
                                <h3 class="mb-0" style="color: #2d3748; font-weight: 600; font-size: 2rem;">{{ $stats['pending'] ?? 0 }}</h3>
                            </div>
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fffbeb; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-clock-history" style="color: #d69e2e; font-size: 1.25rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge" style="background: #fefcbf; color: #975a16; border-radius: 6px; padding: 3px 10px; font-weight: 400; font-size: 0.7rem;">
                                <i class="bi bi-hourglass"></i> En cours
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1" style="font-weight: 400; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px; color: #a0aec0;">Approuvés</h6>
                                <h3 class="mb-0" style="color: #2d3748; font-weight: 600; font-size: 2rem;">{{ $stats['approved'] ?? 0 }}</h3>
                            </div>
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #f0fff4; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-circle" style="color: #38a169; font-size: 1.25rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge" style="background: #c6f6d5; color: #276749; border-radius: 6px; padding: 3px 10px; font-weight: 400; font-size: 0.7rem;">
                                <i class="bi bi-check2"></i> Validés
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1" style="font-weight: 400; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.3px; color: #a0aec0;">Refusés</h6>
                                <h3 class="mb-0" style="color: #2d3748; font-weight: 600; font-size: 2rem;">{{ $stats['rejected'] ?? 0 }}</h3>
                            </div>
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fff5f5; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-x-circle" style="color: #e53e3e; font-size: 1.25rem;"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="badge" style="background: #fed7d7; color: #9b2c2c; border-radius: 6px; padding: 3px 10px; font-weight: 400; font-size: 0.7rem;">
                                <i class="bi bi-x"></i> Non validés
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="mb-0" style="color: #2d3748; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600;">
                            <i class="bi bi-graph-up" style="color: #718096;"></i> Évolution des demandes
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="requestsChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h5 class="mb-0" style="color: #2d3748; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600;">
                            <i class="bi bi-pie-chart" style="color: #718096;"></i> Répartition par type
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="typesChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION SOLDES -->
        @if(isset($balances) && $balances->isNotEmpty())
            <div class="card border-0 mt-4" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0" style="color: #2d3748; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600;">
                        <i class="bi bi-wallet2" style="color: #718096;"></i> {{ __('Mes soldes de congés') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover" style="border-collapse: separate; border-spacing: 0 6px;">
                            <thead>
                                <tr style="background: #f7fafc; border-radius: 8px;">
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; border-radius: 8px 0 0 8px;">{{ __('Type') }}</th>
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; text-align: right;">{{ __('Acquis') }}</th>
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; text-align: right;">{{ __('Pris') }}</th>
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; text-align: right;">{{ __('En attente') }}</th>
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; text-align: right;">{{ __('Restant') }}</th>
                                    <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px; border-radius: 0 8px 8px 0; text-align: right;">{{ __('Disponible') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($balances as $balance)
                                    <tr style="background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.03); border-radius: 8px; transition: all 0.2s;">
                                        <td style="padding: 10px 16px; border-radius: 8px 0 0 8px;">
                                            <span class="badge" style="background: {{ $balance->leaveType->color ?? '#e2e8f0' }}; color: {{ $balance->leaveType->color ? '#fff' : '#4a5568' }}; border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                                                {{ $balance->leaveType->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td style="padding: 10px 16px; text-align: right; color: #2d3748; font-weight: 500;">
                                            {{ number_format($balance->total_entitled ?? 0, 2) }}
                                        </td>
                                        <td style="padding: 10px 16px; text-align: right; color: #2d3748;">
                                            {{ number_format($balance->total_taken ?? 0, 2) }}
                                        </td>
                                        <td style="padding: 10px 16px; text-align: right; color: #d69e2e;">
                                            {{ number_format($balance->total_pending ?? 0, 2) }}
                                        </td>
                                        <td style="padding: 10px 16px; text-align: right; font-weight: 600; color: #2d3748;">
                                            {{ number_format($balance->remaining ?? 0, 2) }}
                                        </td>
                                        <td style="padding: 10px 16px; text-align: right; border-radius: 0 8px 8px 0;">
                                            <span style="color: {{ ($balance->available ?? 0) > 0 ? '#276749' : '#9b2c2c' }}; font-weight: 600;">
                                                {{ number_format($balance->available ?? 0, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3" style="color: #a0aec0;">
                                            <i class="bi bi-inbox fs-4 d-block mb-2" style="color: #cbd5e0;"></i>
                                            {{ __('Aucun solde disponible') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Calendrier -->
        <div class="card border-0 mt-4" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0" style="color: #2d3748; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-calendar3" style="color: #718096;"></i> Calendrier des congés
                </h5>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 400;">
                        <span style="display: inline-block; width: 10px; height: 10px; background: #38a169; border-radius: 4px; margin-right: 6px;"></span> Approuvé
                    </span>
                    <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 400;">
                        <span style="display: inline-block; width: 10px; height: 10px; background: #d69e2e; border-radius: 4px; margin-right: 6px;"></span> En attente
                    </span>
                    <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 400;">
                        <span style="display: inline-block; width: 10px; height: 10px; background: #e53e3e; border-radius: 4px; margin-right: 6px;"></span> Refusé
                    </span>
                    <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 400;">
                        <span style="display: inline-block; width: 10px; height: 10px; background: #a0aec0; border-radius: 4px; margin-right: 6px;"></span> Brouillon
                    </span>
                    <a href="{{ route('employe.leave-calendar.index') }}" class="btn btn-sm" style="background: #edf2f7; color: #4a5568; border: none; border-radius: 8px; padding: 4px 14px; font-weight: 500; font-size: 0.8rem;">
                        <i class="bi bi-arrows-expand"></i> Agrandir
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="dashboardCalendar"></div>
            </div>
        </div>

        <!-- Dernières demandes -->
        <div class="card border-0 mt-4" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #2d3748; font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 600;">
                    <i class="bi bi-clock-history" style="color: #718096;"></i> Dernières demandes
                </h5>
                <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-sm" style="background: #edf2f7; color: #4a5568; border: none; border-radius: 8px; padding: 4px 14px; font-weight: 500; font-size: 0.8rem;">
                    <i class="bi bi-eye"></i> Voir toutes
                </a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover" style="border-collapse: separate; border-spacing: 0 6px;">
                        <thead>
                            <tr>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Type</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Période</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Durée</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Statut</th>
                                <th style="padding: 10px 16px; color: #718096; font-weight: 500; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests->take(5) as $request)
                                <tr style="background: #f7fafc; border-radius: 8px; transition: all 0.2s;">
                                    <td style="padding: 10px 16px; border-radius: 8px 0 0 8px;">
                                        <span class="badge" style="background: #e2e8f0; color: #4a5568; border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                                            {{ $request->leaveType->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 16px; color: #2d3748;">
                                        <i class="bi bi-calendar2" style="color: #718096; font-size: 0.8rem;"></i>
                                        {{ $request->start_date->format('d/m/Y') }} - {{ $request->end_date->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 10px 16px; color: #2d3748;">
                                        {{ number_format($request->duration, 1) }} jour(s)
                                    </td>
                                    <td style="padding: 10px 16px;">
                                        @php
                                            $statusBadge = match($request->status) {
                                                'draft' => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => 'Brouillon', 'icon' => 'bi-pencil'],
                                                'pending' => ['bg' => '#fefcbf', 'color' => '#975a16', 'text' => 'En attente', 'icon' => 'bi-hourglass'],
                                                'approved' => ['bg' => '#c6f6d5', 'color' => '#276749', 'text' => 'Approuvé', 'icon' => 'bi-check-circle'],
                                                'rejected' => ['bg' => '#fed7d7', 'color' => '#9b2c2c', 'text' => 'Refusé', 'icon' => 'bi-x-circle'],
                                                'cancelled' => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => 'Annulé', 'icon' => 'bi-x-circle'],
                                                default => ['bg' => '#e2e8f0', 'color' => '#4a5568', 'text' => $request->status, 'icon' => 'bi-circle'],
                                            };
                                        @endphp
                                        <span class="badge" style="background: {{ $statusBadge['bg'] }}; color: {{ $statusBadge['color'] }}; border-radius: 6px; padding: 4px 12px; font-weight: 500;">
                                            <i class="bi {{ $statusBadge['icon'] }}" style="font-size: 0.7rem;"></i>
                                            {{ $statusBadge['text'] }}
                                        </span>
                                    </td>
                                    <td style="padding: 10px 16px; border-radius: 0 8px 8px 0;">
                                        <a href="{{ route('employe.leave-requests.show', $request->id) }}" 
                                           class="btn btn-sm" 
                                           style="background: #edf2f7; color: #4a5568; border: none; border-radius: 6px; padding: 4px 12px; transition: all 0.2s;">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4" style="color: #a0aec0;">
                                        <i class="bi bi-inbox fs-3 d-block mb-2" style="color: #cbd5e0;"></i>
                                        <p style="font-family: 'Inter', sans-serif;">Aucune demande de congé</p>
                                        <a href="{{ route('employe.leave-requests.create') }}" class="btn btn-sm" style="background: #4299e1; color: #fff; border: none; border-radius: 8px; padding: 6px 20px;">
                                            <i class="bi bi-plus-circle"></i> Faire une demande
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css">
    
    <style>
        body {
            background: #f4f6f9 !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        .card {
            transition: all 0.25s ease;
            border: 1px solid #edf2f7;
        }
        .card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.05) !important;
            border-color: #e2e8f0;
        }
        
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }
        .table-hover tbody tr:hover {
            background: #edf2f7 !important;
        }
        
        #dashboardCalendar {
            max-width: 100%;
            margin: 0 auto;
        }
        #dashboardCalendar .fc {
            font-family: 'Inter', sans-serif !important;
        }
        .fc-event-title {
            font-size: 0.7rem;
            font-weight: 500;
        }
        .fc-daygrid-day-events {
            min-height: 30px;
        }
        .fc-day-today {
            background-color: rgba(66, 153, 225, 0.04) !important;
        }
        .fc-daygrid-day-number {
            font-weight: 500;
            font-size: 0.85rem;
            color: #2d3748;
        }
        .fc-col-header-cell-cushion {
            font-weight: 600;
            color: #718096;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .fc-daygrid-day-frame {
            min-height: 60px !important;
        }
        .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
            min-height: 20px !important;
        }
        .fc-daygrid-day-events {
            min-height: 20px !important;
        }
        .fc .fc-daygrid-day-top {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .fc-event {
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 6px !important;
            padding: 2px 6px !important;
            border: none !important;
        }
        .fc-event:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }
        .fc-event-approved { background: #38a169 !important; }
        .fc-event-pending { background: #d69e2e !important; }
        .fc-event-rejected { background: #e53e3e !important; }
        .fc-event-draft { background: #a0aec0 !important; }
        .fc .fc-button {
            background: #edf2f7 !important;
            color: #4a5568 !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 6px 14px !important;
            font-weight: 500 !important;
            font-family: 'Inter', sans-serif !important;
            transition: all 0.2s ease !important;
        }
        .fc .fc-button:hover {
            background: #e2e8f0 !important;
            color: #2d3748 !important;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: #4299e1 !important;
            color: #fff !important;
        }
        .fc .fc-toolbar-title {
            font-family: 'Inter', sans-serif !important;
            color: #2d3748 !important;
            font-size: 1rem !important;
            font-weight: 600 !important;
        }
        .fc .fc-toolbar {
            flex-wrap: wrap !important;
            gap: 8px !important;
        }
        @media (max-width: 768px) {
            .fc .fc-toolbar {
                flex-direction: column !important;
                align-items: center !important;
            }
            .fc .fc-toolbar-chunk {
                display: flex !important;
                justify-content: center !important;
                width: 100% !important;
            }
        }
        
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f4f6f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card {
            animation: fadeIn 0.5s ease forwards;
        }
        .card:nth-child(1) { animation-delay: 0.05s; }
        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.15s; }
        .card:nth-child(4) { animation-delay: 0.2s; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // 1. GRAPHIQUES
            // ============================================
            
            const monthlyData = @json(array_values($monthlyStatsArray ?? array_fill(0, 12, 0)));
            const typeLabels = @json($typeLabels ?? []);
            const typeData = @json($typeData ?? []);
            
            const ctx1 = document.getElementById('requestsChart');
            if (ctx1) {
                new Chart(ctx1.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
                        datasets: [{
                            label: 'Demandes',
                            data: monthlyData,
                            borderColor: '#4299e1',
                            backgroundColor: 'rgba(66, 153, 225, 0.08)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#4299e1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: true,
                                labels: {
                                    font: { size: 12, family: "'Inter', sans-serif" },
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    color: '#4a5568'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { size: 11, family: "'Inter', sans-serif" },
                                    color: '#718096'
                                },
                                grid: {
                                    color: 'rgba(0,0,0,0.04)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: { size: 11, family: "'Inter', sans-serif" },
                                    color: '#718096'
                                }
                            }
                        }
                    }
                });
            }

            const ctx2 = document.getElementById('typesChart');
            if (ctx2) {
                if (typeLabels.length > 0 && typeData.length > 0) {
                    const colors = ['#4299e1', '#48bb78', '#ed8936', '#9f7aea', '#fc8181', '#38b2ac'];
                    const backgroundColors = typeData.map((_, i) => colors[i % colors.length]);
                    
                    new Chart(ctx2.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: typeLabels,
                            datasets: [{
                                data: typeData,
                                backgroundColor: backgroundColors,
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { 
                                    position: 'bottom',
                                    labels: {
                                        font: { size: 11, family: "'Inter', sans-serif" },
                                        padding: 10,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        color: '#4a5568'
                                    }
                                }
                            },
                            cutout: '55%'
                        }
                    });
                } else {
                    const canvas = ctx2;
                    const ctx = canvas.getContext('2d');
                    canvas.width = canvas.parentElement.clientWidth;
                    canvas.height = 250;
                    ctx.fillStyle = '#a0aec0';
                    ctx.font = '14px "Inter", sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('Aucune donnée disponible', canvas.width/2, canvas.height/2);
                }
            }

            // ============================================
            // 2. CALENDRIER
            // ============================================
            
            const calendarEl = document.getElementById('dashboardCalendar');
            
            if (calendarEl) {
                try {
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'fr',
                        headerToolbar: {
                            left: 'prev,next',
                            center: 'title',
                            right: ''
                        },
                        buttonText: {
                            today: "Aujourd'hui",
                            month: "Mois"
                        },
                        events: '{{ route("employe.calendar.events") }}',
                        eventColor: '#4299e1',
                        eventTextColor: '#ffffff',
                        eventClick: function(info) {
                            if (info.event.extendedProps.url) {
                                window.location.href = info.event.extendedProps.url;
                            }
                        },
                        eventDidMount: function(info) {
                            const status = info.event.extendedProps.status;
                            const colors = {
                                'pending': '#d69e2e',
                                'approved': '#38a169',
                                'rejected': '#e53e3e',
                                'draft': '#a0aec0'
                            };
                            info.el.style.backgroundColor = colors[status] || '#4299e1';
                            info.el.style.borderColor = colors[status] || '#4299e1';
                            if (status) {
                                info.el.classList.add('fc-event-' + status);
                            }
                        },
                        eventContent: function(info) {
                            const statusLabels = {
                                'pending': 'En attente',
                                'approved': 'Approuvé',
                                'rejected': 'Refusé',
                                'draft': 'Brouillon'
                            };
                            return {
                                html: `
                                    <div class="fc-event-title">
                                        <strong>${info.event.title}</strong>
                                        <div style="font-size: 0.55rem; opacity: 0.85;">
                                            ${statusLabels[info.event.extendedProps.status] || info.event.extendedProps.status}
                                        </div>
                                    </div>
                                `
                            };
                        },
                        noEventsText: 'Aucun congé prévu',
                        height: 'auto',
                        contentHeight: 'auto',
                        firstDay: 1,
                        weekends: true,
                        displayEventTime: false,
                        allDaySlot: true,
                        eventsSet: function() {
                            setTimeout(function() { calendar.updateSize(); }, 100);
                        }
                    });
                    
                    calendar.render();
                    
                    let resizeTimeout;
                    window.addEventListener('resize', function() {
                        clearTimeout(resizeTimeout);
                        resizeTimeout = setTimeout(function() { calendar.updateSize(); }, 300);
                    });
                    
                } catch (error) {
                    console.error('Erreur calendrier:', error);
                    calendarEl.innerHTML = `
                        <div class="alert alert-warning" style="border-radius: 8px; border: none; background: #fffbeb; color: #975a16;">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Erreur de chargement du calendrier. 
                            <a href="{{ route('employe.leave-calendar.index') }}" style="color: #4299e1;">Voir en grand</a>
                        </div>
                    `;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>