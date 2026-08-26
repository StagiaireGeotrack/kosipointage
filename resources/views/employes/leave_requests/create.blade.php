{{-- resources/views/employe/leave_requests/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> {{ __('Nouvelle demande de congé') }}
            </h2>
            <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    {{ __('Remplissez le formulaire ci-dessous pour faire une demande de congé. La durée sera calculée automatiquement selon les règles configurées.') }}
                </div>

                <form method="POST" action="{{ route('employe.leave-requests.store') }}" id="leaveRequestForm" enctype="multipart/form-data">
                    @csrf

                    <!-- ID Employé caché -->
                    <input type="hidden" id="employee_id" value="{{ $employee->ID ?? auth()->user()->employee->ID ?? '' }}">

                    <!-- Informations principales -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="period_id" :value="__('Période')" />
                            <span class="text-danger">*</span>
                            <select id="period_id" name="period_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez une période') }}</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="start_date" :value="__('Date de début')" />
                            <span class="text-danger">*</span>
                            <input type="date" id="start_date" name="start_date" class="form-control mt-1" 
                                   value="{{ old('start_date') }}" required>
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="end_date" :value="__('Date de fin')" />
                            <span class="text-danger">*</span>
                            <input type="date" id="end_date" name="end_date" class="form-control mt-1" 
                                   value="{{ old('end_date') }}" required>
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Durée calculée automatiquement -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="duration_display" :value="__('Durée calculée')" />
                            <div id="duration_display" class="form-control mt-1" style="background-color: #f3f4f6; font-weight: bold; padding: 8px 12px; min-height: 38px;">
                                <span class="text-muted">-- jours</span>
                            </div>
                            <input type="hidden" id="duration_hidden" name="duration" value="0">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                {{ __('La durée est calculée automatiquement selon : type de congé, politique, jours fériés et week-ends') }}
                            </small>
                            <div id="duration_details" class="mt-1 small text-muted" style="display: none;"></div>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="reason" :value="__('Motif (optionnel)')" />
                            <input type="text" id="reason" name="reason" class="form-control mt-1" 
                                   value="{{ old('reason') }}" placeholder="{{ __('Ex: Vacances, Rendez-vous...') }}">
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <x-input-label for="comment" :value="__('Commentaire (optionnel)')" />
                            <textarea id="comment" name="comment" class="form-control mt-1" rows="3" 
                                      placeholder="{{ __('Informations supplémentaires...') }}">{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Pièces jointes -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <div class="border p-3 rounded-3" style="background-color: #f8f9fa;">
                                <h5 class="mb-3">
                                    <i class="bi bi-paperclip"></i> {{ __('Pièces jointes') }}
                                    <span class="badge bg-secondary rounded-pill ms-2" id="fileCount">0</span>
                                </h5>
                                
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle"></i>
                                    {{ __('Ajoutez des justificatifs si nécessaire (certificat médical, acte de mariage, etc.)') }}
                                </p>

                                <!-- ✅ Zone de dépôt -->
                                <div id="dropZone" class="drop-zone p-4 text-center rounded-3" 
                                     style="border: 2px dashed #dee2e6; cursor: pointer; transition: all 0.3s;">
                                    <i class="bi bi-cloud-upload fs-1 d-block mb-2" style="color: #4f8a8b;"></i>
                                    <p class="mb-1 fw-semibold">{{ __('Glissez-déposez vos fichiers ici') }}</p>
                                    <p class="text-muted small">{{ __('ou') }}</p>
                                    <button type="button" class="btn btn-sm" style="background-color: #4f8a8b; color: white; border: none; border-radius: 6px; padding: 6px 16px;" onclick="document.getElementById('fileInput').click();">
                                        <i class="bi bi-folder2-open"></i> {{ __('Parcourir...') }}
                                    </button>
                                    <input type="file" id="fileInput" name="attachments[]" multiple 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.txt" 
                                           style="display: none;" onchange="handleFileSelect(this.files)">
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-check-circle"></i> 
                                            {{ __('Formats acceptés: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX, TXT') }}
                                            <br>
                                            <i class="bi bi-hdd"></i> {{ __('Taille maximale: 5MB par fichier') }}
                                        </small>
                                    </div>
                                </div>

                                <!-- ✅ Liste des fichiers sélectionnés -->
                                <div id="fileList" class="mt-3" style="display: none;">
                                    <h6 class="fw-semibold">
                                        <i class="bi bi-files"></i> {{ __('Fichiers sélectionnés') }}
                                    </h6>
                                    <div id="fileListItems" class="list-group"></div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="clearAllFiles()">
                                            <i class="bi bi-trash"></i> {{ __('Tout supprimer') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- ✅ Champ caché pour stocker les noms des fichiers -->
                                <input type="hidden" id="file_names" name="file_names" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;">
                            <i class="bi bi-save"></i> {{ __('Enregistrer le brouillon') }}
                        </button>
                        <button type="submit" name="submit" value="1" class="btn" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;">
                            <i class="bi bi-send"></i> {{ __('Soumettre') }}
                        </button>
                        <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============================================
            // ÉLÉMENTS DU FORMULAIRE
            // ============================================
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const periodSelect = document.getElementById('period_id');
            const durationDisplay = document.getElementById('duration_display');
            const durationHidden = document.getElementById('duration_hidden');
            const durationDetails = document.getElementById('duration_details');
            const employeeId = document.getElementById('employee_id').value;

            // ============================================
            // CALCUL DE LA DURÉE
            // ============================================
            function calculateDuration() {
                const data = {
                    employee_id: employeeId,
                    leave_type_id: leaveTypeSelect.value,
                    start_date: startDateInput.value,
                    end_date: endDateInput.value,
                    period_id: periodSelect.value
                };

                if (!data.start_date || !data.end_date || !data.leave_type_id) {
                    durationDisplay.innerHTML = '<span class="text-muted">-- jours</span>';
                    durationHidden.value = '0';
                    durationDetails.style.display = 'none';
                    return;
                }

                if (new Date(data.end_date) < new Date(data.start_date)) {
                    durationDisplay.innerHTML = '<span class="text-danger">⚠️ Date de fin antérieure</span>';
                    durationHidden.value = '0';
                    durationDetails.style.display = 'none';
                    return;
                }

                durationDisplay.innerHTML = '<span class="text-warning">⏳ Calcul en cours...</span>';

                const url = '{{ route("employe.leave-requests.calculate-duration") }}?' + new URLSearchParams(data);

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (response.status === 401) {
                        throw new Error('Session expirée, veuillez rafraîchir la page.');
                    }
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        durationDisplay.innerHTML = `<span class="text-success fw-bold">✅ ${data.duration_formatted}</span>`;
                        durationHidden.value = data.duration;
                        
                        if (data.details) {
                            durationDetails.innerHTML = JSON.stringify(data.details, null, 2);
                            durationDetails.style.display = 'block';
                        } else {
                            durationDetails.style.display = 'none';
                        }
                    } else {
                        durationDisplay.innerHTML = `<span class="text-danger">⚠️ ${data.message || 'Erreur de calcul'}</span>`;
                        durationHidden.value = '0';
                        durationDetails.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur API:', error);
                    durationDisplay.innerHTML = `<span class="text-danger">❌ ${error.message || 'Erreur de connexion'}</span>`;
                    durationHidden.value = '0';
                    durationDetails.style.display = 'none';
                });
            }

            // ============================================
            // ÉCOUTER LES CHANGEMENTS
            // ============================================
            leaveTypeSelect.addEventListener('change', calculateDuration);
            startDateInput.addEventListener('change', calculateDuration);
            endDateInput.addEventListener('change', calculateDuration);
            startDateInput.addEventListener('input', calculateDuration);
            endDateInput.addEventListener('input', calculateDuration);
            periodSelect.addEventListener('change', calculateDuration);

            if (startDateInput.value && endDateInput.value && leaveTypeSelect.value) {
                calculateDuration();
            }

            // ============================================
            // ✅ GESTION DES FICHIERS (DRAG & DROP) - CORRIGÉ
            // ============================================
            let selectedFiles = [];

            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            const fileListItems = document.getElementById('fileListItems');
            const fileCount = document.getElementById('fileCount');

            // Événements Drag & Drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            dropZone.addEventListener('dragover', () => {
                dropZone.style.borderColor = '#4f8a8b';
                dropZone.style.backgroundColor = '#e8f5e9';
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.style.borderColor = '#dee2e6';
                dropZone.style.backgroundColor = '#f8f9fa';
            });

            dropZone.addEventListener('drop', (e) => {
                dropZone.style.borderColor = '#dee2e6';
                dropZone.style.backgroundColor = '#f8f9fa';
                const files = e.dataTransfer.files;
                handleFiles(files);
            });

            // ✅ Gestion de la sélection de fichiers via le bouton
            function handleFileSelect(files) {
                handleFiles(files);
            }

            // ✅ Exposer la fonction globalement
            window.handleFileSelect = handleFileSelect;

            // ✅ Traitement des fichiers
            function handleFiles(files) {
                const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx', 'txt'];
                const maxSize = 5 * 1024 * 1024;
                
                let newFiles = [];
                let errors = [];

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const extension = file.name.split('.').pop().toLowerCase();
                    
                    if (!allowedExtensions.includes(extension)) {
                        errors.push(`"${file.name}" - Format non autorisé`);
                        continue;
                    }
                    
                    if (file.size > maxSize) {
                        errors.push(`"${file.name}" - Taille > 5MB`);
                        continue;
                    }
                    
                    const duplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (duplicate) {
                        errors.push(`"${file.name}" - Fichier déjà ajouté`);
                        continue;
                    }
                    
                    newFiles.push(file);
                }

                if (newFiles.length > 0) {
                    selectedFiles = [...selectedFiles, ...newFiles];
                    updateFileList();
                }
                
                if (errors.length > 0) {
                    alert('Erreurs :\n' + errors.join('\n'));
                }
                
                // ✅ Réinitialiser l'input pour permettre de sélectionner les mêmes fichiers
                fileInput.value = '';
            }

            // ✅ Mettre à jour l'affichage de la liste
            function updateFileList() {
                if (selectedFiles.length === 0) {
                    fileList.style.display = 'none';
                    fileCount.textContent = '0';
                    // ✅ Mettre à jour le champ caché
                    document.getElementById('file_names').value = '';
                    return;
                }
                
                fileList.style.display = 'block';
                fileCount.textContent = selectedFiles.length;
                
                // ✅ Mettre à jour le champ caché avec les noms des fichiers
                const fileNames = selectedFiles.map(f => f.name).join(',');
                document.getElementById('file_names').value = fileNames;
                
                let html = '';
                selectedFiles.forEach((file, index) => {
                    const size = (file.size / 1024 / 1024).toFixed(2);
                    const icon = getFileIcon(file.name);
                    html += `
                        <div class="list-group-item d-flex justify-content-between align-items-center" id="file-item-${index}">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi ${icon} fs-5"></i>
                                <div>
                                    <span class="fw-semibold">${file.name}</span>
                                    <small class="text-muted ms-2">(${size} MB)</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                    `;
                });
                
                fileListItems.innerHTML = html;
            }

            // ✅ Supprimer un fichier
            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFileList();
            }

            window.removeFile = removeFile;

            // ✅ Supprimer tous les fichiers
            function clearAllFiles() {
                if (selectedFiles.length === 0) return;
                if (confirm('Supprimer tous les fichiers sélectionnés ?')) {
                    selectedFiles = [];
                    updateFileList();
                }
            }

            window.clearAllFiles = clearAllFiles;

            // ✅ Obtenir l'icône selon le type de fichier
            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                const icons = {
                    'pdf': 'bi-file-pdf text-danger',
                    'doc': 'bi-file-word text-primary',
                    'docx': 'bi-file-word text-primary',
                    'jpg': 'bi-file-image text-success',
                    'jpeg': 'bi-file-image text-success',
                    'png': 'bi-file-image text-success',
                    'xls': 'bi-file-excel text-success',
                    'xlsx': 'bi-file-excel text-success',
                    'txt': 'bi-file-text text-secondary'
                };
                return icons[ext] || 'bi-file-earmark';
            }

            // ============================================
            // ✅ SOUMISSION DU FORMULAIRE AVEC FICHIERS
            // ============================================
            document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
                // ✅ Si des fichiers sont sélectionnés, les ajouter au formulaire
                if (selectedFiles.length > 0) {
                    // ✅ Créer un FormData pour l'envoi des fichiers
                    const formData = new FormData(this);
                    
                    // ✅ Ajouter chaque fichier sélectionné
                    selectedFiles.forEach((file, index) => {
                        formData.append('attachments[]', file);
                    });
                    
                    // ✅ Désactiver les boutons pendant l'envoi
                    const buttons = this.querySelectorAll('button[type="submit"]');
                    buttons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Envoi en cours...';
                    });
                    
                    // ✅ Empêcher la soumission standard
                    e.preventDefault();
                    
                    // ✅ Envoyer via fetch avec FormData
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        // ✅ Si la réponse est une redirection, suivre
                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            window.location.href = data.redirect;
                        } else if (data && data.message) {
                            alert('Erreur: ' + data.message);
                            buttons.forEach(btn => {
                                btn.disabled = false;
                                btn.innerHTML = btn.value === '1' 
                                    ? '<i class="bi bi-send"></i> Soumettre' 
                                    : '<i class="bi bi-save"></i> Enregistrer le brouillon';
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de l\'envoi');
                        buttons.forEach(btn => {
                            btn.disabled = false;
                            btn.innerHTML = btn.value === '1' 
                                ? '<i class="bi bi-send"></i> Soumettre' 
                                : '<i class="bi bi-save"></i> Enregistrer le brouillon';
                        });
                    });
                }
                // ✅ Si pas de fichiers, soumission normale
            });

            // ============================================
            // STYLE ADDITIONNEL
            // ============================================
            const style = document.createElement('style');
            style.textContent = `
                .drop-zone:hover {
                    border-color: #4f8a8b !important;
                    background-color: #e8f5e9 !important;
                }
                .list-group-item {
                    transition: all 0.3s;
                }
                .list-group-item:hover {
                    background-color: #f8f9fa;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    @endpush
</x-app-layout>