{{-- resources/views/employe/leave_requests/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-pencil"></i> {{ __('Modifier la demande de congé') }}
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
                    {{ __('Modifiez les informations de votre demande de congé. La durée sera recalculée automatiquement selon les règles configurées.') }}
                </div>

                <form method="POST" action="{{ route('employe.leave-requests.update', $request->id) }}" id="leaveRequestForm">
                    @csrf
                    @method('PUT')

                    <!-- ID Employé caché -->
                    <input type="hidden" id="employee_id" value="{{ $employee->ID ?? auth()->user()->employee->ID ?? '' }}">
                    <!-- ID de la demande -->
                    <input type="hidden" id="request_id" value="{{ $request->id }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="leave_type_id" :value="__('Type de congé')" />
                            <span class="text-danger">*</span>
                            <select id="leave_type_id" name="leave_type_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('leave_type_id', $request->leave_type_id) == $type->id ? 'selected' : '' }}>
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
                                    <option value="{{ $period->id }}" {{ old('period_id', $request->period_id) == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="start_date" :value="__('Date de début')" />
                            <span class="text-danger">*</span>
                            <input type="date" id="start_date" name="start_date" class="form-control mt-1" 
                                   value="{{ old('start_date', $request->start_date->format('Y-m-d')) }}" required>
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="end_date" :value="__('Date de fin')" />
                            <span class="text-danger">*</span>
                            <input type="date" id="end_date" name="end_date" class="form-control mt-1" 
                                   value="{{ old('end_date', $request->end_date->format('Y-m-d')) }}" required>
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Durée calculée automatiquement -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="duration_display" :value="__('Durée calculée')" />
                            <div id="duration_display" class="form-control mt-1" style="background-color: #f3f4f6; font-weight: bold; padding: 8px 12px; min-height: 38px;">
                                <span class="text-muted">{{ number_format($request->duration, 1) }} jours</span>
                            </div>
                            <input type="hidden" id="duration_hidden" name="duration" value="{{ $request->duration }}">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> 
                                {{ __('La durée est calculée automatiquement selon : type de congé, politique, jours fériés et week-ends') }}
                            </small>
                            <div id="duration_details" class="mt-1 small text-muted" style="display: none;"></div>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="reason" :value="__('Motif (optionnel)')" />
                            <input type="text" id="reason" name="reason" class="form-control mt-1" 
                                   value="{{ old('reason', $request->reason) }}" placeholder="{{ __('Ex: Vacances, Rendez-vous...') }}">
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <x-input-label for="comment" :value="__('Commentaire (optionnel)')" />
                            <textarea id="comment" name="comment" class="form-control mt-1" rows="3" 
                                      placeholder="{{ __('Informations supplémentaires...') }}">{{ old('comment', $request->comment) }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>
                    </div>

                    {{-- ✅ Affichage du statut des pièces justificatives --}}
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <div id="attachment_status_container" class="alert alert-warning" style="display: none;">
                                <i class="bi bi-paperclip"></i>
                                <span id="attachment_status_message"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn" style="background-color: #f59e0b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;">
                            <i class="bi bi-save"></i> {{ __('Mettre à jour') }}
                        </button>
                        <button type="submit" name="submit" value="1" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;">
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
        // CALCUL DE LA DURÉE POUR UN BROUILLON (GET)
        // ============================================
        const leaveTypeSelect = document.getElementById('leave_type_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const periodSelect = document.getElementById('period_id');
        const durationDisplay = document.getElementById('duration_display');
        const durationHidden = document.getElementById('duration_hidden');
        const durationDetails = document.getElementById('duration_details');
        const requestId = document.getElementById('request_id').value;

        // ✅ Éléments pour les pièces justificatives
        const attachmentStatusContainer = document.getElementById('attachment_status_container');
        const attachmentStatusMessage = document.getElementById('attachment_status_message');

        function calculateDurationForDraft() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            // ✅ Vérifier que les dates sont valides
            if (!startDate || !endDate) {
                durationDisplay.innerHTML = '<span class="text-muted">-- jours</span>';
                durationHidden.value = '0';
                durationDetails.style.display = 'none';
                return;
            }

            // ✅ Vérifier que la date de fin n'est pas avant la date de début
            if (new Date(endDate) < new Date(startDate)) {
                durationDisplay.innerHTML = '<span class="text-danger">⚠️ Date de fin antérieure</span>';
                durationHidden.value = '0';
                durationDetails.style.display = 'none';
                return;
            }

            durationDisplay.innerHTML = '<span class="text-warning">⏳ Calcul en cours...</span>';

            // ✅ Utiliser GET avec la route spécifique pour les brouillons
            const url = `/employe/leave-requests/${requestId}/calculate-duration`;
            const params = new URLSearchParams({
                start_date: startDate,
                end_date: endDate
            });

            fetch(`${url}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // ✅ Afficher la durée
                    durationDisplay.innerHTML = `<span class="text-success fw-bold">${data.duration_formatted}</span>`;
                    durationHidden.value = data.duration;
                    
                    // ✅ Afficher les détails si disponibles
                    if (data.details) {
                        durationDetails.innerHTML = data.details;
                        durationDetails.style.display = 'block';
                    } else {
                        durationDetails.style.display = 'none';
                    }

                    // ✅ Gérer le statut des pièces justificatives
                    if (data.attachments) {
                        if (data.attachments.required) {
                            attachmentStatusContainer.style.display = 'block';
                            
                            if (data.attachments.has_attachments) {
                                attachmentStatusContainer.className = 'alert alert-success';
                                attachmentStatusMessage.innerHTML = `
                                    <i class="bi bi-check-circle"></i> 
                                    ${data.attachments.message} (${data.attachments.count} pièce(s) jointe(s))
                                `;
                            } else {
                                attachmentStatusContainer.className = 'alert alert-danger';
                                attachmentStatusMessage.innerHTML = `
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    ${data.attachments.message}
                                    <br><small class="text-muted">Veuillez ajouter une pièce justificative avant de soumettre.</small>
                                `;
                            }
                        } else {
                            attachmentStatusContainer.style.display = 'none';
                        }
                    }
                } else {
                    durationDisplay.innerHTML = `<span class="text-danger">⚠️ ${data.message || 'Erreur de calcul'}</span>`;
                    durationHidden.value = '0';
                    durationDetails.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                durationDisplay.innerHTML = `<span class="text-danger">❌ Erreur de calcul: ${error.message}</span>`;
                durationHidden.value = '0';
                durationDetails.style.display = 'none';
            });
        }

        // ✅ Écouter les changements sur les dates
        startDateInput.addEventListener('change', calculateDurationForDraft);
        startDateInput.addEventListener('input', calculateDurationForDraft);
        endDateInput.addEventListener('change', calculateDurationForDraft);
        endDateInput.addEventListener('input', calculateDurationForDraft);

        // ✅ Recalculer quand le type de congé ou la période change
        leaveTypeSelect.addEventListener('change', calculateDurationForDraft);
        periodSelect.addEventListener('change', calculateDurationForDraft);

        // ✅ Calculer au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre un peu que tout soit chargé
            setTimeout(calculateDurationForDraft, 100);
        });

        // ✅ Afficher le statut des pièces au chargement
        function checkAttachmentsStatus() {
            fetch(`/employe/leave-requests/${requestId}/attachments/status`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const status = data.data;
                    if (status.attachments_required) {
                        attachmentStatusContainer.style.display = 'block';
                        if (status.has_attachments) {
                            attachmentStatusContainer.className = 'alert alert-success';
                            attachmentStatusMessage.innerHTML = `
                                <i class="bi bi-check-circle"></i> 
                                ${status.message} (${status.count} pièce(s) jointe(s))
                            `;
                        } else {
                            attachmentStatusContainer.className = 'alert alert-danger';
                            attachmentStatusMessage.innerHTML = `
                                <i class="bi bi-exclamation-triangle"></i> 
                                ${status.message}
                                <br><small class="text-muted">Veuillez ajouter une pièce justificative avant de soumettre.</small>
                            `;
                        }
                    } else {
                        attachmentStatusContainer.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Erreur statut pièces:', error);
            });
        }

        // ✅ Vérifier le statut des pièces au chargement
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkAttachmentsStatus, 200);
        });

        // ✅ Validation avant soumission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = e.submitter;
            
            // Si c'est le bouton "Soumettre"
            if (submitButton && submitButton.name === 'submit' && submitButton.value === '1') {
                // Vérifier si des pièces sont requises
                const statusContainer = document.getElementById('attachment_status_container');
                if (statusContainer.style.display !== 'none') {
                    const isSuccess = statusContainer.className.includes('alert-success');
                    if (!isSuccess) {
                        e.preventDefault();
                        alert('❌ Veuillez ajouter une pièce justificative avant de soumettre la demande.');
                        return false;
                    }
                }
            }
        });

        // ✅ Fonction pour mettre à jour le statut des pièces après ajout/suppression
        window.updateAttachmentsStatus = function() {
            setTimeout(checkAttachmentsStatus, 500);
            setTimeout(calculateDurationForDraft, 500);
        };
    </script>
    @endpush
</x-app-layout>