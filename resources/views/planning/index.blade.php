@extends('layouts.app')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    .planning-scope {
        font-family: 'Roboto', sans-serif;
    }
    .fc-event .fc-event-title {
        font-size: 12px !important;
    }
    .event-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        color: white;
        font-weight: 700;
        font-size: 10px;
        flex-shrink: 0;
        margin-right: 4px;
    }
    .employee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }
    .schedule-item {
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .schedule-item:hover {
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .bg-work { background: #DCFCE7; border-color: #86EFAC; color: #166534; }
    .bg-pause { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-formation { background: #EDE9FE; border-color: #C4B5FD; color: #5B21B6; }
    .bg-conge { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-rtt { background: #FCE7F3; border-color: #F9A8D4; color: #9D174D; }
    .bg-mission { background: #FFEDD5; border-color: #FDBA74; color: #9A3412; }
    .bg-maladie { background: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
    .bg-absence { background: #DBEAFE; border-color: #93C5FD; color: #1E40AF; }
    .bg-rest { background: #F1F5F9; border-color: #CBD5E1; color: #475569; }
    
    .sticky-left {
        position: sticky;
        left: 0;
        z-index: 10;
    }
</style>
@endpush

@section('content')
@php
    $currentView = request('view', 'timeGridWeek');
    $currentOffset = (int) request('offset', 0);
@endphp

<div class="planning-scope flex flex-col h-full bg-slate-50 text-slate-800 -m-3">
    <!-- En-tête -->
    <header class="bg-white border-b border-slate-200 p-4 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">📅 Planning des employés</h1>
                <p class="text-xs text-slate-500">
                    Vue {{ $currentView === 'dayGridDay' ? 'Jour' : ($currentView === 'dayGridMonth' ? 'Mois' : 'Semaine') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100 rounded-lg border border-slate-200 p-0.5">
                    <button class="p-1.5 hover:bg-white rounded-md transition" id="prev-btn">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="flex items-center gap-2 px-3 text-xs font-semibold" id="week-label">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                        @if($currentView === 'dayGridDay')
                            {{ $days[0]['date'] ?? '--' }}
                        @elseif($currentView === 'dayGridMonth')
                            {{ $monthLabel ?? ($days[0]['date'] ?? '--') }}
                        @else
                            {{ $days[0]['date'] ?? '--' }} – {{ $days[count($days)-1]['date'] ?? '--' }}
                        @endif
                    </div>
                    <button class="p-1.5 hover:bg-white rounded-md transition" id="next-btn">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>

                <button class="px-3 py-1.5 bg-slate-100 text-xs font-semibold rounded-lg border border-slate-200 hover:bg-slate-200" id="today-btn">
                    Aujourd'hui
                </button>

                <div class="flex bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    <button class="px-3 py-1.5 rounded-md view-btn {{ $currentView === 'dayGridDay' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}" data-view="dayGridDay">Jour</button>
                    <button class="px-3 py-1.5 rounded-md view-btn {{ $currentView === 'timeGridWeek' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}" data-view="timeGridWeek">Semaine</button>
                    <button class="px-3 py-1.5 rounded-md view-btn {{ $currentView === 'dayGridMonth' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}" data-view="dayGridMonth">Mois</button>
                </div>

                <a href="{{ route('planning.create') }}" class="flex items-center gap-2 px-3.5 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-blue-700">
                    <i data-lucide="plus" class="w-4 h-4"></i> Créer un planning
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            <div class="flex flex-wrap items-center gap-2 flex-1">
                <select id="service-filter" class="border border-slate-200 rounded-lg text-xs font-medium px-3 py-2 bg-white flex-1 min-w-[140px] max-w-[180px]">
                    <option value="">Tous les services</option>
                    @if(isset($servicesData))
                        @foreach($servicesData as $service)
                            <option value="{{ $service['id'] }}">{{ $service['name'] }}</option>
                        @endforeach
                    @endif
                </select>

                <div class="relative flex-1 min-w-[180px] max-w-xs">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                    <input type="text" id="employee-search" placeholder="Rechercher un employé..." class="pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs w-full focus:outline-none focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="flex items-center gap-2 overflow-x-auto text-xs pt-1 pb-1 flex-wrap">
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-green-50 text-green-700 border border-green-200 font-medium whitespace-nowrap">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Travail
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 font-medium whitespace-nowrap">
                <i data-lucide="coffee" class="w-3.5 h-3.5"></i> Pause déjeuner
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 font-medium whitespace-nowrap">
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Formation
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-600 border border-amber-200 font-medium whitespace-nowrap">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Congé
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-pink-50 text-pink-700 border border-pink-200 font-medium whitespace-nowrap">
                <i data-lucide="hourglass" class="w-3.5 h-3.5"></i> RTT
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-orange-50 text-orange-700 border border-orange-200 font-medium whitespace-nowrap">
                <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Déplacement
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-rose-50 text-rose-700 border border-rose-200 font-medium whitespace-nowrap">
                <i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i> Maladie
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 font-medium whitespace-nowrap">
                <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Absence
            </span>
            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 font-medium whitespace-nowrap">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i> Repos
            </span>
        </div>
    </header>

    <!-- Planning Content -->
    <div class="flex-1 overflow-auto p-4 flex gap-4">
        <!-- Main Planning Table -->
        <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full border-collapse text-left text-xs" id="planning-table">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 font-semibold">
                            <th class="p-3 w-56 sticky-left bg-slate-50">Employé</th>
                            @if(isset($days))
                                @foreach($days as $day)
                                <th class="p-3 text-center border-l border-slate-200 min-w-[130px]">
                                    {{ $day['short'] }}
                                    <span class="block text-[10px] font-normal text-slate-400">{{ $day['date'] }}</span>
                                </th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="planning-body">
                        @if(isset($servicesData) && count($servicesData) > 0)
                            @foreach($servicesData as $service)
                            <!-- Service Group Row -->
                            <tr class="bg-slate-50/80 font-bold text-slate-700 border-t border-b border-slate-200 service-group" data-service="{{ $service['id'] }}">
                                <td colspan="{{ count($days) + 1 }}" class="p-2.5 px-3 sticky-left bg-slate-50/80">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                                        <span>{{ $service['name'] }}</span>
                                        <span class="text-slate-400 font-normal">({{ $service['count'] }})</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Employee Schedule Rows -->
                            @foreach($service['employees'] as $emp)
                            <tr class="hover:bg-slate-50/50 employee-row" data-employee="{{ $emp['id'] }}" data-service="{{ $service['id'] }}">
                                <td class="p-3 align-top sticky-left bg-white">
                                    <div class="flex items-center gap-3">
                                        @if(filter_var($emp['avatar'] ?? '', FILTER_VALIDATE_URL))
                                            <img src="{{ $emp['avatar'] }}" class="w-8 h-8 rounded-full object-cover" onerror="this.style.display='none'">
                                        @endif
                                        <div class="employee-avatar" style="background: {{ $emp['color'] ?? '#3B82F6' }}">
                                            {{ $emp['initiales'] ?? '?' }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800 text-xs employee-name">{{ $emp['name'] }}</div>
                                            <div class="text-[10px] text-slate-400">{{ $emp['role'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                @foreach($days as $day)
                                <td class="p-1 border-l border-slate-100 align-top min-w-[130px]">
                                    @if(isset($emp['schedule'][$day['date']]))
                                        <div class="space-y-1">
                                            @foreach($emp['schedule'][$day['date']] as $item)
                                                @if($item['type'] === 'work')
                                                    <div class="schedule-item p-1 rounded bg-green-50 border border-green-200 text-green-800 text-[10px] font-medium flex items-center gap-1" data-id="{{ $item['id'] ?? '' }}" data-type="planning">
                                                        <i data-lucide="briefcase" class="w-3 h-3 text-green-600 flex-shrink-0"></i> {{ $item['time'] }}
                                                    </div>
                                                @elseif($item['type'] === 'pause')
                                                    <div class="schedule-item p-1 rounded bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-medium flex items-center gap-1" data-id="{{ $item['id'] ?? '' }}" data-type="planning">
                                                        <i data-lucide="coffee" class="w-3 h-3 text-amber-600 flex-shrink-0"></i> {{ $item['time'] }}
                                                    </div>
                                                @elseif($item['type'] === 'formation')
                                                    <div class="schedule-item p-2 rounded-lg bg-purple-100 border border-purple-200 text-purple-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-purple-600 flex-shrink-0"></i> {{ $item['title'] ?? 'Formation' }}
                                                        </div>
                                                        <div class="text-[10px] text-purple-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'conge')
                                                    <div class="schedule-item p-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-600 flex-shrink-0"></i> {{ $item['title'] ?? 'Congé' }}
                                                        </div>
                                                        <div class="text-[10px] text-amber-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'rtt')
                                                    <div class="schedule-item p-2 rounded-lg bg-pink-50 border border-pink-200 text-pink-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="hourglass" class="w-3.5 h-3.5 text-pink-600 flex-shrink-0"></i> {{ $item['title'] ?? 'RTT' }}
                                                        </div>
                                                        <div class="text-[10px] text-pink-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'deplacement' || $item['type'] === 'mission')
                                                    <div class="schedule-item p-2 rounded-lg bg-orange-50 border border-orange-200 text-orange-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="navigation" class="w-3.5 h-3.5 text-orange-600 flex-shrink-0"></i> {{ $item['title'] ?? 'Déplacement' }}
                                                        </div>
                                                        <div class="text-[10px] text-orange-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'maladie')
                                                    <div class="schedule-item p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-rose-600 flex-shrink-0"></i> {{ $item['title'] ?? 'Maladie' }}
                                                        </div>
                                                        <div class="text-[10px] text-rose-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'absence')
                                                    <div class="schedule-item p-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="user-x" class="w-3.5 h-3.5 text-blue-600 flex-shrink-0"></i> {{ $item['title'] ?? 'Absence' }}
                                                        </div>
                                                        <div class="text-[10px] text-blue-600">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'rest')
                                                    <div class="p-3 text-center text-slate-400 flex items-center justify-center gap-1 text-[11px]">
                                                        <i data-lucide="moon" class="w-3.5 h-3.5 flex-shrink-0"></i> Repos
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">
                                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                    <p>Aucun planning disponible</p>
                                    <p class="text-[10px]">Créez un planning ou configurez des horaires types</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-t border-slate-200 bg-slate-50 text-xs text-slate-500 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                Les horaires indiqués sont prévisionnels et seront comparés avec les pointages réels.
            </div>
        </div>

        <!-- Panneau de détail droite -->
        @include('planning.partials.details-panel')
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // ============================================================
        // FILTRES DYNAMIQUES (RECHERCHE + SERVICE)
        // ============================================================
        function filterTable() {
            var selectedService = $('#service-filter').val();
            var searchQuery = $('#employee-search').val().toLowerCase().trim();

            $('.service-group').each(function() {
                var serviceId = $(this).data('service');
                var $serviceRows = $('.employee-row[data-service="' + serviceId + '"]');
                var visibleCount = 0;

                if (selectedService && serviceId != selectedService) {
                    $(this).hide();
                    $serviceRows.hide();
                    return;
                }

                $serviceRows.each(function() {
                    var empName = $(this).find('.employee-name').text().toLowerCase();
                    if (searchQuery === '' || empName.includes(searchQuery)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount > 0) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('#service-filter').on('change', filterTable);
        $('#employee-search').on('keyup input', filterTable);

        // ============================================================
        // NAVIGATION (JOUR / SEMAINE / MOIS + FLECHES)
        // ============================================================
        var currentView = "{{ $currentView }}";
        var currentOffset = {{ $currentOffset }};

        function navigate(view, offset) {
            var url = new URL(window.location.href);
            url.searchParams.set('view', view);
            url.searchParams.set('offset', offset);
            window.location.href = url.toString();
        }

        $('#prev-btn').on('click', function() {
            navigate(currentView, currentOffset - 1);
        });

        $('#next-btn').on('click', function() {
            navigate(currentView, currentOffset + 1);
        });

        $('#today-btn').on('click', function() {
            navigate(currentView, 0);
        });

        $('.view-btn').on('click', function() {
            var newView = $(this).data('view');
            navigate(newView, 0);
        });

        // ============================================================
        // CHARGER LES DÉTAILS D'UN ÉVÉNEMENT (AJAX)
        // ============================================================
        $(document).on('click', '.schedule-item', function() {
            var $this = $(this);
            var content = $('#detail-content');
            var eventId = $this.data('id');
            var eventType = $this.data('type');

            if (eventId && eventType) {
                content.html('<div class="text-center py-8"><div class="spinner-border text-primary" role="status"></div><p class="text-xs text-slate-400 mt-2">Chargement...</p></div>');

                $.ajax({
                    url: '/planning/event-detail/' + eventId + '/' + eventType,
                    method: 'GET',
                    success: function(data) {
                        if (data && data.type) {
                            if (data.type === 'planning') {
                                content.html(`
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="employee-avatar" style="background: #3B82F6; width:48px; height:48px; font-size:20px;">
                                            ${data.avatar ? 'AN' : '?'}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">${data.employe || 'N/A'}</h4>
                                            <p class="text-xs text-slate-400">${data.role || 'N/A'}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3 text-xs">
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Date</span>
                                            <span class="col-span-2 font-semibold text-slate-700">${data.date || '-'}</span>
                                        </div>
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Horaires</span>
                                            <span class="col-span-2 font-semibold text-slate-700">${data.heure_debut || '-'} - ${data.heure_fin || '-'}</span>
                                        </div>
                                        ${data.pause_debut ? `
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Pause</span>
                                            <span class="col-span-2 font-semibold text-slate-700">${data.pause_debut} - ${data.pause_fin}</span>
                                        </div>
                                        ` : ''}
                                        ${data.commentaire ? `
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Commentaire</span>
                                            <span class="col-span-2 text-slate-600">${data.commentaire}</span>
                                        </div>
                                        ` : ''}
                                        <div class="grid grid-cols-3 items-center">
                                            <span class="text-slate-400 font-medium">Statut</span>
                                            <span class="col-span-2">
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> ${data.statut || 'Planifié'}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                `);
                            } else if (data.type === 'evenement') {
                                content.html(`
                                    <div class="flex items-center gap-2 text-${data.type_event === 'formation' ? 'purple' : data.type_event === 'deplacement' ? 'orange' : 'blue'}-600 font-bold text-xs mb-4">
                                        <i data-lucide="${data.type_event === 'formation' ? 'graduation-cap' : data.type_event === 'deplacement' ? 'navigation' : 'calendar'}" class="w-4 h-4 flex-shrink-0"></i>
                                        ${data.type_event || 'Événement'}
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm mb-2">${data.titre || 'Sans titre'}</h4>
                                    <div class="space-y-3 text-xs">
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Début</span>
                                            <span class="col-span-2 font-semibold text-slate-700">${data.debut || '-'}</span>
                                        </div>
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Fin</span>
                                            <span class="col-span-2 font-semibold text-slate-700">${data.fin || '-'}</span>
                                        </div>
                                        ${data.description ? `
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Description</span>
                                            <span class="col-span-2 text-slate-600">${data.description}</span>
                                        </div>
                                        ` : ''}
                                        ${data.employes ? `
                                        <div class="grid grid-cols-3">
                                            <span class="text-slate-400 font-medium">Employés</span>
                                            <span class="col-span-2 text-slate-600">${data.employes}</span>
                                        </div>
                                        ` : ''}
                                    </div>
                                `);
                            }
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        } else {
                            content.html(`
                                <div class="text-center py-8 text-slate-400">
                                    <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                    <p class="text-sm">Aucun détail disponible</p>
                                </div>
                            `);
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        }
                    },
                    error: function() {
                        content.html(`
                            <div class="text-center py-8 text-slate-400">
                                <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-red-300"></i>
                                <p class="text-sm text-red-600">Erreur de chargement</p>
                            </div>
                        `);
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                });
            }
        });
    });
</script>
@endpush