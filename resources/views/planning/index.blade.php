@extends('layouts.app')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .planning-scope {
            font-family: 'Roboto', sans-serif;
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
            position: relative;
        }

        .schedule-item:hover {
            transform: scale(1.02);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sticky-left {
            position: sticky;
            left: 0;
            z-index: 10;
        }

        /* Vue Mois - compact */
        .month-cell {
            min-width: 38px !important;
            max-width: 38px !important;
            padding: 2px !important;
        }

        .month-dot {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 20px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .month-dot:hover {
            transform: scale(1.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .month-dot.work {
            background: #DCFCE7;
            color: #166534;
        }

        .month-dot.pause {
            background: #FEF3C7;
            color: #92400E;
        }

        .month-dot.conge {
            background: #FEF3C7;
            color: #92400E;
        }

        .month-dot.rtt {
            background: #FCE7F3;
            color: #9D174D;
        }

        .month-dot.maladie {
            background: #FEE2E2;
            color: #991B1B;
        }

        .month-dot.absence {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .month-dot.formation {
            background: #EDE9FE;
            color: #5B21B6;
        }

        .month-dot.mission {
            background: #FFEDD5;
            color: #9A3412;
        }

        .month-dot.rest {
            background: #F1F5F9;
            color: #94A3B8;
        }

        /* Filtres légende */
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

        /* Bouton de suppression sur événement */
        .event-action-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            transition: all 0.15s ease;
        }

        .event-action-btn:hover {
            background: #EF4444;
            color: white;
        }

        .schedule-item.has-event:hover .event-action-btn {
            display: flex;
        }
    </style>
@endpush

@section('content')
    <div class="planning-scope flex flex-col h-full bg-slate-50 text-slate-800 -m-3">
        <!-- En-tête -->
        <header class="bg-white border-b border-slate-200 p-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">📅 Planning des employés</h1>
                    <p class="text-xs text-slate-500">
                        Vue {{ $view === 'day' ? 'Jour' : ($view === 'month' ? 'Mois' : 'Semaine') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-slate-100 rounded-lg border border-slate-200 p-0.5">
                        <a href="{{ route('planning.index', ['view' => $view, 'date' => $prevDate]) }}"
                            class="p-1.5 hover:bg-white rounded-md transition" title="Précédent">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </a>
                        <div class="flex items-center gap-2 px-3 text-xs font-semibold min-w-[180px] justify-center">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                            <span>{{ $periodLabel }}</span>
                        </div>
                        <a href="{{ route('planning.index', ['view' => $view, 'date' => $nextDate]) }}"
                            class="p-1.5 hover:bg-white rounded-md transition" title="Suivant">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </a>
                    </div>

                    <a href="{{ route('planning.index', ['view' => $view, 'date' => $todayDate]) }}"
                        class="px-3 py-1.5 bg-slate-100 text-xs font-semibold rounded-lg border border-slate-200 hover:bg-slate-200">
                        Aujourd'hui
                    </a>

                    <div class="flex bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-xs font-semibold">
                        <a href="{{ route('planning.index', ['view' => 'day', 'date' => $pivotDate]) }}"
                            class="px-3 py-1.5 rounded-md {{ $view === 'day' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Jour</a>
                        <a href="{{ route('planning.index', ['view' => 'week', 'date' => $pivotDate]) }}"
                            class="px-3 py-1.5 rounded-md {{ $view === 'week' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Semaine</a>
                        <a href="{{ route('planning.index', ['view' => 'month', 'date' => $pivotDate]) }}"
                            class="px-3 py-1.5 rounded-md {{ $view === 'month' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Mois</a>
                    </div>

                    <button type="button" onclick="openEventModal()"
                        class="flex items-center gap-2 px-3.5 py-2 bg-purple-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-purple-700">
                        <i data-lucide="calendar-plus" class="w-4 h-4"></i> Modifier le planning
                    </button>

                    <a href="{{ route('planning.create') }}"
                        class="flex items-center gap-2 px-3.5 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold shadow-sm hover:bg-blue-700">
                        <i data-lucide="plus" class="w-4 h-4"></i> Créer un planning
                    </a>
                </div>
            </div>

            <!-- Filtres -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                <div class="flex flex-wrap items-center gap-2 flex-1">
                    <select id="service-filter"
                        class="border border-slate-200 rounded-lg text-xs font-medium px-3 py-2 bg-white flex-1 min-w-[140px] max-w-[180px]">
                        <option value="">Tous les services</option>
                        @if (isset($servicesData))
                            @foreach ($servicesData as $service)
                                <option value="{{ $service['id'] }}">{{ $service['name'] }}</option>
                            @endforeach
                        @endif
                    </select>

                    <div class="relative flex-1 min-w-[180px] max-w-xs">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
                        <input type="text" id="employee-search" placeholder="Rechercher un employé..."
                            class="pl-9 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs w-full focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Légende filtrable -->
            <div class="flex items-center gap-2 overflow-x-auto text-xs pt-1 pb-1 flex-wrap">
                <span class="text-slate-400 font-medium mr-1">Filtrer par :</span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-green-50 text-green-700 border border-green-200 font-medium whitespace-nowrap"
                    data-filter-type="work">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Travail
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 font-medium whitespace-nowrap"
                    data-filter-type="pause">
                    <i data-lucide="coffee" class="w-3.5 h-3.5"></i> Pause
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 font-medium whitespace-nowrap"
                    data-filter-type="formation">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Formation
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-600 border border-amber-200 font-medium whitespace-nowrap"
                    data-filter-type="conge">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Congé
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-pink-50 text-pink-700 border border-pink-200 font-medium whitespace-nowrap"
                    data-filter-type="rtt">
                    <i data-lucide="hourglass" class="w-3.5 h-3.5"></i> RTT
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-orange-50 text-orange-700 border border-orange-200 font-medium whitespace-nowrap"
                    data-filter-type="deplacement">
                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Déplacement
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-rose-50 text-rose-700 border border-rose-200 font-medium whitespace-nowrap"
                    data-filter-type="maladie">
                    <i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i> Maladie
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 font-medium whitespace-nowrap"
                    data-filter-type="absence">
                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Absence
                </span>
                <span
                    class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 font-medium whitespace-nowrap"
                    data-filter-type="rest">
                    <i data-lucide="moon" class="w-3.5 h-3.5"></i> Repos
                </span>
                <button id="clear-filter"
                    class="hidden ml-2 flex items-center gap-1 px-2.5 py-1 rounded bg-red-50 text-red-600 border border-red-200 font-medium text-[11px] hover:bg-red-100">
                    <i data-lucide="x" class="w-3 h-3"></i> Effacer filtre
                </button>
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
                                @if (isset($days))
                                    @foreach ($days as $day)
                                        <th
                                            class="p-3 text-center border-l border-slate-200 {{ $view === 'month' ? 'month-cell' : 'min-w-[130px]' }}">
                                            @if ($view === 'month')
                                                <span class="block font-bold text-xs">{{ $day['day_num'] }}</span>
                                                <span
                                                    class="block text-[9px] font-normal text-slate-400">{{ $day['short'] }}</span>
                                            @else
                                                {{ $day['short'] }}
                                                <span
                                                    class="block text-[10px] font-normal text-slate-400">{{ $day['date'] }}</span>
                                            @endif
                                        </th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="planning-body">
                            @if (isset($servicesData) && count($servicesData) > 0)
                                @foreach ($servicesData as $service)
                                    <tr class="bg-slate-50/80 font-bold text-slate-700 border-t border-b border-slate-200 service-group"
                                        data-service="{{ $service['id'] }}">
                                        <td colspan="{{ count($days) + 1 }}"
                                            class="p-2.5 px-3 sticky-left bg-slate-50/80">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                                                <span>{{ $service['name'] }}</span>
                                                <span class="text-slate-400 font-normal">({{ $service['count'] }})</span>
                                            </div>
                                        </td>
                                    </tr>

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
                                                    @if (filter_var($emp['avatar'] ?? '', FILTER_VALIDATE_URL))
                                                        <img src="{{ $emp['avatar'] }}"
                                                            class="w-8 h-8 rounded-full object-cover"
                                                            onerror="this.style.display='none'">
                                                    @endif
                                                    <div class="employee-avatar"
                                                        style="background: {{ $emp['color'] ?? '#3B82F6' }}">
                                                        {{ $emp['initiales'] ?? '?' }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-slate-800 text-xs employee-name">
                                                            {{ $emp['name'] }}</div>
                                                        <div class="text-[10px] text-slate-400">
                                                            {{ $emp['role'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach ($days as $day)
                                                <td
                                                    class="border-l border-slate-100 align-top {{ $view === 'month' ? 'month-cell' : 'p-1 min-w-[130px]' }}">
                                                    @if (isset($emp['schedule'][$day['date']]))
                                                        @if ($view === 'month')
                                                            @php
                                                                $firstItem = $emp['schedule'][$day['date']][0] ?? null;
                                                                $dotClass = 'rest';
                                                                $dotText = '·';
                                                                $tooltip = '';
                                                                if ($firstItem) {
                                                                    $type = $firstItem['type'] ?? 'rest';
                                                                    $dotClass = match ($type) {
                                                                        'work' => 'work',
                                                                        'pause' => 'pause',
                                                                        'conge' => 'conge',
                                                                        'rtt' => 'rtt',
                                                                        'maladie' => 'maladie',
                                                                        'absence' => 'absence',
                                                                        'formation' => 'formation',
                                                                        'mission', 'deplacement' => 'mission',
                                                                        default => 'rest',
                                                                    };
                                                                    $dotText = match ($type) {
                                                                        'work' => 'T',
                                                                        'pause' => 'P',
                                                                        'conge' => 'C',
                                                                        'rtt' => 'R',
                                                                        'maladie' => 'M',
                                                                        'absence' => 'A',
                                                                        'formation' => 'F',
                                                                        'mission', 'deplacement' => 'D',
                                                                        default => '·',
                                                                    };
                                                                    $tooltip =
                                                                        $firstItem['time'] ??
                                                                        ($firstItem['title'] ?? '');
                                                                }
                                                            @endphp
                                                            <div class="month-dot {{ $dotClass }}"
                                                                title="{{ $day['date'] }} : {{ $tooltip }}">
                                                                {{ $dotText }}
                                                            </div>
                                                        @else
                                                            <div class="space-y-1">
                                                                @foreach ($emp['schedule'][$day['date']] as $item)
                                                                    @if ($item['type'] === 'work')
                                                                        <div class="schedule-item p-1 rounded bg-green-50 border border-green-200 text-green-800 text-[10px] font-medium flex items-center gap-1"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="planning">
                                                                            <i data-lucide="briefcase"
                                                                                class="w-3 h-3 text-green-600 flex-shrink-0"></i>
                                                                            {{ $item['time'] }}
                                                                        </div>
                                                                    @elseif($item['type'] === 'pause')
                                                                        <div class="schedule-item p-1 rounded bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-medium flex items-center gap-1"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="planning">
                                                                            <i data-lucide="coffee"
                                                                                class="w-3 h-3 text-amber-600 flex-shrink-0"></i>
                                                                            {{ $item['time'] }}
                                                                        </div>
                                                                    @elseif($item['type'] === 'formation')
                                                                        <div class="schedule-item has-event p-2 rounded-lg bg-purple-100 border border-purple-200 text-purple-900 text-center relative"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <span class="event-action-btn text-red-500"
                                                                                onclick="event.stopPropagation(); annulerEvenement({{ $item['id'] }}, '{{ $item['title'] ?? 'Formation' }}')"
                                                                                title="Annuler cet événement">
                                                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                                            </span>
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="graduation-cap"
                                                                                    class="w-3.5 h-3.5 text-purple-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'Formation' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-purple-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'conge')
                                                                        <div class="schedule-item p-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-center"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="calendar"
                                                                                    class="w-3.5 h-3.5 text-amber-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'Congé' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-amber-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'rtt')
                                                                        <div class="schedule-item p-2 rounded-lg bg-pink-50 border border-pink-200 text-pink-900 text-center"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="hourglass"
                                                                                    class="w-3.5 h-3.5 text-pink-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'RTT' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-pink-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'deplacement' || $item['type'] === 'mission')
                                                                        <div class="schedule-item has-event p-2 rounded-lg bg-orange-50 border border-orange-200 text-orange-900 text-center relative"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <span class="event-action-btn text-red-500"
                                                                                onclick="event.stopPropagation(); annulerEvenement({{ $item['id'] }}, '{{ $item['title'] ?? 'Déplacement' }}')"
                                                                                title="Annuler cet événement">
                                                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                                            </span>
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
                                                                        <div class="schedule-item has-event p-2 rounded-lg bg-blue-100 border border-blue-200 text-blue-900 text-center relative"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <span class="event-action-btn text-red-500"
                                                                                onclick="event.stopPropagation(); annulerEvenement({{ $item['id'] }}, '{{ $item['title'] ?? 'Réunion' }}')"
                                                                                title="Annuler cet événement">
                                                                                <i data-lucide="x" class="w-3 h-3"></i>
                                                                            </span>
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="users"
                                                                                    class="w-3.5 h-3.5 text-blue-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'Réunion' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-blue-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'maladie')
                                                                        <div class="schedule-item p-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-center"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="heart-pulse"
                                                                                    class="w-3.5 h-3.5 text-rose-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'Maladie' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-rose-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'absence')
                                                                        <div class="schedule-item p-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-center"
                                                                            data-id="{{ $item['id'] ?? '' }}"
                                                                            data-type="evenement">
                                                                            <div
                                                                                class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                                                <i data-lucide="user-x"
                                                                                    class="w-3.5 h-3.5 text-blue-600 flex-shrink-0"></i>
                                                                                {{ $item['title'] ?? 'Absence' }}
                                                                            </div>
                                                                            <div class="text-[10px] text-blue-600">
                                                                                {{ $item['sub'] ?? '' }}</div>
                                                                        </div>
                                                                    @elseif($item['type'] === 'rest')
                                                                        <div
                                                                            class="p-3 text-center text-slate-400 flex items-center justify-center gap-1 text-[11px]">
                                                                            <i data-lucide="moon"
                                                                                class="w-3.5 h-3.5 flex-shrink-0"></i>
                                                                            Repos
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
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

            @include('planning.partials.details-panel')
        </div>
    </div>

    <!-- ✅ MODAL : Ajouter un événement -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-purple-50 border-b">
                    <h5 class="modal-title font-bold text-purple-800">
                        <i data-lucide="calendar-plus" class="w-5 h-5 inline mr-2"></i>
                        Ajouter un événement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="eventForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Titre *</label>
                                <input type="text" name="titre" id="event_titre" class="form-control" required
                                    placeholder="Ex: Formation sécurité">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Type *</label>
                                <select name="type" id="event_type" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="formation">Formation</option>
                                    <option value="deplacement">Déplacement professionnel</option>
                                    <option value="reunion">Réunion</option>
                                    <option value="conges_exceptionnel">Congé exceptionnel</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Début *</label>
                                <input type="datetime-local" name="debut" id="event_debut" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fin *</label>
                                <input type="datetime-local" name="fin" id="event_fin" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="event_description" class="form-control" rows="2"
                                    placeholder="Détails de l'événement..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Adresse</label>
                                <input type="text" name="adresse" id="event_adresse" class="form-control"
                                    placeholder="Adresse où l'événement aura lieu...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Couleur</label>
                                <input type="color" name="couleur" id="event_couleur"
                                    class="form-control form-control-color" value="#8B5CF6">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Toute la journée</label>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="toute_la_journee" id="event_all_day"
                                        class="form-check-input" value="1">
                                    <label class="form-check-label" for="event_all_day">Oui, sur toute la journée</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Employés concernés *</label>
                                <select name="employe_ids[]" id="event_employes" class="form-select" multiple
                                    size="6" required>
                                    @if (isset($servicesData))
                                        @foreach ($servicesData as $service)
                                            <optgroup label="{{ $service['name'] }}">
                                                @foreach ($service['employees'] as $emp)
                                                    <option value="{{ $emp['id'] }}">{{ $emp['name'] }}
                                                        ({{ $emp['role'] }})</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="text-muted">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs
                                    employés</small>
                            </div>
                        </div>
                        <div id="event_error" class="alert alert-danger mt-3" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-purple" style="background:#8B5CF6;color:white;">
                            <i data-lucide="check" class="w-4 h-4 inline"></i> Ajouter l'événement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // ============================================================
            // FILTRES (Service + Recherche + Type)
            // ============================================================
            var activeTypeFilter = null;

            function filterTable() {
                var selectedService = document.getElementById('service-filter').value;
                var searchQuery = document.getElementById('employee-search').value.toLowerCase().trim();

                document.querySelectorAll('.service-group').forEach(function(group) {
                    var serviceId = group.dataset.service;
                    var serviceRows = document.querySelectorAll('.employee-row[data-service="' + serviceId +
                        '"]');
                    var visibleCount = 0;

                    if (selectedService && serviceId != selectedService) {
                        group.style.display = 'none';
                        serviceRows.forEach(r => r.style.display = 'none');
                        return;
                    }

                    serviceRows.forEach(function(row) {
                        var empName = row.querySelector('.employee-name')?.textContent
                        .toLowerCase() || '';
                        var empTypes = (row.dataset.types || '').split(',').filter(Boolean);

                        var matchSearch = (searchQuery === '' || empName.includes(searchQuery));
                        var matchType = (activeTypeFilter === null || empTypes.includes(
                            activeTypeFilter));

                        if (matchSearch && matchType) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    group.style.display = visibleCount > 0 ? '' : 'none';
                });
            }

            document.getElementById('service-filter').addEventListener('change', filterTable);
            document.getElementById('employee-search').addEventListener('keyup', filterTable);

            // ============================================================
            // FILTRE PAR TYPE
            // ============================================================
            var clearBtn = document.getElementById('clear-filter');

            document.querySelectorAll('.legend-filter').forEach(function(el) {
                el.addEventListener('click', function() {
                    var type = this.dataset.filterType;

                    if (activeTypeFilter === type) {
                        activeTypeFilter = null;
                        this.classList.remove('active');
                        clearBtn.classList.add('hidden');
                    } else {
                        activeTypeFilter = type;
                        document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove(
                            'active'));
                        this.classList.add('active');
                        clearBtn.classList.remove('hidden');
                    }
                    filterTable();
                });
            });

            clearBtn.addEventListener('click', function() {
                activeTypeFilter = null;
                document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove('active'));
                this.classList.add('hidden');
                filterTable();
            });

            // ============================================================
            // CHARGER LES DÉTAILS D'UN CRÉNEAU (AJAX)
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

                fetch('/planning/event-detail/' + eventId + '/' + eventType)
                    .then(r => r.json())
                    .then(data => {
                        if (!data || !data.type) {
                            content.innerHTML =
                                '<div class="text-center py-8 text-slate-400"><i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i><p class="text-sm">Aucun détail disponible</p></div>';
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                            return;
                        }

                        if (data.type === 'planning') {
                            content.innerHTML = `
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
                                ${data.site ? `
                                    <div class="grid grid-cols-3">
                                        <span class="text-slate-400 font-medium">Site</span>
                                        <span class="col-span-2 font-semibold text-slate-700">${data.site}</span>
                                    </div>
                                ` : ''}
                                ${data.pause_debut ? `
                                    <div class="grid grid-cols-3">
                                        <span class="text-slate-400 font-medium">Pause</span>
                                        <span class="col-span-2 font-semibold text-slate-700">${data.pause_debut} - ${data.pause_fin}</span>
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
                        `;
                        } else if (data.type === 'evenement') {
                            content.innerHTML = `
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
                                ${data.adresse ? `
                                    <div class="grid grid-cols-3">
                                        <span class="text-slate-400 font-medium">Adresse</span>
                                        <span class="col-span-2 text-slate-600">${data.adresse}</span>
                                    </div>
                                ` : ''}
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
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <button type="button" onclick="annulerEvenement(${data.evenement_id}, '${(data.titre || '').replace(/'/g, "\\'")}')"
                                        class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-semibold hover:bg-red-100">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Annuler cet événement
                                </button>
                            </div>
                        `;
                        }

                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    })
                    .catch(() => {
                        content.innerHTML =
                            '<div class="text-center py-8 text-slate-400"><i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-2 text-red-300"></i><p class="text-sm text-red-600">Erreur de chargement</p></div>';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
            });
        });

        // ============================================================
        // OUVRIR LE MODAL D'AJOUT D'ÉVÉNEMENT
        // ============================================================
        function openEventModal() {
            document.getElementById('eventForm').reset();
            document.getElementById('event_error').style.display = 'none';
            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        }

        // ============================================================
        // SOUMETTRE LE FORMULAIRE D'ÉVÉNEMENT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('eventForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                var errorDiv = document.getElementById('event_error');
                errorDiv.style.display = 'none';

                var formData = new FormData(form);
                var employesSelect = document.getElementById('event_employes');
                var selectedOptions = Array.from(employesSelect.selectedOptions).map(o => o.value);

                if (selectedOptions.length === 0) {
                    errorDiv.textContent = 'Veuillez sélectionner au moins un employé.';
                    errorDiv.style.display = 'block';
                    return;
                }

                // Supprimer les anciens employe_ids et les rajouter
                formData.delete('employe_ids[]');
                selectedOptions.forEach(id => formData.append('employe_ids[]', id));

                fetch('{{ route('planning.events.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                            // Recharger la page pour voir le nouvel événement
                            window.location.reload();
                        } else {
                            errorDiv.textContent = data.message || 'Erreur lors de l\'ajout';
                            errorDiv.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        errorDiv.textContent = 'Erreur réseau : ' + err.message;
                        errorDiv.style.display = 'block';
                    });
            });
        });

        // ============================================================
        // ANNULER UN ÉVÉNEMENT
        // ============================================================
        function annulerEvenement(eventId, titre) {
            if (!confirm('Voulez-vous vraiment annuler l\'événement "' + titre +
                    '" ?\n\nLe créneau normal réapparaîtra sur le planning.')) {
                return;
            }

            fetch('/planning/events/' + eventId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Événement annulé avec succès');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'annulation');
                    }
                })
                .catch(err => {
                    alert('Erreur réseau : ' + err.message);
                });
        }
    </script>
@endpush
