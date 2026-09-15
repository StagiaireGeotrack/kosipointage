{{-- resources/views/planning/employe.blade.php --}}
@extends('layouts.app')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .planning-scope {
            font-family: 'Roboto', sans-serif;
        }

        .employee-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            color: white;
            flex-shrink: 0;
        }

        .schedule-item {
            transition: all 0.15s ease;
        }

        .schedule-item.has-event {
            cursor: pointer;
        }

        .schedule-item.has-event:hover {
            transform: scale(1.02);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sticky-left {
            position: sticky;
            left: 0;
            z-index: 10;
        }

        .legend-filter {
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
            opacity: 0.85;
        }

        .legend-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }

        .legend-filter.active {
            box-shadow: 0 0 0 2px #3B82F6, 0 2px 6px rgba(59, 130, 246, 0.3);
            opacity: 1;
            font-weight: 700;
        }

        .manager-badge {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
            font-size: 9px;
            padding: 1px 8px;
            border-radius: 10px;
            margin-left: 6px;
            font-weight: 600;
        }

        .me-badge {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            font-size: 9px;
            padding: 1px 8px;
            border-radius: 10px;
            margin-left: 6px;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="planning-scope flex flex-col h-full bg-slate-50 text-slate-800 -m-3">

        {{-- EN-TÊTE --}}
        <header class="bg-white border-b border-slate-200 p-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">
                        {{ $isManagerView ? '📅 Planning de mon service' : '📅 Mon planning' }}
                    </h1>
                    <p class="text-xs text-slate-500">
                        Vue {{ $view === 'day' ? 'Jour' : 'Semaine' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-slate-100 rounded-lg border border-slate-200 p-0.5">
                        <a href="{{ route('employe.planning.index', ['view' => $view, 'date' => $prevDate]) }}"
                            class="p-1.5 hover:bg-white rounded-md transition" title="Précédent">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                        <div class="flex items-center gap-2 px-3 text-xs font-semibold min-w-[180px] justify-center">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                            <span>{{ $periodLabel }}</span>
                        </div>
                        @if ($canGoNext)
                            <a href="{{ route('employe.planning.index', ['view' => $view, 'date' => $nextDate]) }}"
                                class="p-1.5 hover:bg-white rounded-md transition" title="Suivant">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @else
                            <span class="p-1.5 text-slate-300 cursor-not-allowed" title="Pas de semaine future">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('employe.planning.index', ['view' => $view, 'date' => $todayDate]) }}"
                        class="px-3 py-1.5 bg-slate-100 text-xs font-semibold rounded-lg border border-slate-200 hover:bg-slate-200">
                        Aujourd'hui
                    </a>

                    <div class="flex bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        <a href="{{ route('employe.planning.index', ['view' => 'day', 'date' => $pivotDate]) }}"
                            class="px-3 py-1.5 rounded-md {{ $view === 'day' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Jour</a>
                        <a href="{{ route('employe.planning.index', ['view' => 'week', 'date' => $pivotDate]) }}"
                            class="px-3 py-1.5 rounded-md {{ $view === 'week' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Semaine</a>
                    </div>
                </div>
            </div>

            @if ($isManagerView)
                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                    <div class="flex flex-wrap items-center gap-2 flex-1">
                        <select id="service-filter"
                            class="border border-slate-200 rounded-lg text-xs font-medium px-3 py-2 bg-white flex-1 min-w-[140px] max-w-[180px]">
                            <option value="">Tous les services</option>
                            @foreach ($servicesData as $service)
                                <option value="{{ $service['id'] }}">{{ $service['name'] }}</option>
                            @endforeach
                        </select>

                        <div class="relative flex-1 min-w-[180px] max-w-xs">
                            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                            <input type="text" id="employee-search" placeholder="Rechercher un employé..."
                                class="pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs w-full focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-2 overflow-x-auto text-xs pt-1 pb-1 flex-wrap">
                <span class="text-slate-400 font-medium mr-1">Filtrer par :</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-green-50 text-green-700 border border-green-200 font-medium whitespace-nowrap"
                    data-filter-type="work"><i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Travail</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 font-medium whitespace-nowrap"
                    data-filter-type="pause"><i data-lucide="coffee" class="w-3.5 h-3.5"></i> Pause</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 font-medium whitespace-nowrap"
                    data-filter-type="formation"><i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Formation</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-600 border border-amber-200 font-medium whitespace-nowrap"
                    data-filter-type="conge"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> Congé</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-pink-50 text-pink-700 border border-pink-200 font-medium whitespace-nowrap"
                    data-filter-type="rtt"><i data-lucide="hourglass" class="w-3.5 h-3.5"></i> RTT</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-orange-50 text-orange-700 border border-orange-200 font-medium whitespace-nowrap"
                    data-filter-type="deplacement"><i data-lucide="navigation" class="w-3.5 h-3.5"></i> Déplacement</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-rose-50 text-rose-700 border border-rose-200 font-medium whitespace-nowrap"
                    data-filter-type="maladie"><i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i> Maladie</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 font-medium whitespace-nowrap"
                    data-filter-type="absence"><i data-lucide="user-x" class="w-3.5 h-3.5"></i> Absence</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 font-medium whitespace-nowrap"
                    data-filter-type="rest"><i data-lucide="moon" class="w-3.5 h-3.5"></i> Repos</span>
                <button id="clear-filter"
                    class="hidden ml-2 flex items-center gap-1 px-2.5 py-1 rounded bg-red-50 text-red-600 border border-red-200 font-medium text-[11px] hover:bg-red-100">
                    <i data-lucide="x" class="w-3 h-3"></i> Effacer filtre
                </button>
            </div>
        </header>

        {{-- CONTENU --}}
        <div class="flex-1 overflow-auto p-4 flex gap-4">
            <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="overflow-x-auto flex-1">
                    <table class="w-full border-collapse text-left text-xs" id="planning-table">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 font-semibold">
                                <th class="p-3 w-56 sticky-left bg-slate-50">Employé</th>
                                @foreach ($days as $day)
                                    <th class="p-3 text-center border-l border-slate-200 min-w-[130px]">
                                        {{ $day['short'] }}
                                        <span
                                            class="block text-[10px] font-normal text-slate-400">{{ $day['date'] }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="planning-body">
                            @if (count($servicesData) > 0)
                                @foreach ($servicesData as $service)
                                    @if ($isManagerView)
                                        <tr class="bg-slate-50/80 font-bold text-slate-700 border-t border-b border-slate-200 service-group"
                                            data-service="{{ $service['id'] }}">
                                            <td colspan="{{ count($days) + 1 }}"
                                                class="p-2.5 px-3 sticky-left bg-slate-50/80">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                                                    <span>{{ $service['name'] }}</span>
                                                    <span
                                                        class="text-slate-400 font-normal">({{ $service['count'] }})</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach ($service['employees'] as $emp)
                                        @php
                                            $empTypes = [];
                                            foreach ($emp['schedule'] as $items) {
                                                foreach ($items as $item) {
                                                    $t = $item['type'] ?? '';
                                                    if ($t === 'work') {
                                                        $empTypes['work'] = true;
                                                    } elseif ($t === 'pause') {
                                                        $empTypes['pause'] = true;
                                                    } elseif ($t === 'conge') {
                                                        $empTypes['conge'] = true;
                                                    } elseif ($t === 'rtt') {
                                                        $empTypes['rtt'] = true;
                                                    } elseif ($t === 'maladie') {
                                                        $empTypes['maladie'] = true;
                                                    } elseif ($t === 'absence') {
                                                        $empTypes['absence'] = true;
                                                    } elseif ($t === 'formation') {
                                                        $empTypes['formation'] = true;
                                                    } elseif ($t === 'mission' || $t === 'deplacement') {
                                                        $empTypes['deplacement'] = true;
                                                    } elseif ($t === 'rest') {
                                                        $empTypes['rest'] = true;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 employee-row" data-employee="{{ $emp['id'] }}"
                                            data-service="{{ $service['id'] }}"
                                            data-types="{{ implode(',', array_keys($empTypes)) }}">
                                            <td class="p-3 align-top sticky-left bg-white">
                                                <div class="flex items-center gap-3">
                                                    <div class="employee-avatar"
                                                        style="background: {{ $emp['color'] ?? '#3B82F6' }}">
                                                        {{ $emp['initiales'] ?? '?' }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-slate-800 text-xs employee-name">
                                                            {{ $emp['name'] }}
                                                            @if ($emp['is_me'] ?? false)
                                                                <span class="me-badge">Moi</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-[10px] text-slate-400">
                                                            {{ $emp['role'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach ($days as $day)
                                                <td class="p-1 border-l border-slate-100 align-top min-w-[130px]">
                                                    @if (isset($emp['schedule'][$day['date']]))
                                                        <div class="space-y-1">
                                                            @foreach ($emp['schedule'][$day['date']] as $item)
                                                                @if ($item['type'] === 'work')
                                                                    <div class="schedule-item has-event p-1 rounded bg-green-50 border border-green-200 text-green-800 text-[10px] font-medium flex items-center gap-1"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="planning">
                                                                        <i data-lucide="briefcase"
                                                                            class="w-3 h-3 text-green-600 flex-shrink-0"></i>
                                                                        {{ $item['time'] }}
                                                                    </div>
                                                                @elseif($item['type'] === 'pause')
                                                                    <div class="schedule-item has-event p-1 rounded bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-medium flex items-center gap-1"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="planning">
                                                                        <i data-lucide="coffee"
                                                                            class="w-3 h-3 text-amber-600 flex-shrink-0"></i>
                                                                        {{ $item['time'] }}
                                                                    </div>
                                                                @elseif($item['type'] === 'formation')
                                                                    <div class="schedule-item has-event p-2 rounded-lg bg-purple-100 border border-purple-200 text-purple-900 text-center"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="evenement">
                                                                        <div
                                                                            class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                            <i data-lucide="graduation-cap"
                                                                                class="w-3.5 h-3.5 text-purple-600 flex-shrink-0"></i>
                                                                            {{ $item['title'] ?? 'Formation' }}
                                                                        </div>
                                                                        <div class="text-[10px] text-purple-600">
                                                                            {{ $item['sub'] ?? '' }}</div>
                                                                    </div>
                                                                @elseif($item['type'] === 'conge' || $item['type'] === 'rtt' || $item['type'] === 'maladie' || $item['type'] === 'absence')
                                                                    <div class="schedule-item has-event p-2 rounded-lg {{ $item['type'] === 'conge' ? 'bg-amber-50 border-amber-200 text-amber-900' : ($item['type'] === 'rtt' ? 'bg-pink-50 border-pink-200 text-pink-900' : ($item['type'] === 'maladie' ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-blue-50 border-blue-200 text-blue-900')) }} border text-center"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="conge">
                                                                        <div
                                                                            class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                            <i data-lucide="{{ $item['type'] === 'conge' ? 'calendar' : ($item['type'] === 'rtt' ? 'hourglass' : ($item['type'] === 'maladie' ? 'heart-pulse' : 'user-x')) }}"
                                                                                class="w-3.5 h-3.5 flex-shrink-0"></i>
                                                                            {{ $item['title'] ?? 'Congé' }}
                                                                        </div>
                                                                        <div class="text-[10px]">{{ $item['sub'] ?? '' }}
                                                                        </div>
                                                                    </div>
                                                                @elseif($item['type'] === 'deplacement' || $item['type'] === 'mission')
                                                                    <div class="schedule-item has-event p-2 rounded-lg bg-orange-50 border border-orange-200 text-orange-900 text-center"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="evenement">
                                                                        <div
                                                                            class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                            <i data-lucide="navigation"
                                                                                class="w-3.5 h-3.5 text-orange-600 flex-shrink-0"></i>
                                                                            {{ $item['title'] ?? 'Déplacement' }}
                                                                        </div>
                                                                        <div class="text-[10px] text-orange-600">
                                                                            {{ $item['sub'] ?? '' }}</div>
                                                                    </div>
                                                                @elseif($item['type'] === 'reunion')
                                                                    <div class="schedule-item has-event p-2 rounded-lg bg-blue-100 border border-blue-200 text-blue-900 text-center"
                                                                        data-id="{{ $item['id'] ?? '' }}"
                                                                        data-type="evenement">
                                                                        <div
                                                                            class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                            <i data-lucide="users"
                                                                                class="w-3.5 h-3.5 text-blue-600 flex-shrink-0"></i>
                                                                            {{ $item['title'] ?? 'Réunion' }}
                                                                        </div>
                                                                        <div class="text-[10px] text-blue-600">
                                                                            {{ $item['sub'] ?? '' }}</div>
                                                                    </div>
                                                                @elseif($item['type'] === 'rest')
                                                                    <div
                                                                        class="p-3 text-center text-slate-400 flex items-center justify-center gap-1 text-[11px]">
                                                                        <i data-lucide="moon"
                                                                            class="w-3.5 h-3.5 flex-shrink-0"></i> Repos
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
                                    <td colspan="{{ count($days) + 1 }}" class="text-center py-8 text-slate-400">
                                        <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                        <p>Aucun planning disponible</p>
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

            {{-- Panneau détail droite --}}
            @include('planning.partials.details-panel')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // ============================================================
            // FILTRES
            // ============================================================
            var activeTypeFilter = null;
            var activeService = '';
            var activeSearch = '';
            var clearBtn = document.getElementById('clear-filter');
            var serviceFilter = document.getElementById('service-filter');
            var searchInput = document.getElementById('employee-search');

            function applyFilters() {
                document.querySelectorAll('.employee-row').forEach(function(row) {
                    var empName = (row.querySelector('.employee-name')?.textContent || '').toLowerCase();
                    var empTypes = (row.dataset.types || '').split(',').filter(Boolean);
                    var rowService = row.dataset.service;

                    var matchType = (activeTypeFilter === null || empTypes.includes(activeTypeFilter));
                    var matchService = (activeService === '' || rowService === activeService);
                    var matchSearch = (activeSearch === '' || empName.includes(activeSearch));

                    row.style.display = (matchType && matchService && matchSearch) ? '' : 'none';
                });
                document.querySelectorAll('.service-group').forEach(function(group) {
                    var serviceId = group.dataset.service;
                    var visibleRows = document.querySelectorAll('.employee-row[data-service="' + serviceId +
                        '"]:not([style*="display: none"])');
                    group.style.display = visibleRows.length > 0 ? '' : 'none';
                });
            }

            document.querySelectorAll('.legend-filter').forEach(function(el) {
                el.addEventListener('click', function() {
                    var type = this.dataset.filterType;
                    if (activeTypeFilter === type) {
                        activeTypeFilter = null;
                        this.classList.remove('active');
                        if (clearBtn) clearBtn.classList.add('hidden');
                    } else {
                        activeTypeFilter = type;
                        document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove(
                            'active'));
                        this.classList.add('active');
                        if (clearBtn) clearBtn.classList.remove('hidden');
                    }
                    applyFilters();
                });
            });

            if (clearBtn) clearBtn.addEventListener('click', function() {
                activeTypeFilter = null;
                document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove('active'));
                this.classList.add('hidden');
                applyFilters();
            });

            if (serviceFilter) serviceFilter.addEventListener('change', function() {
                activeService = this.value;
                applyFilters();
            });
            if (searchInput) searchInput.addEventListener('keyup', function() {
                activeSearch = this.value.toLowerCase().trim();
                applyFilters();
            });

            // ============================================================
            // CLIC SUR UN CRÉNEAU → charge le détail
            // ============================================================
            document.addEventListener('click', function(e) {
                var target = e.target.closest('.schedule-item');
                if (!target) return;

                var eventId = target.dataset.id;
                var eventType = target.dataset.type;
                var content = document.getElementById('detail-content');

                if (!eventId || !eventType || !content) return;

                content.innerHTML =
                    '<div class="text-center py-8"><div class="spinner-border text-primary" role="status"></div><p class="text-xs text-slate-400 mt-2">Chargement...</p></div>';

                var url = '{{ url('employe/planning/event-detail') }}/' + encodeURIComponent(eventId) +
                    '/' + eventType;

                fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (!data || !data.type) {
                            content.innerHTML =
                                '<div class="text-center py-8 text-slate-400"><i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i><p class="text-sm">Aucun détail disponible</p></div>';
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                            return;
                        }

                        // ---------- PLANNING ----------
                        if (data.type === 'planning') {
                            content.innerHTML = `
                            <div class="flex items-center gap-3 mb-4">
                                <div class="employee-avatar" style="background: #3B82F6; width:48px; height:48px; font-size:20px;">
                                    ${(data.employe || '?').substring(0,1).toUpperCase()}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">${data.employe || 'N/A'}</h4>
                                    <p class="text-xs text-slate-400">${data.role || 'N/A'}</p>
                                </div>
                            </div>
                            <div class="space-y-3 text-xs">
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Date</span><span class="col-span-2 font-semibold text-slate-700">${data.date || '-'}</span></div>
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Horaires</span><span class="col-span-2 font-semibold text-slate-700">${data.heure_debut || '-'} - ${data.heure_fin || '-'}</span></div>
                                ${data.pause_debut ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Pause</span><span class="col-span-2 font-semibold text-slate-700">${data.pause_debut} - ${data.pause_fin}</span></div>` : ''}
                                ${data.commentaire ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Commentaire</span><span class="col-span-2 text-slate-600">${data.commentaire}</span></div>` : ''}
                                <div class="grid grid-cols-3 items-center">
                                    <span class="text-slate-400 font-medium">Statut</span>
                                    <span class="col-span-2"><span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> ${data.statut || 'Planifié'}</span></span>
                                </div>
                            </div>
                        `;
                        }
                        // ---------- ÉVÉNEMENT ----------
                        else if (data.type === 'evenement') {
                            var iconColor = data.type_event === 'formation' ? 'purple' : (data
                                .type_event === 'deplacement' ? 'orange' : 'blue');
                            var iconName = data.type_event === 'formation' ? 'graduation-cap' : (data
                                .type_event === 'deplacement' ? 'navigation' : 'calendar');
                            content.innerHTML = `
                            <div class="flex items-center gap-2 text-${iconColor}-600 font-bold text-xs mb-4">
                                <i data-lucide="${iconName}" class="w-4 h-4 flex-shrink-0"></i>
                                ${data.type_event || 'Événement'}
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm mb-2">${data.titre || 'Sans titre'}</h4>
                            <div class="space-y-3 text-xs">
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Début</span><span class="col-span-2 font-semibold text-slate-700">${data.debut || '-'}</span></div>
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Fin</span><span class="col-span-2 font-semibold text-slate-700">${data.fin || '-'}</span></div>
                                ${data.description ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Description</span><span class="col-span-2 text-slate-600">${data.description}</span></div>` : ''}
                                ${data.employes ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Employés</span><span class="col-span-2 text-slate-600">${data.employes}</span></div>` : ''}
                            </div>
                        `;
                        }
                        // ---------- CONGÉ ----------
                        else if (data.type === 'conge') {
                            content.innerHTML = `
                            <div class="flex items-center gap-2 text-amber-600 font-bold text-xs mb-4">
                                <i data-lucide="calendar" class="w-4 h-4 flex-shrink-0"></i>
                                ${data.type_conge || 'Congé'}
                            </div>
                            <h4 class="font-bold text-slate-800 text-sm mb-2">${data.employe || 'N/A'}</h4>
                            <div class="space-y-3 text-xs">
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Du</span><span class="col-span-2 font-semibold text-slate-700">${data.date_debut || '-'}</span></div>
                                <div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Au</span><span class="col-span-2 font-semibold text-slate-700">${data.date_fin || '-'}</span></div>
                                ${data.duration ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Durée</span><span class="col-span-2 font-semibold text-slate-700">${data.duration} jours</span></div>` : ''}
                                ${data.commentaire ? `<div class="grid grid-cols-3"><span class="text-slate-400 font-medium">Motif</span><span class="col-span-2 text-slate-600">${data.commentaire}</span></div>` : ''}
                                <div class="grid grid-cols-3 items-center">
                                    <span class="text-slate-400 font-medium">Statut</span>
                                    <span class="col-span-2"><span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-green-50 text-green-700 border border-green-200">${data.statut || '-'}</span></span>
                                </div>
                            </div>
                        `;
                        }

                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    })
                    .catch(err => {
                        content.innerHTML =
                            '<div class="text-center py-8 text-slate-400"><i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-red-300"></i><p class="text-sm text-red-600">Erreur de chargement</p></div>';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
            });
        });
    </script>
@endpush
