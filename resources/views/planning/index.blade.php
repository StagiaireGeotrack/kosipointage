@extends('layouts.app')

@section('title', 'Planning des employés')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/main.min.css">
<style>
    .fc-daygrid-day-frame { min-height: 80px !important; }
    .fc-event { cursor: pointer !important; }
    .employee-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #3B82F6;
        color: white;
        font-weight: bold;
        font-size: 14px;
        margin-right: 6px;
        flex-shrink: 0;
    }
    .employee-avatar.small { width: 28px; height: 28px; font-size: 11px; }
    .employee-avatar.green { background: #22C55E; }
    .employee-avatar.orange { background: #F59E0B; }
    .employee-avatar.purple { background: #8B5CF6; }
    .employee-avatar.red { background: #EF4444; }
    .filter-section {
        background: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    .stats-card {
        background: white;
        padding: 12px 18px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        text-align: center;
    }
    .stats-card .number { font-size: 24px; font-weight: 700; color: #1F2937; }
    .stats-card .label { font-size: 13px; color: #6B7280; }
</style>
@endpush

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">📅 Planning des employés</h4>
        <span class="text-muted small">Vue semaine</span>
    </div>
    <div>
        <a href="{{ route('planning.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Créer un planning
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventModal">
            <i class="bi bi-calendar-plus"></i> Ajouter un événement
        </button>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-0">

    {{-- Filtres --}}
    <div class="filter-section">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Service</label>
                <select id="service-filter" class="form-select">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Fonction / Poste</label>
                <select id="poste-filter" class="form-select">
                    <option value="">Toutes les fonctions</option>
                    @foreach($postes as $poste)
                        <option value="{{ $poste->id }}">{{ $poste->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Semaine</label>
                <input type="week" id="week-picker" class="form-control" value="{{ date('Y-\WW') }}">
            </div>
            <div class="col-md-3 text-end">
                <button id="refresh-calendar" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Actualiser
                </button>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-employes">0</div>
                <div class="label">Employés</div>
            </div>
        </div>
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-services">0</div>
                <div class="label">Services</div>
            </div>
        </div>
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-evenements">0</div>
                <div class="label">Événements</div>
            </div>
        </div>
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-creneaux">0</div>
                <div class="label">Créneaux</div>
            </div>
        </div>
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-postes">0</div>
                <div class="label">Postes</div>
            </div>
        </div>
        <div class="col-4 col-md-2">
            <div class="stats-card">
                <div class="number" id="stat-services-actifs">0</div>
                <div class="label">Services actifs</div>
            </div>
        </div>
    </div>

    {{-- Calendrier --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- Règles --}}
    <div class="mt-3 p-3 bg-light rounded border">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle text-primary"></i>
            <span class="text-muted small">
                <strong>Règles :</strong> Les horaires indiqués sont prévisionnels et seront comparés avec les pointages réels.
            </span>
        </div>
    </div>
</div>

{{-- Modal Événement --}}
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-plus"></i> Ajouter un événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eventForm">
                <div class="modal-body">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Titre *</label>
                            <input type="text" name="titre" class="form-control" required placeholder="Ex: Formation sécurité">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <option value="formation">Formation</option>
                                <option value="deplacement">Déplacement professionnel</option>
                                <option value="reunion">Réunion</option>
                                <option value="conges_exceptionnel">Congé exceptionnel</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Couleur</label>
                            <input type="color" name="couleur" class="form-control form-control-color" value="#8B5CF6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Début *</label>
                            <input type="datetime-local" name="debut" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fin *</label>
                            <input type="datetime-local" name="fin" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Service concerné</label>
                            <select name="service_id" class="form-select">
                                <option value="">Tous les services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Poste concerné</label>
                            <select name="poste_id" class="form-select">
                                <option value="">Tous les postes</option>
                                @foreach($postes as $poste)
                                    <option value="{{ $poste->id }}">{{ $poste->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Employés concernés</label>
                            <select name="employe_ids[]" class="form-select select2-multi" multiple>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->ID }}">{{ $employe->Nom }} {{ $employe->Prenom ?? '' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Laissez vide pour tous les employés du service/poste</small>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="toute_la_journee" class="form-check-input" id="allDay">
                                <label class="form-check-label" for="allDay">Toute la journée</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter l'événement</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Détail --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
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
        events: function(fetchInfo, successCallback, failureCallback) {
            var serviceId = document.getElementById('service-filter').value;
            var posteId = document.getElementById('poste-filter').value;
            var url = '{{ route("planning.events") }}' +
                '?start=' + fetchInfo.startStr +
                '&end=' + fetchInfo.endStr +
                (serviceId ? '&service_id=' + serviceId : '') +
                (posteId ? '&poste_id=' + posteId : '');
            fetch(url).then(r => r.json()).then(data => {
                updateStats(data);
                successCallback(data);
            }).catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            var props = info.event.extendedProps;
            var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            if (props.type === 'planning') {
                document.getElementById('detailContent').innerHTML = `
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="employee-avatar" style="width:48px;height:48px;font-size:20px;">${props.initiales || '?'}</div>
                        <div><h6 class="mb-0 fw-bold">${props.employe}</h6>
                        <span class="badge bg-primary">${props.statut}</span></div>
                    </div>
                    <hr>
                    <div class="row g-2">
                        <div class="col-6"><strong>Date :</strong></div>
                        <div class="col-6">${new Date(info.event.start).toLocaleDateString('fr-FR')}</div>
                        <div class="col-6"><strong>Horaires :</strong></div>
                        <div class="col-6">${new Date(info.event.start).toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'})} - ${new Date(info.event.end).toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'})}</div>
                        ${props.commentaire ? `<div class="col-12"><strong>Commentaire :</strong><br><span class="text-muted">${props.commentaire}</span></div>` : ''}
                        <div class="col-12 mt-2"><a href="{{ url('planning') }}/${props.planning_id}" class="btn btn-sm btn-outline-primary">Voir le planning complet</a></div>
                    </div>
                `;
            } else if (props.type === 'event') {
                document.getElementById('detailContent').innerHTML = `
                    <div class="mb-3"><h6 class="fw-bold">${props.titre}</h6>
                    <span class="badge bg-info">${props.type_event}</span></div>
                    <hr>
                    <div class="row g-2">
                        <div class="col-12"><strong>Description :</strong><br><span class="text-muted">${props.description || 'Aucune description'}</span></div>
                        <div class="col-6"><strong>Début :</strong></div>
                        <div class="col-6">${new Date(info.event.start).toLocaleString('fr-FR')}</div>
                        <div class="col-6"><strong>Fin :</strong></div>
                        <div class="col-6">${new Date(info.event.end).toLocaleString('fr-FR')}</div>
                    </div>
                `;
            }
            detailModal.show();
        },
        eventDidMount: function(info) {
            var props = info.event.extendedProps;
            if (props.type === 'planning' && props.initiales) {
                var titleEl = info.el.querySelector('.fc-event-title');
                if (titleEl) {
                    var avatar = document.createElement('span');
                    avatar.className = 'employee-avatar small';
                    avatar.textContent = props.initiales;
                    avatar.style.marginRight = '6px';
                    titleEl.prepend(avatar);
                }
            }
        }
    });
    calendar.render();

    function updateStats(events) {
        var employes = new Set(), services = new Set(), postes = new Set();
        var evenements = 0, creneaux = 0;
        events.forEach(event => {
            var props = event.extendedProps || {};
            if (props.type === 'planning') {
                if (props.employe_id) employes.add(props.employe_id);
                creneaux++;
            } else if (props.type === 'event') {
                evenements++;
            }
        });
        document.getElementById('stat-employes').textContent = employes.size;
        document.getElementById('stat-services').textContent = services.size;
        document.getElementById('stat-postes').textContent = postes.size;
        document.getElementById('stat-evenements').textContent = evenements;
        document.getElementById('stat-creneaux').textContent = creneaux;
        document.getElementById('stat-services-actifs').textContent = services.size;
    }

    document.getElementById('refresh-calendar').addEventListener('click', function() {
        calendar.refetchEvents();
    });

    document.getElementById('service-filter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
    document.getElementById('poste-filter').addEventListener('change', function() {
        calendar.refetchEvents();
    });

    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        fetch('{{ route("planning.events.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        }).then(r => r.json()).then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                calendar.refetchEvents();
                this.reset();
                alert(data.message);
            }
        });
    });
});
</script>
@endpush
