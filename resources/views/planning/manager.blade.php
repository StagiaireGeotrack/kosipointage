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
    }
    .schedule-item:hover {
        transform: scale(1.02);
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .bg-work { background: #DCFCE7; border-color: #86EFAC; color: #166534; }
    .bg-pause { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-rest { background: #F1F5F9; border-color: #CBD5E1; color: #475569; }
    .bg-conge { background: #FEF3C7; border-color: #FCD34D; color: #92400E; }
    .bg-rtt { background: #FCE7F3; border-color: #F9A8D4; color: #9D174D; }
    .bg-maladie { background: #FEE2E2; border-color: #FCA5A5; color: #991B1B; }
    .bg-absence { background: #DBEAFE; border-color: #93C5FD; color: #1E40AF; }
    .bg-formation { background: #EDE9FE; border-color: #C4B5FD; color: #5B21B6; }
    .bg-mission { background: #FFEDD5; border-color: #FDBA74; color: #9A3412; }

    .sticky-left {
        position: sticky;
        left: 0;
        z-index: 10;
    }

    .manager-badge {
        background: #3B82F6;
        color: white;
        font-size: 9px;
        padding: 1px 8px;
        border-radius: 10px;
        margin-left: 6px;
    }
</style>
@endpush

@section('content')
<div class="planning-scope flex flex-col h-full bg-slate-50 text-slate-800 -m-3">
    <!-- En-tête -->
    <header class="bg-white border-b border-slate-200 p-4 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">📅 Planning - Vue Manager</h1>
                <p class="text-xs text-slate-500">Vue semaine - Vous voyez les employés de votre service</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center bg-slate-100 rounded-lg border border-slate-200 p-0.5">
                    <button class="p-1.5 hover:bg-white rounded-md transition" id="prev-week">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <div class="flex items-center gap-2 px-3 text-xs font-semibold" id="week-label">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-500"></i>
                        <span id="week-range">
                            @php
                                $dateDebut = $days[0]['date'] ?? '--';
                                $dateFin = $days[6]['date'] ?? '--';
                            @endphp
                            {{ $dateDebut }} – {{ $dateFin }}
                        </span>
                    </div>
                    <button class="p-1.5 hover:bg-white rounded-md transition" id="next-week">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>

                <button class="px-3 py-1.5 bg-slate-100 text-xs font-semibold rounded-lg border border-slate-200 hover:bg-slate-200" id="today-btn">
                    Aujourd'hui
                </button>
            </div>
        </div>

        <!-- Bannière Manager -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 text-center text-sm text-blue-700">
            <i data-lucide="eye" class="w-4 h-4 inline mr-2"></i>
            Vue manager : vous voyez les employés de votre service
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
                    <tbody class="divide-y divide-slate-100">
                        @if(isset($servicesData) && count($servicesData) > 0)
                            @foreach($servicesData as $service)
                            <!-- Service Group Row -->
                            <tr class="bg-slate-50/80 font-bold text-slate-700 border-t border-b border-slate-200">
                                <td colspan="8" class="p-2.5 px-3 sticky-left bg-slate-50/80">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                                        <span>{{ $service['name'] }}</span>
                                        <span class="text-slate-400 font-normal">({{ $service['count'] }})</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Employee Schedule Rows -->
                            @foreach($service['employees'] as $emp)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3 align-top sticky-left bg-white">
                                    <div class="flex items-center gap-3">
                                        <div class="employee-avatar" style="background: {{ $emp['color'] ?? '#22C55E' }}">
                                            {{ $emp['initiales'] ?? '?' }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800 text-xs">
                                                {{ $emp['name'] }}
                                                @if($emp['is_manager'] ?? false)
                                                    <span class="manager-badge">Manager</span>
                                                @endif
                                            </div>
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
                                                @elseif($item['type'] === 'conge' || $item['type'] === 'rtt' || $item['type'] === 'maladie' || $item['type'] === 'absence')
                                                    <div class="schedule-item p-2 rounded-lg {{ $item['type'] === 'conge' ? 'bg-amber-50 border-amber-200 text-amber-900' : ($item['type'] === 'rtt' ? 'bg-pink-50 border-pink-200 text-pink-900' : ($item['type'] === 'maladie' ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-blue-50 border-blue-200 text-blue-900')) }} border text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
                                                        <div class="font-bold text-[11px] flex items-center justify-center gap-1">
                                                            <i data-lucide="{{ $item['type'] === 'conge' ? 'calendar' : ($item['type'] === 'rtt' ? 'hourglass' : ($item['type'] === 'maladie' ? 'heart-pulse' : 'user-x')) }}" class="w-3.5 h-3.5 flex-shrink-0"></i>
                                                            {{ $item['title'] ?? 'Absence' }}
                                                        </div>
                                                        <div class="text-[10px]">{{ $item['sub'] ?? '' }}</div>
                                                    </div>
                                                @elseif($item['type'] === 'formation' || $item['type'] === 'mission')
                                                    <div class="schedule-item p-2 rounded-lg {{ $item['type'] === 'formation' ? 'bg-purple-100 border-purple-200 text-purple-900' : 'bg-orange-50 border-orange-200 text-orange-900' }} border text-center" data-id="{{ $item['id'] ?? '' }}" data-type="evenement">
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
                            @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">
                                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                                    <p>Aucun planning disponible pour votre service</p>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialiser Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // ============================================================
        // NAVIGATION SEMAINE
        // ============================================================
        var currentOffset = 0;

        function updateWeek(offset) {
            currentOffset += offset;
            window.location.href = '{{ route("employe.planning.index") }}?week_offset=' + currentOffset;
        }

        document.getElementById('prev-week').addEventListener('click', function() {
            updateWeek(-1);
        });

        document.getElementById('next-week').addEventListener('click', function() {
            updateWeek(1);
        });

        document.getElementById('today-btn').addEventListener('click', function() {
            window.location.href = '{{ route("employe.planning.index") }}';
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
                                    </div>
                                `);
                            }
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }
                        }
                    }
                });
            }
        });
    });
</script>
@endpush
