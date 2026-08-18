{{-- resources/views/employes/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Modifier l'employé : {{ $employe->num_mat ? "N° Matricule " . $employe->num_mat . " - " : "" }} {{ $employe->Nom }} 
            </h2>
            <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('employes.update', $employe->ID) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <!-- num_mat - Super Admin ET Simple Admin -->
                        <div class="form-group mb-3">
                            <label for="num_mat">Numéro matricule</label>
                            <input type="text" 
                                    class="form-control @error('num_mat') is-invalid @enderror" 
                                    id="num_mat" 
                                    name="num_mat" 
                                    value="{{ old('num_mat', $employe->num_mat) }}" 
                                    maxlength="50">
                            @error('num_mat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Optionnel — unique par siège</small>
                        </div>

                        <!-- Nom - TOUS LES UTILISATEURS PEUVENT MODIFIER -->
                        <div class="form-group mb-3">
                            <label for="Nom">Nom <span class="text-danger">*</span></label>
                            <input type="text" 
                                    class="form-control @error('Nom') is-invalid @enderror" 
                                    id="Nom" 
                                    name="Nom" 
                                    value="{{ old('Nom', $employe->Nom) }}" 
                                    required>
                            @error('Nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email - TOUS LES UTILISATEURS -->
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email', $employe->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone - TOUS LES UTILISATEURS -->
                        <div class="form-group mb-3">
                            <label for="telephone">Téléphone</label>
                            <input type="text" 
                                    class="form-control @error('telephone') is-invalid @enderror" 
                                    id="telephone" 
                                    name="telephone" 
                                    value="{{ old('telephone', $employe->telephone) }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ============ ORGANISATION ============ -->
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-light text-primary">
                                <i class="bi bi-diagram-3"></i> Organisation
                            </div>
                            <div class="card-body row">

                                <!-- Service -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="department_id">Service</label>
                                    <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                        <option value="">-- Non classé --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id', $employe->department_id) == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                                </div>

                                <!-- Poste -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="job_title_id">Poste</label>
                                    <select name="job_title_id" id="job_title_id" class="form-select @error('job_title_id') is-invalid @enderror">
                                        <option value="">-- Non défini --</option>
                                        @foreach($jobTitles as $jt)
                                            <option value="{{ $jt->id }}" {{ old('job_title_id', $employe->job_title_id) == $jt->id ? 'selected' : '' }}>
                                                {{ $jt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('job_title_id')" class="mt-2" />
                                </div>

                                <!-- Niveau Hiérarchique -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="hierarchy_level_id">Niveau Hiérarchique</label>
                                    <select name="hierarchy_level_id" id="hierarchy_level_id" class="form-select @error('hierarchy_level_id') is-invalid @enderror">
                                        <option value="">-- Non classé --</option>
                                        @foreach($hierarchyLevels as $lvl)
                                            <option value="{{ $lvl->id }}" {{ old('hierarchy_level_id', $employe->hierarchy_level_id) == $lvl->id ? 'selected' : '' }}>
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
                                            <option value="{{ $mgr->ID }}" {{ old('manager_id', $employe->manager_id) == $mgr->ID ? 'selected' : '' }}>
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
                                        <option value="actif" {{ old('employment_status', $employe->employment_status ?? 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="suspendu" {{ old('employment_status', $employe->employment_status ?? '') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                        <option value="sorti" {{ old('employment_status', $employe->employment_status ?? '') == 'sorti' ? 'selected' : '' }}>Sorti</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('employment_status')" class="mt-2" />
                                </div>

                                <!-- Date d'embauche -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="hire_date">Date d'embauche</label>
                                    <input type="date" 
                                            class="form-control @error('hire_date') is-invalid @enderror" 
                                            id="hire_date" 
                                            name="hire_date" 
                                            value="{{ old('hire_date', $employe->hire_date ? \Carbon\Carbon::parse($employe->hire_date)->format('Y-m-d') : '') }}">
                                    <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                                </div>

                            </div>
                        </div>
                        <!-- ============ FIN ORGANISATION ============ -->

                        <!-- SiegeID -->
                        <div class="form-group mb-3">
                            <label for="SiegeID">Siège <span class="text-danger">*</span></label>
                            @if(Auth::user()->IsSuperAdmin)
                                <select class="form-control @error('SiegeID') is-invalid @enderror" 
                                        id="SiegeID" 
                                        name="SiegeID" 
                                        required>
                                    <option value="">Sélectionnez un siège</option>
                                    @foreach($sieges as $siege)
                                        <option value="{{ $siege->ID }}" 
                                                {{ old('SiegeID', $employe->SiegeID) == $siege->ID ? 'selected' : '' }}>
                                            {{ $siege->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select class="form-control" disabled>
                                    @foreach($sieges as $siege)
                                        <option value="{{ $siege->ID }}" {{ $employe->SiegeID == $siege->ID ? 'selected' : '' }}>
                                            {{ $siege->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Modification réservée aux super administrateurs</small>
                            @endif
                            <x-input-error :messages="$errors->get('SiegeID')" class="mt-2" />
                        </div>
                        
                        @if(Auth::user()->IsSuperAdmin)
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BadgeID -->
                                <div class="form-group mb-3">
                                    <label for="BadgeID">Badge ID</label>
                                    <input type="password" 
                                            class="form-control @error('BadgeID') is-invalid @enderror" 
                                            id="BadgeID" 
                                            name="BadgeID" 
                                            maxlength="25"
                                            placeholder="Laissez vide pour conserver l'actuel">
                                    <small class="form-text text-muted">Laissez vide pour conserver l'actuel</small>
                                    <x-input-error :messages="$errors->get('BadgeID')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->isSimpleAdmin())
                            @if(empty($employe->Pin))
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group mb-3">
                                        <label for="Pin">Code PIN (6 chiffres)</label>
                                        <input type="password" 
                                            class="form-control @error('Pin') is-invalid @enderror" 
                                            id="Pin" 
                                            name="Pin" 
                                            value="{{ old('Pin', $employe->Pin) }}" 
                                            maxlength="6"
                                            inputmode="numeric"
                                            pattern="[0-9]{6}"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <x-input-error :messages="$errors->get('Pin')" class="mt-2" />
                                        <small class="form-text text-muted">Code PIN à 6 chiffres (optionnel)</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif

                        <!-- Actived -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                @if(Auth::user()->IsSuperAdmin)
                                    <input class="form-check-input @error('Actived') is-invalid @enderror" 
                                            type="checkbox" 
                                            value="1" 
                                            id="Actived" 
                                            name="Actived" 
                                            {{ old('Actived', $employe->Actived) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="Actived">Activer</label>
                                @else
                                    <input class="form-check-input" 
                                            type="checkbox" 
                                            id="Actived_display" 
                                            {{ $employe->Actived ? 'checked' : '' }}
                                            disabled>
                                    <input type="hidden" name="Actived" value="{{ $employe->Actived ? '1' : '0' }}">
                                    <label class="form-check-label" for="Actived_display">
                                        Activer <small class="text-muted">(réservé aux super administrateurs)</small>
                                    </label>
                                @endif
                                <x-input-error :messages="$errors->get('Actived')" class="mt-2" />
                            </div>
                        </div>                               
                            
                    </div>

                    {{-- Bouton submit pour TOUS LES UTILISATEURS --}}
                    @if (auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                    @endif
                </form>

                @if (auth()->user()->isSimpleAdmin())
                    @if(!empty($employe->Pin))                
                        <hr>
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-info"
                                    onclick="setResetPinAction('{{ route('employes.reset-pin', $employe->ID) }}', '{{ $employe->Nom }}')"
                                    data-bs-toggle="modal"
                                    data-bs-target="#resetPinModal"
                                    title="Réinitialiser le PIN">
                                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser le Code Pin
                            </button>
                        </div>
                    @endif
                @endif

                <!-- Modal Reset PIN -->
                <div class="modal fade" id="resetPinModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Réinitialisation du code PIN</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Voulez-vous vraiment réinitialiser le code PIN de <strong id="details_employee_pin"></strong> ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <form id="resetPinForm" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">Réinitialiser</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Validation du PIN pour Super Admin
        @if(Auth::user()->IsSuperAdmin)
        const pinInput = document.getElementById('Pin');
        if (pinInput) {
            pinInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6);
            });
        }
        @endif

        function setResetPinAction(url, nom) {
            document.getElementById('resetPinForm').action = url;
            document.getElementById('details_employee_pin').textContent = nom;
        }

        // AJAX : charger Services & Managers selon Siège (uniquement si SuperAdmin)
        @if(Auth::user()->IsSuperAdmin)
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('SiegeID');
            const deptSelect = document.getElementById('department_id');
            const mgrSelect  = document.getElementById('manager_id');

            if (!siteSelect || siteSelect.disabled) return;

            function loadSiteData(siteId) {
                if (!siteId) {
                    if (deptSelect) deptSelect.innerHTML = '<option value="">-- Non classé --</option>';
                    if (mgrSelect)  mgrSelect.innerHTML  = '<option value="">-- Responsable par défaut --</option>';
                    return;
                }

                if (deptSelect) {
                    fetch(`/api/departments-by-site/${siteId}`)
                        .then(r => r.json())
                        .then(data => {
                            const currentVal = "{{ old('department_id', $employe->department_id) }}";
                            deptSelect.innerHTML = '<option value="">-- Non classé --</option>';
                            data.forEach(d => {
                                const selected = d.id == currentVal ? 'selected' : '';
                                deptSelect.innerHTML += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                            });
                        })
                        .catch(err => console.error('Erreur chargement services:', err));
                }

                if (mgrSelect) {
                    fetch(`/api/managers-by-site/${siteId}`)
                        .then(r => r.json())
                        .then(data => {
                            const currentVal = "{{ old('manager_id', $employe->manager_id) }}";
                            mgrSelect.innerHTML = '<option value="">-- Responsable par défaut --</option>';
                            data.forEach(e => {
                                const selected = e.id == currentVal ? 'selected' : '';
                                mgrSelect.innerHTML += `<option value="${e.id}" ${selected}>${e.name}</option>`;
                            });
                        })
                        .catch(err => console.error('Erreur chargement managers:', err));
                }
            }

            // Charger les données initiales
            const initialSiteId = siteSelect.value;
            if (initialSiteId) {
                loadSiteData(initialSiteId);
            }

            siteSelect.addEventListener('change', function() {
                loadSiteData(this.value);
            });
        });
        @endif
    </script>
    @endpush
</x-app-layout>