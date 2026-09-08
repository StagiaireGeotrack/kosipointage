@extends('layouts.app')

@push('styles')
{{-- Select2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<style>
    .select2-container--default .select2-selection--multiple {
        border-radius: 8px !important;
        border-color: #D1D5DB !important;
        padding: 4px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #3B82F6 !important;
        color: white !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 2px 10px !important;
        font-size: 12px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white !important;
        opacity: 0.7;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        opacity: 1;
        background: none !important;
    }
    .horaire-card {
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 16px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .horaire-card:hover {
        border-color: #93C5FD;
        background: #F8FAFC;
    }
    .horaire-card.active {
        border-color: #3B82F6;
        background: #EFF6FF;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    }
    .horaire-card .badge-jour {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        background: #F3F4F6;
        color: #374151;
        margin: 2px;
    }
    .horaire-card .badge-jour.active {
        background: #3B82F6;
        color: white;
    }
    .horaire-card .badge-jour.inactive {
        background: #F3F4F6;
        color: #9CA3AF;
    }
    .horaire-info {
        font-size: 12px;
        color: #6B7280;
    }
</style>
@endpush

@section('title', 'Créer un planning')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">📋 Créer un planning</h4>
        <span class="text-muted small">Assigner des horaires aux employés</span>
    </div>
    <a href="{{ route('planning.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('planning.store') }}" method="POST" id="planningForm">
            @csrf

            <div class="row g-4">
                {{-- ÉTAPE 1 : Service --}}
                <div class="col-md-12">
                    <h6 class="fw-bold text-primary">📌 Étape 1 : Sélectionner le service</h6>
                    <hr>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Service *</label>
                    <select name="service_id" id="service_id" class="form-select" required>
                        <option value="">Sélectionner un service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ÉTAPE 2 : Poste --}}
                <div class="col-md-12 mt-3">
                    <h6 class="fw-bold text-primary">📌 Étape 2 : Sélectionner le poste</h6>
                    <hr>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Poste *</label>
                    <select name="poste_id" id="poste_id" class="form-select" required>
                        <option value="">Sélectionner d'abord un service</option>
                    </select>
                    @error('poste_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ÉTAPE 3 : Créneau horaire --}}
                <div class="col-md-12 mt-3">
                    <h6 class="fw-bold text-primary">🕐 Étape 3 : Choisir le créneau horaire</h6>
                    <hr>
                </div>

                <div class="col-md-12">
                    <div id="horaires-container">
                        <p class="text-muted text-center py-3">Sélectionnez d'abord un service et un poste</p>
                    </div>
                    @error('horaire_type_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ÉTAPE 4 : Employés --}}
                <div class="col-md-12 mt-3">
                    <h6 class="fw-bold text-primary">👥 Étape 4 : Sélectionner les employés</h6>
                    <hr>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">Employés *</label>
                    <select name="employe_ids[]" id="employe_ids" class="form-select select2-multi" multiple>
                        <option value="">Sélectionner d'abord un poste</option>
                    </select>
                    @error('employe_ids')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                    <small class="text-muted">Sélectionnez les employés à planifier sur ce créneau</small>
                </div>

                {{-- Récapitulatif --}}
                <div class="col-md-12 mt-3">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Récapitulatif :</strong>
                        <span id="recap-text">Aucune sélection</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Générer le planning
                </button>
                <a href="{{ route('planning.index') }}" class="btn btn-secondary btn-lg">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- jQuery d'abord --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Select2 ensuite --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ Page planning.create chargée');
        
        // SELECT2 - initialisation
        if (typeof $ !== 'undefined') {
            $('.select2-multi').select2({
                placeholder: 'Sélectionner des employés',
                allowClear: true,
                width: '100%'
            });
            console.log('✅ Select2 initialisé');
        }

        // DÉFINIR LA DATE PAR DÉFAUT
        var now = new Date();
        var day = now.getDay();
        var diff = now.getDate() - day + (day === 0 ? -6 : 1);
        var monday = new Date(now.setDate(diff));
        
        var dateInput = document.createElement('input');
        dateInput.type = 'hidden';
        dateInput.name = 'date_debut';
        dateInput.value = monday.toISOString().split('T')[0];
        document.getElementById('planningForm').appendChild(dateInput);
        console.log('📅 Date de début:', dateInput.value);

        // ============================================================
        // CHARGER LES POSTES PAR SERVICE
        // ============================================================
        document.getElementById('service_id').addEventListener('change', function() {
            var serviceId = this.value;
            var posteSelect = document.getElementById('poste_id');
            
            console.log('🔍 Service sélectionné ID:', serviceId);
            
            posteSelect.innerHTML = '<option value="">Chargement...</option>';
            document.getElementById('horaires-container').innerHTML = '<p class="text-muted text-center py-3">Chargement des créneaux...</p>';
            document.getElementById('employe_ids').innerHTML = '';
            updateRecap();

            if (serviceId) {
                // Charger les postes
                var url = '/planning/api/job-titles-by-department/' + serviceId;
                console.log('📡 Appel API postes:', url);
                
                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('✅ Postes reçus:', data);
                        posteSelect.innerHTML = '<option value="">Sélectionner un poste</option>';
                        if (data && data.length > 0) {
                            data.forEach(function(poste) {
                                posteSelect.innerHTML += '<option value="' + poste.id + '">' + poste.name + '</option>';
                            });
                            console.log('✅ ' + data.length + ' postes chargés');
                        } else {
                            posteSelect.innerHTML = '<option value="">Aucun poste trouvé</option>';
                        }
                    })
                    .catch(error => {
                        console.error('❌ Erreur postes:', error);
                        posteSelect.innerHTML = '<option value="">Erreur: ' + error.message + '</option>';
                    });

                // ✅ CHARGER LES EMPLOYÉS - CORRIGÉ (sans Prenom)
                var urlEmp = '/planning/api/employees-by-service/' + serviceId;
                console.log('📡 Appel API employés:', urlEmp);
                
                fetch(urlEmp)
                    .then(response => {
                        console.log('📥 Status employés:', response.status);
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('✅ Employés reçus:', data);
                        
                        var empSelect = document.getElementById('employe_ids');
                        empSelect.innerHTML = '';
                        
                        if (data && data.length > 0) {
                            data.forEach(function(emp) {
                                // ✅ CORRECTION : Utiliser 'Nom' uniquement
                                var nom = emp.Nom || 'Inconnu';
                                empSelect.innerHTML += '<option value="' + emp.ID + '">' + nom + '</option>';
                            });
                            console.log('✅ ' + data.length + ' employés chargés');
                        } else {
                            empSelect.innerHTML = '<option value="">Aucun employé trouvé</option>';
                        }
                        
                        if (typeof $ !== 'undefined') {
                            $(empSelect).trigger('change');
                        }
                        updateRecap();
                    })
                    .catch(error => {
                        console.error('❌ Erreur chargement employés:', error);
                        document.getElementById('employe_ids').innerHTML = '<option value="">Erreur: ' + error.message + '</option>';
                    });
            } else {
                posteSelect.innerHTML = '<option value="">Sélectionner un service d\'abord</option>';
                document.getElementById('horaires-container').innerHTML = '<p class="text-muted text-center py-3">Sélectionnez d\'abord un service</p>';
                document.getElementById('employe_ids').innerHTML = '<option value="">Sélectionner un service d\'abord</option>';
            }
        });

        // ============================================================
        // CHARGER LES CRÉNEAUX PAR POSTE
        // ============================================================
        document.getElementById('poste_id').addEventListener('change', function() {
            var posteId = this.value;
            console.log('🔍 Poste sélectionné ID:', posteId);
            
            if (posteId) {
                loadHoraires(posteId);
                filterEmployeesByPoste(posteId);
            } else {
                document.getElementById('horaires-container').innerHTML = '<p class="text-muted text-center py-3">Sélectionnez un poste pour voir les créneaux</p>';
                updateRecap();
            }
        });

        function loadHoraires(posteId) {
            var container = document.getElementById('horaires-container');
            if (!posteId) return;
            
            container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>';
            
            var url = '/planning/api/work-schedules-by-job-title/' + posteId;
            console.log('📡 Appel API créneaux:', url);

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Créneaux reçus:', data);
                    if (data && data.length > 0) {
                        var html = '<div class="row g-3">';
                        data.forEach(function(horaire, index) {
                            var jours = horaire.jours_travailles ? horaire.jours_travailles.split(',') : [];
                            var joursLibelles = jours.map(function(j) {
                                return '<span class="badge-jour active">' + j.charAt(0).toUpperCase() + j.slice(1) + '</span>';
                            }).join(' ');
                            
                            var tousLesJours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                            var joursNonTravailles = tousLesJours.filter(function(j) {
                                return !jours.includes(j);
                            });
                            var joursNonTravaillesLibelles = joursNonTravailles.map(function(j) {
                                return '<span class="badge-jour inactive">' + j.charAt(0).toUpperCase() + j.slice(1) + '</span>';
                            }).join(' ');
                            
                            var horaireLabel = horaire.heure_debut + ' - ' + horaire.heure_fin;
                            if (horaire.deuxieme_debut && horaire.deuxieme_fin) {
                                horaireLabel += ' / ' + horaire.deuxieme_debut + ' - ' + horaire.deuxieme_fin;
                            }
                            
                            var pauseLabel = horaire.pause_debut && horaire.pause_fin ? 
                                horaire.pause_debut + ' - ' + horaire.pause_fin : 'Aucune pause';
                            
                            html += `
                                <div class="col-md-6 col-lg-4">
                                    <div class="horaire-card ${index === 0 ? 'active' : ''}" data-id="${horaire.id}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="badge bg-primary">Poste #${horaire.poste_id}</span>
                                                ${horaire.par_defaut ? '<span class="badge bg-success ms-1">Défaut</span>' : ''}
                                            </div>
                                            <input type="radio" name="horaire_type_id" value="${horaire.id}" ${index === 0 ? 'checked' : ''}>
                                        </div>
                                        <div class="mt-2">
                                            <strong>🕐 ${horaireLabel}</strong>
                                        </div>
                                        <div class="horaire-info mt-1">
                                            <i data-lucide="coffee" class="w-3 h-3"></i> Pause: ${pauseLabel}
                                        </div>
                                        <div class="mt-2">
                                            <strong>Jours travaillés :</strong>
                                            <div class="mt-1">${joursLibelles}</div>
                                        </div>
                                        <div class="mt-1">
                                            <span class="text-muted" style="font-size:10px;">Jours de repos : ${joursNonTravaillesLibelles}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                        
                        document.querySelectorAll('.horaire-card').forEach(function(card) {
                            card.addEventListener('click', function() {
                                document.querySelectorAll('.horaire-card').forEach(function(c) {
                                    c.classList.remove('active');
                                });
                                this.classList.add('active');
                                this.querySelector('input[type="radio"]').checked = true;
                                updateRecap();
                            });
                        });
                        
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                        updateRecap();
                    } else {
                        container.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Aucun créneau horaire défini pour ce poste.
                                <a href="{{ route('planning.horaires-types.create') }}" class="alert-link">Créer un créneau</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('❌ Erreur créneaux:', error);
                    container.innerHTML = '<p class="text-danger text-center py-3">Erreur: ' + error.message + '</p>';
                });
        }

        // ============================================================
        // FILTRER LES EMPLOYÉS PAR POSTE (CORRIGÉ)
        // ============================================================
        function filterEmployeesByPoste(posteId) {
            console.log('🔍 Filtrage des employés pour le poste ID:', posteId);
            
            var serviceId = document.getElementById('service_id').value;
            if (!serviceId) {
                console.warn('⚠️ Aucun service sélectionné');
                return;
            }
            
            var url = '/planning/api/employees-by-service/' + serviceId;
            console.log('📡 Appel API pour filtrer les employés:', url);
            
            fetch(url)
                .then(response => {
                    console.log('📥 Status filtrage:', response.status);
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Données employés pour filtrage:', data);
                    
                    var empSelect = document.getElementById('employe_ids');
                    empSelect.innerHTML = '';
                    var found = false;
                    
                    if (data && data.length > 0) {
                        data.forEach(function(emp) {
                            // ✅ CORRECTION : Utiliser 'Nom' uniquement
                            if (parseInt(emp.job_title_id) === parseInt(posteId)) {
                                empSelect.innerHTML += '<option value="' + emp.ID + '">' + emp.Nom + '</option>';
                                found = true;
                            }
                        });
                    }
                    
                    if (!found) {
                        empSelect.innerHTML = '<option value="">Aucun employé pour ce poste</option>';
                        console.warn('⚠️ Aucun employé trouvé pour le poste ID:', posteId);
                    } else {
                        console.log('✅ Employés filtrés avec succès');
                    }
                    
                    if (typeof $ !== 'undefined') {
                        $(empSelect).trigger('change');
                    }
                    updateRecap();
                })
                .catch(error => {
                    console.error('❌ Erreur filtrage employés:', error);
                    document.getElementById('employe_ids').innerHTML = '<option value="">Erreur: ' + error.message + '</option>';
                });
        }

        // ============================================================
        // RÉCAPITULATIF
        // ============================================================
        function updateRecap() {
            var service = document.getElementById('service_id').value;
            var poste = document.getElementById('poste_id').value;
            var horaireChecked = document.querySelector('input[name="horaire_type_id"]:checked');
            var empSelect = document.getElementById('employe_ids');
            
            var serviceText = document.getElementById('service_id').options[document.getElementById('service_id').selectedIndex]?.text || '';
            var posteText = document.getElementById('poste_id').options[document.getElementById('poste_id').selectedIndex]?.text || '';
            
            var employes = [];
            for (var i = 0; i < empSelect.options.length; i++) {
                if (empSelect.options[i].selected) {
                    employes.push(empSelect.options[i].text);
                }
            }
            
            var recap = '';
            if (serviceText && serviceText !== 'Sélectionner un service') {
                recap += 'Service: <strong>' + serviceText + '</strong> | ';
            }
            if (posteText && posteText !== 'Sélectionner un poste' && posteText !== 'Sélectionner d\'abord un service') {
                recap += 'Poste: <strong>' + posteText + '</strong> | ';
            }
            if (horaireChecked) {
                var horaireCard = document.querySelector('.horaire-card.active');
                var horaireText = horaireCard ? horaireCard.querySelector('.mt-2 strong')?.textContent || 'Créneau sélectionné' : 'Créneau sélectionné';
                recap += 'Créneau: <strong>' + horaireText + '</strong> | ';
            }
            if (employes.length > 0) {
                recap += 'Employés: <strong>' + employes.join(', ') + '</strong>';
            } else {
                recap += 'Aucun employé sélectionné';
            }
            
            document.getElementById('recap-text').innerHTML = recap || 'Aucune sélection';
        }

        // ============================================================
        // VALIDATION
        // ============================================================
        document.getElementById('planningForm').addEventListener('submit', function(e) {
            var empSelect = document.getElementById('employe_ids');
            var selected = [];
            for (var i = 0; i < empSelect.options.length; i++) {
                if (empSelect.options[i].selected) selected.push(empSelect.options[i].value);
            }
            
            if (selected.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un employé.');
                return false;
            }
            
            if (!document.querySelector('input[name="horaire_type_id"]:checked')) {
                e.preventDefault();
                alert('Veuillez sélectionner un créneau horaire.');
                return false;
            }
            
            if (!document.getElementById('service_id').value) {
                e.preventDefault();
                alert('Veuillez sélectionner un service.');
                return false;
            }
            
            if (!document.getElementById('poste_id').value) {
                e.preventDefault();
                alert('Veuillez sélectionner un poste.');
                return false;
            }
            
            return true;
        });

        // ============================================================
        // ÉCOUTEURS POUR RÉCAPITULATIF
        // ============================================================
        document.getElementById('service_id').addEventListener('change', function() {
            setTimeout(updateRecap, 500);
        });
        document.getElementById('poste_id').addEventListener('change', function() {
            setTimeout(updateRecap, 500);
        });
        document.getElementById('employe_ids').addEventListener('change', updateRecap);
        document.addEventListener('change', function(e) {
            if (e.target.name === 'horaire_type_id') {
                setTimeout(updateRecap, 100);
            }
        });
        
        setTimeout(updateRecap, 500);
        console.log('✅ Initialisation terminée');
    });
</script>
@endpush