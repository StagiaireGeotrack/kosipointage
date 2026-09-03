{{-- resources/views/employe/leave_requests/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-file-text"></i> {{ __('Détails de la demande') }}
            </h2>
            <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Détails de la demande -->
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">{{ __('Type de congé') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge" style="background-color: {{ $request->leaveType->color ?? '#4f8a8b' }}; color: #fff;">
                                    {{ $request->leaveType->name ?? 'N/A' }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Période') }}</dt>
                            <dd class="col-sm-8">{{ $request->period->name ?? 'N/A' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Date de début') }}</dt>
                            <dd class="col-sm-8">{{ $request->start_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Date de fin') }}</dt>
                            <dd class="col-sm-8">{{ $request->end_date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Durée') }}</dt>
                            <dd class="col-sm-8">{{ number_format($request->duration, 2) }} {{ $request->duration > 1 ? 'jours' : 'jour' }}</dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">{{ __('Statut') }}</dt>
                            <dd class="col-sm-8">
                                @php
                                    $statusBadge = match($request->status) {
                                        'draft' => ['bg' => 'secondary', 'text' => 'Brouillon'],
                                        'pending' => ['bg' => 'warning', 'text' => 'En attente'],
                                        'approved' => ['bg' => 'success', 'text' => 'Approuvé'],
                                        'rejected' => ['bg' => 'danger', 'text' => 'Refusé'],
                                        'cancelled' => ['bg' => 'secondary', 'text' => 'Annulé'],
                                        default => ['bg' => 'secondary', 'text' => $request->status],
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusBadge['bg'] }}">
                                    {{ $statusBadge['text'] }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Motif') }}</dt>
                            <dd class="col-sm-8">{{ $request->reason ?? '—' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Commentaire') }}</dt>
                            <dd class="col-sm-8">{{ $request->comment ?? '—' }}</dd>

                            @if($request->status == 'rejected')
                                <dt class="col-sm-4 fw-bold">{{ __('Motif du refus') }}</dt>
                                <dd class="col-sm-8">{{ $request->rejection_reason ?? '—' }}</dd>
                            @endif

                            <dt class="col-sm-4 fw-bold">{{ __('Date de création') }}</dt>
                            <dd class="col-sm-8">{{ $request->created_at->format('d/m/Y H:i') }}</dd>

                            @if($request->approved_at)
                                <dt class="col-sm-4 fw-bold">{{ __('Date d\'approbation') }}</dt>
                                <dd class="col-sm-8">{{ $request->approved_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Workflow (affichage pour l'employé) -->
                <div class="mt-3 pt-3 border-top">
                    <h6>{{ __('Workflow de validation') }}</h6>
                    @if($request->approvals->isNotEmpty())
                        <ul class="list-group">
                            @foreach($request->approvals as $step)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Étape {{ $step->step_order }}</strong> : 
                                        {{ $step->step ? $step->step->name : ($step->step_order == 1 ? 'Manager' : 'RH/Direction') }}
                                    </div>
                                    <div>
                                        @if($step->status == 'pending' && $step->is_current)
                                            <span class="badge bg-warning">En cours</span>
                                        @elseif($step->status == 'approved')
                                            <span class="badge bg-success">Approuvé</span>
                                        @elseif($step->status == 'rejected')
                                            <span class="badge bg-danger">Refusé</span>
                                        @else
                                            <span class="badge bg-secondary">En attente</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">{{ __('Aucune étape de validation définie.') }}</p>
                    @endif
                </div>

                <!-- Pièces jointes -->
                <div class="mt-4 pt-3 border-top">
                    <h5 class="mb-3">
                        <i class="bi bi-paperclip"></i> {{ __('Pièces jointes') }}
                        <span class="badge bg-secondary rounded-pill ms-2">{{ $request->attachments->count() }}</span>
                    </h5>
                    
                    @if($request->attachments->isNotEmpty())
                        <div class="list-group mb-3">
                            @foreach($request->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $icon = 'bi-file';
                                            if (str_contains($attachment->mime_type, 'pdf')) {
                                                $icon = 'bi-file-pdf text-danger';
                                            } elseif (str_contains($attachment->mime_type, 'image')) {
                                                $icon = 'bi-file-image text-success';
                                            } elseif (str_contains($attachment->mime_type, 'word') || str_contains($attachment->mime_type, 'document')) {
                                                $icon = 'bi-file-word text-primary';
                                            } elseif (str_contains($attachment->mime_type, 'excel') || str_contains($attachment->mime_type, 'spreadsheet')) {
                                                $icon = 'bi-file-excel text-success';
                                            } else {
                                                $icon = 'bi-file-earmark';
                                            }
                                        @endphp
                                        <i class="bi {{ $icon }} fs-5"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $attachment->file_name }}</div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $attachment->created_at->format('d/m/Y H:i') }}
                                                @if($attachment->file_size)
                                                    <i class="bi bi-hdd ms-2"></i> {{ $attachment->formatted_size }}
                                                @endif
                                                @if($attachment->uploaded_by)
                                                    <i class="bi bi-person ms-2"></i> {{ $attachment->uploaded_by }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('employe.leave-requests.download-attachment', $attachment->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Télécharger {{ $attachment->file_name }}"
                                           target="_blank">
                                            <i class="bi bi-download"></i> Télécharger
                                        </a>
                                        @if($request->status == 'draft' || $request->status == 'pending')
                                            <button onclick="deleteAttachment({{ $attachment->id }})" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Supprimer {{ $attachment->file_name }}">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-paperclip fs-3 d-block mb-2"></i>
                            <p>{{ __('Aucune pièce jointe') }}</p>
                        </div>
                    @endif
                    
                    <!-- Formulaire d'upload -->
                    @if($request->status == 'draft' || $request->status == 'pending')
                        <div class="mt-3 p-3" style="background-color: #f8f9fa; border-radius: 6px; border: 2px dashed #dee2e6;">
                            <form id="attachmentForm" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-8">
                                        <label for="attachment" class="form-label fw-semibold">
                                            <i class="bi bi-upload"></i> {{ __('Ajouter une pièce jointe') }}
                                        </label>
                                        <input type="file" 
                                               class="form-control" 
                                               id="attachment" 
                                               name="attachment" 
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.txt">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            {{ __('Formats acceptés: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX, TXT (Max 5MB)') }}
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" 
                                                class="btn w-100" 
                                                style="background-color: #4f8a8b; color: white; border: none; border-radius: 6px; padding: 8px 20px;" 
                                                onclick="uploadAttachment()">
                                            <i class="bi bi-cloud-upload"></i> {{ __('Ajouter') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="uploadProgress" class="mt-2" style="display: none;">
                                    <div class="progress">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" style="width: 0%; background-color: #4f8a8b;">
                                            0%
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1" id="uploadStatus">Téléchargement en cours...</small>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Boutons d'action -->
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex gap-2 flex-wrap">
                        @if($request->status == 'draft')
                            <a href="{{ route('employe.leave-requests.edit', $request->id) }}" 
                               class="btn" style="background-color: #f59e0b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;" 
                               title="Modifier la demande">
                                <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                            </a>
                        @endif

                        @if($request->status == 'draft')
                            <form action="{{ route('employe.leave-requests.submit', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn" style="background-color: #4f8a8b; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;" title="Soumettre la demande">
                                    <i class="bi bi-send"></i> {{ __('Soumettre la demande') }}
                                </button>
                            </form>
                        @endif

                        @if($request->status == 'draft')
                            <form action="{{ route('employe.leave-requests.destroy', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cette demande ?')" title="Supprimer la demande">
                                    <i class="bi bi-trash"></i> {{ __('Supprimer') }}
                                </button>
                            </form>
                        @endif

                        @if($request->status == 'approved')
                            <form action="{{ route('employe.leave-requests.cancel-approved', $request->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn" style="background-color: #d97706; color: #fff; border: none; border-radius: 6px; padding: 8px 20px;" onclick="return confirm('Annuler ce congé validé ?')" title="Annuler le congé">
                                    <i class="bi bi-x-circle"></i> {{ __('Annuler le congé') }}
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('employe.leave-requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts upload (inchangés) -->
    @push('scripts')
    <script>
        function uploadAttachment() {
            const fileInput = document.getElementById('attachment');
            const file = fileInput.files[0];
            if (!file) {
                alert('Veuillez sélectionner un fichier');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale: 5MB');
                fileInput.value = '';
                return;
            }
            const allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx', 'txt'];
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                alert('Format de fichier non autorisé. Formats acceptés: ' + allowedExtensions.join(', '));
                fileInput.value = '';
                return;
            }
            const formData = new FormData();
            formData.append('attachment', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            const progressDiv = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const statusText = document.getElementById('uploadStatus');
            progressDiv.style.display = 'block';
            progressBar.style.width = '0%';
            progressBar.textContent = '0%';
            statusText.textContent = 'Téléchargement en cours...';
            const uploadBtn = document.querySelector('button[onclick="uploadAttachment()"]');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Envoi...';
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("employe.leave-requests.upload-attachment", $request->id) }}');
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressBar.textContent = percentComplete + '%';
                    if (percentComplete < 100) {
                        statusText.textContent = 'Téléchargement... ' + percentComplete + '%';
                    }
                }
            });
            xhr.onload = function() {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-cloud-upload"></i> Ajouter';
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            progressBar.style.width = '100%';
                            progressBar.textContent = '100%';
                            statusText.textContent = '✅ ' + response.message;
                            statusText.style.color = '#22c55e';
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            statusText.textContent = '❌ Erreur: ' + (response.message || 'Erreur inconnue');
                            statusText.style.color = '#ef4444';
                            setTimeout(function() {
                                progressDiv.style.display = 'none';
                                progressBar.style.width = '0%';
                                statusText.style.color = '';
                            }, 3000);
                        }
                    } catch (e) {
                        statusText.textContent = '❌ Erreur lors du traitement de la réponse';
                        statusText.style.color = '#ef4444';
                    }
                } else {
                    statusText.textContent = '❌ Erreur serveur (Code: ' + xhr.status + ')';
                    statusText.style.color = '#ef4444';
                    setTimeout(function() {
                        progressDiv.style.display = 'none';
                        progressBar.style.width = '0%';
                        statusText.style.color = '';
                    }, 3000);
                }
                fileInput.value = '';
            };
            xhr.onerror = function() {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-cloud-upload"></i> Ajouter';
                statusText.textContent = '❌ Erreur de connexion. Vérifiez votre réseau.';
                statusText.style.color = '#ef4444';
                setTimeout(function() {
                    progressDiv.style.display = 'none';
                    progressBar.style.width = '0%';
                    statusText.style.color = '';
                }, 3000);
                fileInput.value = '';
            };
            xhr.send(formData);
        }

        function deleteAttachment(id) {
            if (!confirm('Supprimer cette pièce jointe définitivement ?')) {
                return;
            }
            const deleteBtn = document.querySelector(`button[onclick="deleteAttachment(${id})"]`);
            if (deleteBtn) {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            }
            const url = '{{ route("employe.leave-requests.delete-attachment", ":id") }}'.replace(':id', id);
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + (data.message || 'Erreur lors de la suppression'));
                    if (deleteBtn) {
                        deleteBtn.disabled = false;
                        deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
                    }
                }
            })
            .catch(error => {
                alert('Erreur réseau lors de la suppression');
                if (deleteBtn) {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('attachment');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Le fichier est trop volumineux (max 5MB)');
                            this.value = '';
                            return;
                        }
                        const fileName = file.name;
                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                        console.log(`Fichier sélectionné: ${fileName} (${fileSize} MB)`);
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('attachmentForm');
            if (form) {
                const dropZone = form.querySelector('.p-3');
                if (dropZone) {
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                        });
                    });
                    dropZone.addEventListener('dragover', function(e) {
                        this.style.borderColor = '#4f8a8b';
                        this.style.backgroundColor = '#e8f5e9';
                    });
                    dropZone.addEventListener('dragleave', function(e) {
                        this.style.borderColor = '#dee2e6';
                        this.style.backgroundColor = '#f8f9fa';
                    });
                    dropZone.addEventListener('drop', function(e) {
                        this.style.borderColor = '#dee2e6';
                        this.style.backgroundColor = '#f8f9fa';
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            const fileInput = document.getElementById('attachment');
                            fileInput.files = files;
                            uploadAttachment();
                        }
                    });
                }
            }
        });
    </script>
    @endpush
</x-app-layout>