{{-- resources/views/employes/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau employé') }}
            </h2>
            <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('employes.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                    
                        <!-- num_mat -->
                        <div class="form-group mb-3">
                            <label for="num_mat">Numéro matricule</label>
                            <input type="text" 
                                    class="form-control @error('num_mat') is-invalid @enderror" 
                                    id="num_mat" 
                                    name="num_mat" 
                                    value="{{ old('num_mat') }}" 
                                    maxlength="50">
                            <x-input-error :messages="$errors->get('num_mat')" class="mt-2" />
                            <small class="form-text text-muted">Optionnel — unique par siège</small>
                        </div>

                        <!-- Nom -->
                        <div class="form-group mb-3">
                            <label for="Nom">Nom <span class="text-danger">*</span></label>
                            <input type="text" 
                                    class="form-control @error('Nom') is-invalid @enderror" 
                                    id="Nom" 
                                    name="Nom" 
                                    value="{{ old('Nom') }}" 
                                    required 
                                    autofocus>
                            <x-input-error :messages="$errors->get('Nom')" class="mt-2" />
                        </div>

                        <!-- ============ ORGANISATION ============ -->
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-light text-primary">
                                <i class="bi bi-diagram-3"></i> Organisation
                            </div>
                            <div class="card-body row">

                                <!-- Service -->
                               {{-- resources/views/employes/create.blade.php --}}

<!-- Sélection du service -->
<div class="col-md-6">
    <x-input-label for="department_id" :value="__('Service')" />
    <select id="department_id" name="department_id" class="form-select">
        <option value="">{{ __('Aucun service') }}</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Sélection du poste -->
<div class="col-md-6">
    <x-input-label for="job_title_id" :value="__('Poste')" />
    <select id="job_title_id" name="job_title_id" class="form-select">
        <option value="">{{ __('Aucun poste') }}</option>
        @foreach($jobTitles as $job)
            <option value="{{ $job->id }}" {{ old('job_title_id') == $job->id ? 'selected' : '' }}>
                {{ $job->name }}
            </option>
        @endforeach
    </select>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const jobTitleSelect = document.getElementById('job_title_id');

    function loadJobTitles(departmentId) {
        // Si aucun service, on peut charger tous les postes (ou vider)
        let url = '{{ route("admin.get.job-titles.by.department") }}?department_id=' + (departmentId || '');
        fetch(url)
            .then(response => response.json())
            .then(data => {
                jobTitleSelect.innerHTML = '<option value="">{{ __("Aucun poste") }}</option>';
                data.forEach(job => {
                    const option = document.createElement('option');
                    option.value = job.id;
                    option.textContent = job.name;
                    jobTitleSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }

    departmentSelect.addEventListener('change', function() {
        loadJobTitles(this.value);
    });

    // Charger initialement si un service est pré-sélectionné
    if (departmentSelect.value) {
        loadJobTitles(departmentSelect.value);
    }
});
</script>
@endpush

                                <!-- Niveau Hiérarchique -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="hierarchy_level_id">Niveau Hiérarchique</label>
                                    <select name="hierarchy_level_id" id="hierarchy_level_id" class="form-select @error('hierarchy_level_id') is-invalid @enderror">
                                        <option value="">-- Non classé --</option>
                                        @foreach($hierarchyLevels as $lvl)
                                            <option value="{{ $lvl->id }}" {{ old('hierarchy_level_id') == $lvl->id ? 'selected' : '' }}>
                                                {{ $lvl->name }} (Rang {{ $lvl->rank }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('hierarchy_level_id')" class="mt-2" />
                                </div>

                                <!-- Manager Direct -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="manager_id">Manager Direct</label>
                                    <select name="manager_id" id="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
                                        <option value="">-- Responsable par défaut --</option>
                                        @foreach($managers as $mgr)
                                            <option value="{{ $mgr->ID }}" {{ old('manager_id') == $mgr->ID ? 'selected' : '' }}>
                                                {{ $mgr->Nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                                </div>

                                <!-- Statut -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="employment_status">Statut</label>
                                    <select name="employment_status" id="employment_status" class="form-select @error('employment_status') is-invalid @enderror">
                                        <option value="actif" {{ old('employment_status', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="suspendu" {{ old('employment_status') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        <option value="sorti" {{ old('employment_status') == 'sorti' ? 'selected' : '' }}>Sorti</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('employment_status')" class="mt-2" />
                                </div>

                                <!-- Date d'embauche -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="hire_date">Date d'embauche <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" 
                                           name="hire_date" 
                                           value="{{ old('hire_date') }}" 
                                           required>
                                    <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                                    <small class="form-text text-muted">Nécessaire pour le calcul des droits de congés</small>
                                </div>

                            </div>
                        </div>
                        <!-- ============ FIN ORGANISATION ============ -->

                        <!-- BadgeID - SEULEMENT SUPER ADMIN -->
                        @if(Auth::user()->IsSuperAdmin)
                        <div class="form-group mb-3">
                            <label for="BadgeID">Badge ID <span class="text-danger">*</span></label>
                            <input type="password" 
                                    class="form-control @error('BadgeID') is-invalid @enderror" 
                                    id="BadgeID" 
                                    name="BadgeID" 
                                    value="{{ old('BadgeID') }}" 
                                    maxlength="25"
                                    required>
                            <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
                        </div>
                        @endif

                        <!-- SiegeID -->
                        <div class="form-group mb-3">
                            <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                            <select class="form-control @error('SiegeID') is-invalid @enderror" 
                                    id="SiegeID" 
                                    name="SiegeID" 
                                    required>
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ $siege_id == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>

                        <!-- Pin - SEULEMENT SIMPLE ADMIN -->
                        @if(Auth::user()->isSimpleAdmin())
                        <div class="form-group mb-3">
                            <label for="Pin">Code PIN</label>
                            <input type="password" 
                                            class="form-control @error('Pin') is-invalid @enderror" 
                                            id="Pin" 
                                            name="Pin" 
                                            value="{{ old('Pin') }}" 
                                            maxlength="6"
                                            inputmode="numeric"
                                            pattern="[0-9]{6}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                            <small class="form-text text-muted">Code PIN à 6 chiffres (optionnel)</small>
                        </div>
                        @endif

                        <!-- Actived -->
                        <div class="form-group my-3">
                            <div class="form-check">
                                <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                        type="checkbox" 
                                        value="1" 
                                        id="Actived" 
                                        name="Actived" 
                                        {{ old('Actived') ? 'checked' : '' }}
                                        {{ !Auth::user()->IsSuperAdmin ? 'disabled' : '' }}>
                                <label class="form-check-label" for="Actived">
                                    Activer
                                    @if(!Auth::user()->IsSuperAdmin)
                                        <small class="text-muted">(réservé aux super administrateurs)</small>
                                    @endif
                                </label>
                                <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                            </div>
                        </div>
                        
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validation du PIN
        const pinInput = document.getElementById('Pin');
        if (pinInput) {
            pinInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });
        }

        // AJAX : charger Services & Managers selon Siège
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('SiegeID');
            const deptSelect = document.getElementById('department_id');
            const mgrSelect  = document.getElementById('manager_id');

            if (!siteSelect) return;

            siteSelect.addEventListener('change', function() {
                const siteId = this.value;
                if (!siteId) {
                    if (deptSelect) deptSelect.innerHTML = '<option value="">-- Non classé --</option>';
                    if (mgrSelect)  mgrSelect.innerHTML  = '<option value="">-- Responsable par défaut --</option>';
                    return;
                }

                if (deptSelect) {
                    fetch(`/api/departments-by-site/${siteId}`)
                        .then(r => r.json())
                        .then(data => {
                            deptSelect.innerHTML = '<option value="">-- Non classé --</option>';
                            data.forEach(d => {
                                deptSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
                            });
                        });
                }

                if (mgrSelect) {
                    fetch(`/api/managers-by-site/${siteId}`)
                        .then(r => r.json())
                        .then(data => {
                            mgrSelect.innerHTML = '<option value="">-- Responsable par défaut --</option>';
                            data.forEach(e => {
                                mgrSelect.innerHTML += `<option value="${e.id}">${e.name}</option>`;
                            });
                        });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>