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
                    {{ __('Remplissez le formulaire ci-dessous pour faire une demande de congé. La durée sera calculée automatiquement.') }}
                </div>

                <form method="POST" action="{{ route('employe.leave-requests.store') }}" id="leaveRequestForm" enctype="multipart/form-data">
                    @csrf

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
                                   value="{{ old('start_date') }}" required onchange="calculateDuration()">
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="end_date" :value="__('Date de fin')" />
                            <span class="text-danger">*</span>
                            <input type="date" id="end_date" name="end_date" class="form-control mt-1" 
                                   value="{{ old('end_date') }}" required onchange="calculateDuration()">
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Durée et Motif -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="duration" :value="__('Durée calculée (jours)')" />
                            <input type="text" id="duration" name="duration" class="form-control mt-1" 
                                   value="{{ old('duration', 0) }}" readonly style="background-color: #f3f4f6;">
                            <small class="text-muted">{{ __('La durée est calculée automatiquement en jours ouvrés') }}</small>
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

                    <!-- ============================================ -->
                    <!-- SECTION PIÈCES JOINTES (NOUVEAU)              -->
                    <!-- ============================================ -->
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

                                <!-- Zone de dépôt des fichiers -->
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
                                           style="display: none;" onchange="handleFiles(this.files)">
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-check-circle"></i> 
                                            {{ __('Formats acceptés: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX, TXT') }}
                                            <br>
                                            <i class="bi bi-hdd"></i> {{ __('Taille maximale: 5MB par fichier') }}
                                        </small>
                                    </div>
                                </div>

                                <!-- Liste des fichiers sélectionnés -->
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
        // ============================================
        // CALCUL DE LA DURÉE
        // ============================================
        function calculateDuration() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const durationField = document.getElementById('duration');

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                if (end < start) {
                    durationField.value = '0';
                    return;
                }

                let days = 0;
                const current = new Date(start);
                while (current <= end) {
                    const day = current.getDay();
                    if (day !== 0 && day !== 6) {
                        days++;
                    }
                    current.setDate(current.getDate() + 1);
                }
                durationField.value = days;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            calculateDuration();
        });

        // ============================================
        // GESTION DES FICHIERS (DRAG & DROP)
        // ============================================
        let selectedFiles = [];

        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');
        const fileListItems = document.getElementById('fileListItems');
        const fileCount = document.getElementById('fileCount');

        // Drag & Drop events
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

        // Gestion des fichiers
        function handleFiles(files) {
            const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx', 'txt'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            let newFiles = [];
            let errors = [];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const extension = file.name.split('.').pop().toLowerCase();
                
                // Vérifier l'extension
                if (!allowedExtensions.includes(extension)) {
                    errors.push(`"${file.name}" - Format non autorisé`);
                    continue;
                }
                
                // Vérifier la taille
                if (file.size > maxSize) {
                    errors.push(`"${file.name}" - Taille > 5MB`);
                    continue;
                }
                
                // Vérifier les doublons
                const duplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (duplicate) {
                    errors.push(`"${file.name}" - Fichier déjà ajouté`);
                    continue;
                }
                
                newFiles.push(file);
            }

            // Ajouter les fichiers valides
            if (newFiles.length > 0) {
                selectedFiles = [...selectedFiles, ...newFiles];
                updateFileList();
            }
            
            // Afficher les erreurs
            if (errors.length > 0) {
                alert('Erreurs :\n' + errors.join('\n'));
            }
            
            // Réinitialiser l'input
            fileInput.value = '';
        }

        // Mettre à jour la liste des fichiers
        function updateFileList() {
            if (selectedFiles.length === 0) {
                fileList.style.display = 'none';
                fileCount.textContent = '0';
                return;
            }
            
            fileList.style.display = 'block';
            fileCount.textContent = selectedFiles.length;
            
            let html = '';
            selectedFiles.forEach((file, index) => {
                const size = (file.size / 1024 / 1024).toFixed(2);
                const icon = getFileIcon(file.name);
                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
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

        // Supprimer un fichier
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileList();
        }

        // Supprimer tous les fichiers
        function clearAllFiles() {
            if (selectedFiles.length === 0) return;
            if (confirm('Supprimer tous les fichiers sélectionnés ?')) {
                selectedFiles = [];
                updateFileList();
            }
        }

        // Obtenir l'icône selon le type de fichier
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
        // SOUMISSION DU FORMULAIRE AVEC FICHIERS
        // ============================================
        document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
            // Si des fichiers sont sélectionnés, les ajouter au formulaire
            if (selectedFiles.length > 0) {
                // Créer un FormData pour envoyer les fichiers
                const formData = new FormData(this);
                
                // Ajouter chaque fichier
                selectedFiles.forEach((file, index) => {
                    formData.append('attachments[' + index + ']', file);
                });
                
                // Remplacer la soumission standard
                e.preventDefault();
                
                // Désactiver les boutons
                const buttons = this.querySelectorAll('button[type="submit"]');
                buttons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Envoi en cours...';
                });
                
                // Envoyer avec fetch
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Rediriger vers la page de la demande
                        window.location.href = data.redirect;
                    } else {
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
                    alert('Erreur lors de l\'envoi');
                    buttons.forEach(btn => {
                        btn.disabled = false;
                        btn.innerHTML = btn.value === '1' 
                            ? '<i class="bi bi-send"></i> Soumettre' 
                            : '<i class="bi bi-save"></i> Enregistrer le brouillon';
                    });
                });
            }
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
    </script>
    @endpush
</x-app-layout>