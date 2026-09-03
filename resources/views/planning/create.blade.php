@extends('layouts.app')

@section('title', 'Créer un planning')

@section('header')
<h4 class="fw-bold mb-0">📅 Créer un planning hebdomadaire</h4>
<span class="text-muted small">Générer automatiquement les horaires selon le poste sélectionné</span>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('planning.store') }}" method="POST">
            @csrf

            {{-- Service --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Service *</label>
                <select name="service_id" id="service_id" class="form-select" required>
                    <option value="">Sélectionner un service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Poste --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Poste / Fonction *</label>
                <select name="poste_id" id="poste_id" class="form-select" required>
                    <option value="">Sélectionner un poste</option>
                </select>
                <small class="text-muted" id="horaire-info">Les horaires seront automatiquement chargés</small>
            </div>

            {{-- Période --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date début *</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Date fin *</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                </div>
            </div>

            {{-- Jours de travail --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Jours de travail</label>
                <div class="d-flex flex-wrap gap-3">
                    <label><input type="checkbox" name="jours_travail[]" value="lundi" checked> Lundi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="mardi" checked> Mardi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="mercredi" checked> Mercredi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="jeudi" checked> Jeudi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="vendredi" checked> Vendredi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="samedi"> Samedi</label>
                    <label><input type="checkbox" name="jours_travail[]" value="dimanche"> Dimanche</label>
                </div>
            </div>

            {{-- Employés --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Employés *</label>
                <select name="employe_ids[]" id="employe_ids" class="form-select select2-multi" multiple required>
                    @foreach($employes as $employe)
                        <option value="{{ $employe->ID }}">{{ $employe->Nom }} {{ $employe->Prenom ?? '' }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Sélectionnez les employés à planifier</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Générer le planning
                </button>
                <a href="{{ route('planning.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Charger les postes par service
        $('#service_id').on('change', function() {
            var serviceId = $(this).val();
            var posteSelect = $('#poste_id');
            posteSelect.html('<option value="">Chargement...</option>');

            if (serviceId) {
                $.ajax({
                    url: '{{ route("planning.api.job-titles-by-department", "") }}/' + serviceId,
                    method: 'GET',
                    success: function(data) {
                        posteSelect.html('<option value="">Sélectionner un poste</option>');
                        data.forEach(function(poste) {
                            posteSelect.append(
                                '<option value="' + poste.id + '">' + poste.name + '</option>'
                            );
                        });
                    }
                });
            } else {
                posteSelect.html('<option value="">Sélectionner un service d\'abord</option>');
            }
        });

        // Charger les horaires par poste
        $('#poste_id').on('change', function() {
            var posteId = $(this).val();
            var info = $('#horaire-info');

            if (posteId) {
                $.ajax({
                    url: '{{ route("planning.api.work-schedules-by-job-title", "") }}/' + posteId,
                    method: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            var html = '<strong>Horaires :</strong><br>';
                            data.forEach(function(h) {
                                html += h.jour_libelle + ' : ' + h.libelle_horaire + '<br>';
                            });
                            info.html(html);
                        } else {
                            info.html('⚠️ Aucun horaire défini pour ce poste. Configurez-les d\'abord dans "Paramètres des horaires".');
                        }
                    }
                });
            } else {
                info.html('Sélectionnez un poste pour voir les horaires');
            }
        });

        // Select2
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-multi').select2({
                placeholder: 'Sélectionner des employés',
                allowClear: true,
                width: '100%'
            });
        }

        // Date par défaut (semaine en cours)
        var now = new Date();
        var startOfWeek = new Date(now);
        startOfWeek.setDate(now.getDate() - now.getDay() + 1);
        var endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);

        document.getElementById('date_debut').value = startOfWeek.toISOString().split('T')[0];
        document.getElementById('date_fin').value = endOfWeek.toISOString().split('T')[0];
    });
</script>
@endpush
