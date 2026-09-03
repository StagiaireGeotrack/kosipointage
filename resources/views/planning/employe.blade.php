@extends('layouts.app')

@section('title', 'Mon planning')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/main.min.css">
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">📅 Mon planning</h4>
        <span class="text-muted small">Semaine du {{ \Carbon\Carbon::now()->startOfWeek()->format('d/m/Y') }} au {{ \Carbon\Carbon::now()->endOfWeek()->format('d/m/Y') }}</span>
    </div>
    <div>
        <span class="badge bg-primary">👤 {{ $employe->Nom }} {{ $employe->Prenom ?? '' }}</span>
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div id="calendar"></div>
    </div>
</div>

<div class="mt-3 p-3 bg-light rounded border">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-info-circle text-primary"></i>
        <span class="text-muted small">
            <strong>Règles :</strong> Les horaires indiqués sont prévisionnels et seront comparés avec vos pointages réels.
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/locales/fr.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        height: 'auto',
        events: '{{ route("employe.planning.events") }}'
    });
    calendar.render();
});
</script>
@endpush
