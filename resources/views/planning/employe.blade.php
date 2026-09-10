@extends('layouts.app')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    .planning-scope { font-family: 'Roboto', sans-serif; }
    .employee-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 18px; color: white; flex-shrink: 0;
    }
    .schedule-item { transition: all 0.15s ease; cursor: default; }
    .bg-work { background: #DCFCE7; border-color: #86EFAC; color: #166534; }
    .bg-pause { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-rest { background: #F1F5F9; border-color: #CBD5E1; color: #475569; }
    .bg-conge { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-rtt { background: #FCE7F3; border-color: #F9A8D4; color: #9D174D; }
    .bg-maladie { background: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
    .bg-absence { background: #DBEAFE; border-color: #93C5FD; color: #1E40AF; }
    .bg-formation { background: #EDE9FE; border-color: #C4B5FD; color: #5B21B6; }
    .bg-mission { background: #FFEDD5; border-color: #FDBA74; color: #9A3412; }

    .sticky-left { position: sticky; left: 0; z-index: 10; }

    /* ✅ Légende filtrable */
    .legend-filter {
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
        opacity: 0.85;
    }
    .legend-filter:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        opacity: 1;
    }
    .legend-filter.active {
        box-shadow: 0 0 0 2px #3B82F6, 0 2px 6px rgba(59,130,246,0.3);
        opacity: 1;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="planning-scope flex flex-col h-full bg-slate-50 text-slate-800 -m-3">
    <!-- En-tête -->
    <header class="bg-white border-b border-slate-200 p-4 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">📅 Mon planning</h1>
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
                    @if($canGoNext)
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

                <!-- ✅ Uniquement Jour et Semaine pour l'employé -->
                <div class="flex bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-xs font-semibold">
                    <a href="{{ route('employe.planning.index', ['view' => 'day', 'date' => $pivotDate]) }}"
                       class="px-3 py-1.5 rounded-md {{ $view === 'day' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Jour</a>
                    <a href="{{ route('employe.planning.index', ['view' => 'week', 'date' => $pivotDate]) }}"
                       class="px-3 py-1.5 rounded-md {{ $view === 'week' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-white' }}">Semaine</a>
                </div>
            </div>
        </div>

        <!-- ✅ Bannière informative -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 text-center text-xs text-blue-700">
            <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1"></i>
            Vous consultez uniquement votre planning personnel. Les semaines futures ne sont pas accessibles.
        </div>

        <!-- ✅ Légende filtrable -->
        <div class="flex items-center gap-2 overflow-x-auto text-xs pt-1 pb-1 flex-wrap">
            <span class="text-slate-400 font-medium mr-1">Filtrer par :</span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-green-50 text-green-700 border border-green-200 font-medium whitespace-nowrap" data-filter-type="work">
                <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Travail
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200 font-medium whitespace-nowrap" data-filter-type="pause">
                <i data-lucide="coffee" class="w-3.5 h-3.5"></i> Pause
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-purple-50 text-purple-700 border border-purple-200 font-medium whitespace-nowrap" data-filter-type="formation">
                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Formation
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-amber-50 text-amber-600 border border-amber-200 font-medium whitespace-nowrap" data-filter-type="conge">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Congé
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-pink-50 text-pink-700 border border-pink-200 font-medium whitespace-nowrap" data-filter-type="rtt">
                <i data-lucide="hourglass" class="w-3.5 h-3.5"></i> RTT
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-orange-50 text-orange-700 border border-orange-200 font-medium whitespace-nowrap" data-filter-type="deplacement">
                <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Déplacement
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-rose-50 text-rose-700 border border-rose-200 font-medium whitespace-nowrap" data-filter-type="maladie">
                <i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i> Maladie
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 font-medium whitespace-nowrap" data-filter-type="absence">
                <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Absence
            </span>
            <span class="legend-filter flex items-center gap-1.5 px-2.5 py-1 rounded bg-slate-100 text-slate-600 border border-slate-200 font-medium whitespace-nowrap" data-filter-type="rest">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i> Repos
            </span>
            <button id="clear-filter" class="hidden ml-2 flex items-center gap-1 px-2.5 py-1 rounded bg-red-50 text-red-600 border border-red-200 font-medium text-[11px] hover:bg-red-100">
                <i data-lucide="x" class="w-3 h-3"></i> Effacer filtre
            </button>
        </div>
    </header>

    <!-- Planning Content -->
    <div class="flex-1 overflow-auto p-4 flex gap-4">
        <!-- Planning Table -->
        <div class="flex-1 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full border-collapse text-left text-xs">
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
                    <tbody>
                        @php
                            $empTypes = [];
                            foreach ($employeeData['schedule'] as $items) {
                                foreach ($items as $item) {
                                    $t = $item['type'] ?? '';
                                    if ($t === 'work') $empTypes['work'] = true;
                                    elseif ($t === 'pause') $empTypes['pause'] = true;
                                    elseif ($t === 'conge') $empTypes['conge'] = true;
                                    elseif ($t === 'rtt') $empTypes['rtt'] = true;
                                    elseif ($t === 'maladie') $empTypes['maladie'] = true;
                                    elseif ($t === 'absence') $empTypes['absence'] = true;
                                    elseif ($t === 'formation') $empTypes['formation'] = true;
                                    elseif ($t === 'mission' || $t === 'deplacement') $empTypes['deplacement'] = true;
                                    elseif ($t === 'rest') $empTypes['rest'] = true;
                                }
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 employee-row" data-types="{{ implode(',', array_keys($empTypes)) }}">
                            <td class="p-3 align-top sticky-left bg-white">
                                <div class="flex items-center gap-3">
                                    <div class="employee-avatar" style="background: {{ $employeeData['color'] ?? '#3B82F6' }}">
                                        {{ $employeeData['initiales'] ?? '?' }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-xs">{{ $employeeData['name'] }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $employeeData['role'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            @foreach($days as $day)
                            <td class="p-1 border-l border-slate-100 align-top min-w-[130px]">
                                @if(isset($employeeData['schedule'][$day['date']]))
                                    <div class="space-y-1">
                                        @foreach($employeeData['schedule'][$day['date']] as $item)
                                            @if($item['type'] === 'work')
                                                <div class="p-1 rounded bg-green-50 border border-green-200 text-green-800 text-[10px] font-medium flex items-center gap-1">
                                                    <i data-lucide="briefcase" class="w-3 h-3 text-green-600 flex-shrink-0"></i> {{ $item['time'] }}
                                                </div>
                                            @elseif($item['type'] === 'pause')
                                                <div class="p-1 rounded bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-medium flex items-center gap-1">
                                                    <i data-lucide="coffee" class="w-3 h-3 text-amber-600 flex-shrink-0"></i> {{ $item['time'] }}
                                                </div>
                                            @elseif($item['type'] === 'conge' || $item['type'] === 'rtt' || $item['type'] === 'maladie' || $item['type'] === 'absence')
                                                <div class="p-2 rounded-lg {{ $item['type'] === 'conge' ? 'bg-amber-50 border-amber-200 text-amber-900' : ($item['type'] === 'rtt' ? 'bg-pink-50 border-pink-200 text-pink-900' : ($item['type'] === 'maladie' ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-blue-50 border-blue-200 text-blue-900')) }} border text-center">
                                                    <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                        <i data-lucide="{{ $item['type'] === 'conge' ? 'calendar' : ($item['type'] === 'rtt' ? 'hourglass' : ($item['type'] === 'maladie' ? 'heart-pulse' : 'user-x')) }}" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                                        {{ $item['title'] ?? 'Absence' }}
                                                    </div>
                                                    <div class="text-[10px]">{{ $item['sub'] ?? '' }}</div>
                                                </div>
                                            @elseif($item['type'] === 'formation' || $item['type'] === 'mission' || $item['type'] === 'deplacement')
                                                <div class="p-2 rounded-lg {{ $item['type'] === 'formation' ? 'bg-purple-100 border-purple-200 text-purple-900' : 'bg-orange-50 border-orange-200 text-orange-900' }} border text-center">
                                                    <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                        <i data-lucide="{{ $item['type'] === 'formation' ? 'graduation-cap' : 'navigation' }}" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                                        {{ $item['title'] ?? 'Événement' }}
                                                    </div>
                                                    <div class="text-[10px]">{{ $item['sub'] ?? '' }}</div>
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
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-t border-slate-200 bg-slate-50 text-xs text-slate-500 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                Les horaires indiqués sont prévisionnels et seront comparés avec vos pointages réels.
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
        // FILTRE PAR TYPE (clic sur la légende)
        // ============================================================
        var activeTypeFilter = null;
        var clearBtn = document.getElementById('clear-filter');

        function applyFilter() {
            document.querySelectorAll('.employee-row').forEach(function(row) {
                var empTypes = (row.dataset.types || '').split(',').filter(Boolean);
                var matchType = (activeTypeFilter === null || empTypes.includes(activeTypeFilter));
                row.style.display = matchType ? '' : 'none';
            });
        }

        document.querySelectorAll('.legend-filter').forEach(function(el) {
            el.addEventListener('click', function() {
                var type = this.dataset.filterType;

                if (activeTypeFilter === type) {
                    activeTypeFilter = null;
                    this.classList.remove('active');
                    clearBtn.classList.add('hidden');
                } else {
                    activeTypeFilter = type;
                    document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    clearBtn.classList.remove('hidden');
                }
                applyFilter();
            });
        });

        clearBtn.addEventListener('click', function() {
            activeTypeFilter = null;
            document.querySelectorAll('.legend-filter').forEach(l => l.classList.remove('active'));
            this.classList.add('hidden');
            applyFilter();
        });
    });
</script>
@endpush
