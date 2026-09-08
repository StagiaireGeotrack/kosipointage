@extends('layouts.app')

@section('title', 'Paramétrage des périodes de travail')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">⚙️ Paramétrage des périodes de travail</h4>
        <span class="text-muted small">Définir les horaires de travail par poste</span>
    </div>
    <a href="{{ route('planning.horaires-types.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('planning.horaires-types.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- Service --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Service *</label>
                    <select name="service_id" id="service_id" class="form-select" required>
                        <option value="">Sélectionner un service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Poste --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Poste *</label>
                    <select name="poste_id" id="poste_id" class="form-select" required>
                        <option value="">Sélectionner un poste</option>
                        @foreach($postes as $poste)
                            <option value="{{ $poste->id }}" {{ old('poste_id') == $poste->id ? 'selected' : '' }}>
                                {{ $poste->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('poste_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Période de travail --}}
                <div class="col-md-12">
                    <hr>
                    <h6 class="fw-semibold">🕐 Période de travail</h6>
                    <small class="text-muted">Définir les horaires de travail pour ce poste</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Heure début *</label>
                    <input type="time" name="heure_debut" class="form-control" 
                           value="{{ old('heure_debut', '08:00') }}" required>
                    @error('heure_debut')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Heure fin *</label>
                    <input type="time" name="heure_fin" class="form-control" 
                           value="{{ old('heure_fin', '17:00') }}" required>
                    @error('heure_fin')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Pause déjeuner --}}
                <div class="col-md-12">
                    <hr>
                    <h6 class="fw-semibold">🍽️ Pause déjeuner</h6>
                    <small class="text-muted">Définir la pause pour ce poste</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Début pause</label>
                    <input type="time" name="pause_debut" class="form-control" 
                           value="{{ old('pause_debut', '12:00') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fin pause</label>
                    <input type="time" name="pause_fin" class="form-control" 
                           value="{{ old('pause_fin', '13:00') }}">
                </div>

                {{-- Deuxième période --}}
                <div class="col-md-12 mt-3">
                    <hr>
                    <h6 class="fw-semibold">🔄 Deuxième période de travail (optionnelle)</h6>
                    <small class="text-muted">Utilisé pour les horaires coupés (ex: 06:00-11:00 / 13:00-16:00)</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Début 2ème période</label>
                    <input type="time" name="deuxieme_debut" class="form-control" 
                           value="{{ old('deuxieme_debut') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fin 2ème période</label>
                    <input type="time" name="deuxieme_fin" class="form-control" 
                           value="{{ old('deuxieme_fin') }}">
                </div>

                {{-- Jours travaillés --}}
                <div class="col-md-12 mt-3">
                    <hr>
                    <h6 class="fw-semibold">📅 Jours travaillés</h6>
                    <small class="text-muted">Cochez les jours de travail pour ce poste</small>
                    <br><br>

                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="lundi" 
                                       class="form-check-input" 
                                       {{ in_array('lundi', old('jours_travailles', ['lundi','mardi','mercredi','jeudi','vendredi'])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Lundi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="mardi" 
                                       class="form-check-input" 
                                       {{ in_array('mardi', old('jours_travailles', ['lundi','mardi','mercredi','jeudi','vendredi'])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Mardi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="mercredi" 
                                       class="form-check-input" 
                                       {{ in_array('mercredi', old('jours_travailles', ['lundi','mardi','mercredi','jeudi','vendredi'])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Mercredi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="jeudi" 
                                       class="form-check-input" 
                                       {{ in_array('jeudi', old('jours_travailles', ['lundi','mardi','mercredi','jeudi','vendredi'])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Jeudi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="vendredi" 
                                       class="form-check-input" 
                                       {{ in_array('vendredi', old('jours_travailles', ['lundi','mardi','mercredi','jeudi','vendredi'])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Vendredi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="samedi" 
                                       class="form-check-input" 
                                       {{ in_array('samedi', old('jours_travailles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Samedi</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="jours_travailles[]" value="dimanche" 
                                       class="form-check-input" 
                                       {{ in_array('dimanche', old('jours_travailles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold">Dimanche</label>
                            </div>
                        </div>
                    </div>
                    @error('jours_travailles')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Par défaut --}}
                <div class="col-md-12 mt-3">
                    <div class="form-check">
                        <input type="checkbox" name="par_defaut" class="form-check-input" 
                               value="1" {{ old('par_defaut') ? 'checked' : '' }}>
                        <label class="form-check-label">
                            <strong>Utiliser par défaut</strong>
                            <br>
                            <small class="text-muted">Cette période sera sélectionnée automatiquement lors de la création d'un planning</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Enregistrer
                </button>
                <a href="{{ route('planning.horaires-types.index') }}" class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Charger les postes par service
    document.getElementById('service_id').addEventListener('change', function() {
        var serviceId = this.value;
        var posteSelect = document.getElementById('poste_id');
        posteSelect.innerHTML = '<option value="">Chargement...</option>';

        if (serviceId) {
            fetch('/planning/api/job-titles-by-department/' + serviceId)
                .then(response => response.json())
                .then(data => {
                    posteSelect.innerHTML = '<option value="">Sélectionner un poste</option>';
                    data.forEach(function(poste) {
                        posteSelect.innerHTML += '<option value="' + poste.id + '">' + poste.name + '</option>';
                    });
                })
                .catch(function() {
                    posteSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            posteSelect.innerHTML = '<option value="">Sélectionner un service d\'abord</option>';
        }
    });
</script>
@endpush