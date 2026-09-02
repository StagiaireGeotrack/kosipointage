{{-- resources/views/employes/leave_requests/create.blade.php --}}
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
                                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}
                                            data-periods='@json($allPeriods->get($type->id, []))'>
                                        {{ $type->name }} ({{ $type->code }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('leave_type_id')" class="mt-2" />
                            <div id="balance_info" class="mt-1 small text-muted" style="display: none;">
                                <i class="bi bi-wallet2"></i> 
                                Solde disponible : <span id="balance_display">0</span> jour(s)
                            </div>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="period_id" :value="__('Période de référence')" />
                            <span class="text-danger">*</span>
                            <select id="period_id" name="period_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez d\'abord un type de congé') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                            
                            <!-- Informations sur la période sélectionnée -->
                            <div id="period_info" class="mt-1" style="display: none;">
                                <div class="alert alert-info small p-2">
                                    <i class="bi bi-calendar-range"></i>
                                    <span id="period_dates"></span>
                                    <br>
                                    <span id="period_status"></span>
                                </div>
                            </div>
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

                    <!-- Alertes de validation -->
                    <div id="date_alert" class="mt-3" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span id="date_alert_message"></span>
                        </div>
                    </div>

                    <div id="balance_alert" class="mt-3" style="display: none;">
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            <span id="balance_alert_message"></span>
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
                                    <span id="attachment_required" class="badge bg-danger ms-2" style="display: none;">
                                        {{ __('Justificatif obligatoire') }}
                                    </span>
                                </h5>
                                
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle"></i>
                                    {{ __('Ajoutez des justificatifs si nécessaire (certificat médical, acte de mariage, etc.)') }}
                                </p>

                                <!-- Zone de dépôt -->
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

                                <!-- Champ caché pour stocker les noms des fichiers -->
                                <input type="hidden" id="file_names" name="file_names" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;" id="btnDraft">
                            <i class="bi bi-save"></i> {{ __('Enregistrer le brouillon') }}
                        </button>
                        <button type="submit" name="submit" value="1" class="btn" style="background-color: #5b7f95; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;" id="btnSubmit" disabled>
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
            // ÉLÉMENTS
            // ============================================
            const leaveTypeSelect = document.getElementById('leave_type_id');
            const periodSelect = document.getElementById('period_id');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const durationDisplay = document.getElementById('duration_display');
            const durationHidden = document.getElementById('duration_hidden');
            const durationDetails = document.getElementById('duration_details');
            const employeeId = document.getElementById('employee_id').value;
            const dateAlert = document.getElementById('date_alert');
            const dateAlertMessage = document.getElementById('date_alert_message');
            const balanceAlert = document.getElementById('balance_alert');
            const balanceAlertMessage = document.getElementById('balance_alert_message');
            const balanceInfo = document.getElementById('balance_info');
            const balanceDisplay = document.getElementById('balance_display');
            const periodInfo = document.getElementById('period_info');
            const periodDates = document.getElementById('period_dates');
            const periodStatus = document.getElementById('period_status');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnDraft = document.getElementById('btnDraft');
            const attachmentRequired = document.getElementById('attachment_required');
            
            let currentPeriods = [];
            let selectedPeriodData = null;
            let currentBalance = 0;
            let currentDuration = 0;
            let isFormValid = false;
            let selectedFiles = [];

            // ============================================
            // FORMATER UNE DATE
            // ============================================
            function formatDate(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return date.toLocaleDateString('fr-FR');
            }

            // ============================================
            // AFFICHER UNE ALERTE
            // ============================================
            function showAlert(type, message) {
                if (type === 'date') {
                    dateAlert.style.display = 'block';
                    dateAlert.className = 'alert alert-warning';
                    dateAlertMessage.textContent = message;
                    balanceAlert.style.display = 'none';
                } else if (type === 'balance') {
                    balanceAlert.style.display = 'block';
                    balanceAlertMessage.textContent = message;
                    dateAlert.style.display = 'none';
                }
            }

            // ============================================
            // AFFICHER LES INFOS DE LA PÉRIODE - CORRIGÉ
            // ============================================
           // ============================================
// AFFICHER LES INFOS DE LA PÉRIODE - AVEC DATE LIMITE
// ============================================
function updatePeriodInfo() {
    const selectedOption = periodSelect.options[periodSelect.selectedIndex];
    
    console.log('🔄 updatePeriodInfo - Option sélectionnée:', selectedOption);
    
    if (!selectedOption || !selectedOption.value) {
        periodInfo.style.display = 'none';
        selectedPeriodData = null;
        return;
    }
    
    // Récupérer les données depuis l'option sélectionnée
    selectedPeriodData = {
        id: selectedOption.value,
        start_date: selectedOption.dataset.start,
        end_date: selectedOption.dataset.end,
        status: selectedOption.dataset.status,
        is_active: selectedOption.dataset.isActive === '1',
        allow_rollover: selectedOption.dataset.allowRollover === '1',
        submission_deadline: selectedOption.dataset.submissionDeadline || null //  AJOUTÉ
    };
    
    console.log(' Période sélectionnée:', selectedPeriodData);
    
    const startFormatted = formatDate(selectedPeriodData.start_date);
    const endFormatted = formatDate(selectedPeriodData.end_date);
    
    // Afficher la période
    let periodText = ` Période: ${startFormatted} - ${endFormatted}`;
    
    // ✅ AFFICHER LA DATE LIMITE DE POSE
    let deadlineText = '';
    let isDeadlineExpired = false;
    
    if (selectedPeriodData.submission_deadline) {
        const deadline = new Date(selectedPeriodData.submission_deadline);
        const today = new Date();
        isDeadlineExpired = today > deadline;
        
        deadlineText = `<br> Date limite de pose: ${formatDate(selectedPeriodData.submission_deadline)}`;
        if (isDeadlineExpired) {
            deadlineText += ' ❌ (Dépassée)';
        } else {
            const daysLeft = Math.ceil((deadline - today) / (1000 * 60 * 60 * 24));
            deadlineText += ` (${daysLeft} jour${daysLeft > 1 ? 's' : ''} restant${daysLeft > 1 ? 's' : ''})`;
        }
    }
    
    periodDates.innerHTML = periodText + deadlineText;
    
    // Afficher le bon statut avec la bonne couleur
    let statusText = '';
    let statusClass = '';
    
    if (selectedPeriodData.status === 'open' && selectedPeriodData.is_active) {
        // ✅ VÉRIFIER LA DATE LIMITE
        if (isDeadlineExpired) {
            statusText = ' Période expirée - Date limite dépassée';
            statusClass = 'text-danger';
        } else {
            statusText = ' Période ouverte - Vous pouvez faire une demande';
            statusClass = 'text-success';
        }
    } else if (selectedPeriodData.status === 'preparing') {
        statusText = ' Période en préparation - Les demandes ne sont pas encore ouvertes';
        statusClass = 'text-warning';
    } else if (selectedPeriodData.status === 'closed') {
        statusText = ' Période fermée - Les demandes ne sont plus acceptées';
        statusClass = 'text-danger';
    } else {
        statusText = `Statut inconnu: "${selectedPeriodData.status}"`;
        statusClass = 'text-secondary';
    }
    
    periodStatus.textContent = statusText;
    periodStatus.className = statusClass;
    
    periodInfo.style.display = 'block';
}

// ============================================
// FILTRAGE DES PÉRIODES - AVEC DATE LIMITE
// ============================================
function updatePeriods() {
    const selectedOption = leaveTypeSelect.options[leaveTypeSelect.selectedIndex];
    const periodsData = JSON.parse(selectedOption.dataset.periods || '[]');
    currentPeriods = periodsData;
    
    console.log('📋 Périodes chargées:', periodsData);
    
    periodSelect.innerHTML = '';
    
    if (periodsData.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = '{{ __("Aucune période disponible pour ce type") }}';
        periodSelect.appendChild(option);
        periodInfo.style.display = 'none';
        validateForm();
        return;
    }
    
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '{{ __("Sélectionnez une période") }}';
    periodSelect.appendChild(defaultOption);
    
    periodsData.forEach((period, index) => {
        const option = document.createElement('option');
        option.value = period.id;
        
        // Ajouter tous les datasets nécessaires
        option.dataset.start = period.start_date;
        option.dataset.end = period.end_date;
        option.dataset.status = period.status;
        option.dataset.isActive = period.is_active ? '1' : '0';
        option.dataset.allowRollover = period.allow_rollover ? '1' : '0';
        option.dataset.submissionDeadline = period.submission_deadline || ''; // ✅ AJOUTÉ
        
        const startFormatted = formatDate(period.start_date);
        const endFormatted = formatDate(period.end_date);
        let label = `${period.name} (${startFormatted} - ${endFormatted})`;
        
        // ✅ AFFICHER LA DATE LIMITE DANS LE SELECT
        if (period.submission_deadline) {
            const deadline = new Date(period.submission_deadline);
            const today = new Date();
            const isExpired = today > deadline;
            label += ` - Limite: ${formatDate(period.submission_deadline)}`;
            if (isExpired) {
                label += ' ❌';
            }
        }
        
        // Afficher le bon badge selon le statut
        if (period.status === 'open' && period.is_active) {
            label += ' ✅';
        } else if (period.status === 'preparing') {
            label += ' ⏳';
        } else if (period.status === 'closed') {
            label += ' 🔒';
        }
        
        option.textContent = label;
        
        console.log(`📌 Période #${period.id}: ${period.name} - statut: ${period.status}, deadline: ${period.submission_deadline}`);
        
        if (period.is_default && index === 0) {
            option.selected = true;
        }
        
        periodSelect.appendChild(option);
    });
    
    updatePeriodInfo();
    getBalance();
    validateForm();
}
            // ============================================
            // RÉCUPÉRER LE SOLDE
            // ============================================
            function getBalance() {
                const typeId = leaveTypeSelect.value;
                const periodId = periodSelect.value;
                
                if (!typeId || !periodId) {
                    balanceInfo.style.display = 'none';
                    return;
                }
                
                fetch(`/employe/leave-requests/balance?leave_type_id=${typeId}&period_id=${periodId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentBalance = data.balance.available;
                            balanceDisplay.textContent = currentBalance;
                            balanceInfo.style.display = 'block';
                            
                            if (currentBalance <= 0) {
                                balanceInfo.classList.add('text-danger');
                                balanceInfo.classList.remove('text-muted');
                            } else {
                                balanceInfo.classList.remove('text-danger');
                                balanceInfo.classList.add('text-muted');
                            }
                        }
                    })
                    .catch(error => console.error('Erreur solde:', error));
            }

            // ============================================
            // VALIDATION DU FORMULAIRE - CORRIGÉE
            // ============================================
            function validateForm() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const typeId = leaveTypeSelect.value;
                const periodId = periodSelect.value;
                
                dateAlert.style.display = 'none';
                balanceAlert.style.display = 'none';
                
                if (!typeId || !periodId || !startDate || !endDate) {
                    btnSubmit.disabled = true;
                    isFormValid = false;
                    return;
                }
                
                if (new Date(endDate) < new Date(startDate)) {
                    showAlert('date', '⚠️ La date de fin doit être postérieure à la date de début.');
                    btnSubmit.disabled = true;
                    isFormValid = false;
                    return;
                }
                
                // Vérifier les dates par rapport à la période
                if (selectedPeriodData) {
                    const periodStart = new Date(selectedPeriodData.start_date);
                    const periodEnd = new Date(selectedPeriodData.end_date);
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    
                    if (start < periodStart || end > periodEnd) {
                        showAlert('date', `⚠️ Les dates doivent être comprises entre ${formatDate(selectedPeriodData.start_date)} et ${formatDate(selectedPeriodData.end_date)}`);
                        btnSubmit.disabled = true;
                        isFormValid = false;
                        return;
                    }
                    
                    // Vérification du statut - Comparaison stricte avec 'open'
                    console.log('🔍 Vérification statut:', {
                        status: selectedPeriodData.status,
                        is_active: selectedPeriodData.is_active,
                        statusType: typeof selectedPeriodData.status
                    });
                    
                    const isOpen = selectedPeriodData.status === 'open' && selectedPeriodData.is_active;
                    
                    if (!isOpen) {
                        let msg = '';
                        if (selectedPeriodData.status === 'preparing') {
                            msg = '⏳ Cette période est en préparation. Les demandes ne sont pas encore ouvertes.';
                        } else if (selectedPeriodData.status === 'closed') {
                            msg = '🔒 Cette période est fermée. Les demandes ne sont plus acceptées.';
                        } else {
                            msg = `⏳ Cette période n'est pas ouverte pour les demandes (statut: ${selectedPeriodData.status}).`;
                        }
                        showAlert('date', msg);
                        btnSubmit.disabled = true;
                        isFormValid = false;
                        return;
                    }
                }
                
                // Vérifier le solde
                if (currentBalance > 0 && currentDuration > currentBalance) {
                    showAlert('balance', `⚠️ Solde insuffisant : ${currentBalance} jour(s) disponible(s) pour ${currentDuration} jour(s) demandé(s).`);
                    btnSubmit.disabled = true;
                    isFormValid = false;
                    return;
                }
                
                // Vérifier les pièces jointes obligatoires
                const required = attachmentRequired.style.display !== 'none';
                const hasFiles = selectedFiles.length > 0;
                if (required && !hasFiles) {
                    showAlert('date', '📎 Un justificatif est obligatoire pour ce type de congé.');
                    btnSubmit.disabled = true;
                    isFormValid = false;
                    return;
                }
                
                // Tout est valide
                btnSubmit.disabled = false;
                isFormValid = true;
                console.log('✅ Formulaire valide, soumission possible');
            }

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
                
                if (!data.start_date || !data.end_date || !data.leave_type_id || !data.period_id) {
                    durationDisplay.innerHTML = '<span class="text-muted">-- jours</span>';
                    durationHidden.value = '0';
                    currentDuration = 0;
                    validateForm();
                    return;
                }
                
                if (new Date(data.end_date) < new Date(data.start_date)) {
                    durationDisplay.innerHTML = '<span class="text-danger">⚠️ Date de fin antérieure</span>';
                    durationHidden.value = '0';
                    currentDuration = 0;
                    validateForm();
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
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        currentDuration = data.duration;
                        durationDisplay.innerHTML = `<span class="text-success fw-bold">✅ ${data.duration_formatted}</span>`;
                        durationHidden.value = data.duration;
                        
                        if (data.balance_message) {
                            showAlert('balance', data.balance_message);
                        }
                        
                        validateForm();
                    } else {
                        durationDisplay.innerHTML = `<span class="text-danger">⚠️ ${data.message || 'Erreur de calcul'}</span>`;
                        durationHidden.value = '0';
                        currentDuration = 0;
                        validateForm();
                    }
                })
                .catch(error => {
                    console.error('Erreur API:', error);
                    durationDisplay.innerHTML = `<span class="text-danger">❌ ${error.message || 'Erreur de connexion'}</span>`;
                    durationHidden.value = '0';
                    currentDuration = 0;
                    validateForm();
                });
            }

            // ============================================
            // GESTION DES FICHIERS
            // ============================================
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const fileList = document.getElementById('fileList');
            const fileListItems = document.getElementById('fileListItems');
            const fileCount = document.getElementById('fileCount');

            // Drag & Drop
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

            function handleFileSelect(files) {
                handleFiles(files);
            }

            window.handleFileSelect = handleFileSelect;

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
                
                fileInput.value = '';
                validateForm();
            }

            function updateFileList() {
                if (selectedFiles.length === 0) {
                    fileList.style.display = 'none';
                    fileCount.textContent = '0';
                    document.getElementById('file_names').value = '';
                    validateForm();
                    return;
                }
                
                fileList.style.display = 'block';
                fileCount.textContent = selectedFiles.length;
                
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
                validateForm();
            }

            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFileList();
                validateForm();
            }

            window.removeFile = removeFile;

            function clearAllFiles() {
                if (selectedFiles.length === 0) return;
                if (confirm('Supprimer tous les fichiers sélectionnés ?')) {
                    selectedFiles = [];
                    updateFileList();
                    validateForm();
                }
            }

            window.clearAllFiles = clearAllFiles;

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
            // SOUMISSION DU FORMULAIRE
            // ============================================
            document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
                if (!isFormValid) {
                    e.preventDefault();
                    alert('Veuillez corriger les erreurs avant de soumettre.');
                    return;
                }
                
                if (selectedFiles.length > 0) {
                    const formData = new FormData(this);
                    selectedFiles.forEach((file, index) => {
                        formData.append('attachments[]', file);
                    });
                    
                    const buttons = this.querySelectorAll('button[type="submit"]');
                    buttons.forEach(btn => {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Envoi en cours...';
                    });
                    
                    e.preventDefault();
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
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
            });

            // ============================================
            // ÉCOUTEURS D'ÉVÉNEMENTS
            // ============================================
            leaveTypeSelect.addEventListener('change', function() {
                updatePeriods();
                getBalance();
            });
            
            periodSelect.addEventListener('change', function() {
                updatePeriodInfo();
                getBalance();
                calculateDuration();
            });
            
            startDateInput.addEventListener('change', calculateDuration);
            endDateInput.addEventListener('change', calculateDuration);
            startDateInput.addEventListener('input', calculateDuration);
            endDateInput.addEventListener('input', calculateDuration);

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
                .alert {
                    border-radius: 8px;
                }
                .text-success {
                    color: #22c55e !important;
                }
                .text-warning {
                    color: #f59e0b !important;
                }
                .text-danger {
                    color: #ef4444 !important;
                }
                .text-secondary {
                    color: #6b7280 !important;
                }
            `;
            document.head.appendChild(style);

            // ============================================
            // INITIALISATION
            // ============================================
            if (leaveTypeSelect.value) {
                updatePeriods();
            }
            
            if (startDateInput.value && endDateInput.value) {
                calculateDuration();
            }
        });
    </script>
    @endpush
</x-app-layout>