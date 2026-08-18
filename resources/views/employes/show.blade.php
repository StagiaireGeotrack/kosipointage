{{-- resources/views/employes/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                Détails de l'employé : {{ $employe->Nom }}
            </h2>
            <div>
                <a href="{{ route('employes.edit', $employe->ID) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('employes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    
                    <!-- ========================================================= -->
                    <!-- IDENTITÉ -->
                    <!-- ========================================================= -->
                    <div class="col-12">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-person-badge"></i> Identité
                        </h5>
                    </div>

                    <!-- N° Mat -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">N° Matricule</label>
                        <p class="fs-5">{{ $employe->num_mat ?? 'Non défini' }}</p>
                    </div>

                    <!-- Nom -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Nom</label>
                        <p class="fs-5">{{ $employe->Nom }}</p>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Email</label>
                        <p class="fs-5">{{ $employe->email ?? 'Non défini' }}</p>
                    </div>

                    <!-- Téléphone -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Téléphone</label>
                        <p class="fs-5">{{ $employe->telephone ?? 'Non défini' }}</p>
                    </div>

                    <!-- ========================================================= -->
                    <!-- ORGANISATION -->
                    <!-- ========================================================= -->
                    <div class="col-12 mt-3">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-diagram-3"></i> Organisation
                        </h5>
                    </div>

                    <!-- Siège -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Siège</label>
                        <p class="fs-5">{{ $employe->siege->Nom ?? 'Non défini' }}</p>
                    </div>

                    <!-- Service -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Service</label>
                        <p class="fs-5">{{ $employe->department->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Poste -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Poste</label>
                        <p class="fs-5">{{ $employe->jobTitle->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Niveau Hiérarchique -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Niveau Hiérarchique</label>
                        <p class="fs-5">{{ $employe->hierarchyLevel->name ?? 'Non défini' }}</p>
                    </div>

                    <!-- Manager Direct -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Manager Direct</label>
                        <p class="fs-5">{{ $employe->manager->Nom ?? 'Aucun manager' }}</p>
                    </div>

                    <!-- Statut employé -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Statut</label>
                        <p class="fs-5">
                            @php
                                $statusLabels = [
                                    'actif' => 'Actif',
                                    'suspendu' => 'Suspendu',
                                    'sorti' => 'Sorti',
                                ];
                            @endphp
                            <span class="badge {{ $employe->employment_status == 'actif' ? 'bg-success' : ($employe->employment_status == 'suspendu' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $statusLabels[$employe->employment_status] ?? $employe->employment_status ?? 'Actif' }}
                            </span>
                        </p>
                    </div>

                    <!-- Date d'embauche -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Date d'embauche</label>
                        <p class="fs-5">{{ $employe->hire_date ? \Carbon\Carbon::parse($employe->hire_date)->format('d/m/Y') : 'Non définie' }}</p>
                    </div>

                    <!-- ========================================================= -->
                    <!-- MÉTHODES D'AUTHENTIFICATION -->
                    <!-- ========================================================= -->
                    <div class="col-12 mt-3">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-shield-lock"></i> Méthodes d'authentification
                        </h5>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($employe->BadgeID)
                                <span class="badge bg-info p-2">
                                    <i class="bi bi-credit-card"></i> Badge
                                </span>
                            @endif
                            @if ($employe->HasFaceSetup)
                                <span class="badge bg-primary p-2">
                                    <i class="bi bi-camera"></i> Face image
                                </span>
                            @endif
                            @if ($employe->Pin)
                                <span class="badge bg-secondary p-2">
                                    <i class="bi bi-key"></i> Code Pin
                                </span>
                            @endif
                            @if ($employe->email)
                                <span class="badge bg-dark p-2">
                                    <i class="bi bi-globe"></i> Accès Web
                                </span>
                            @endif
                            @if (!$employe->BadgeID && !$employe->HasFaceSetup && !$employe->Pin && !$employe->email)
                                <span class="text-muted">Aucune méthode configurée</span>
                            @endif
                        </div>
                    </div>

                    <!-- Badge ID (détaillé) -->
                    @if ($employe->BadgeID)
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Badge ID</label>
                            <p class="fs-5"><code>{{ $employe->BadgeID }}</code></p>
                        </div>
                    @endif

                    <!-- Pin (détaillé) -->
                    @if ($employe->Pin)
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Code PIN</label>
                            <p class="fs-5">••••••</p>
                        </div>
                    @endif

                    <!-- ========================================================= -->
                    <!-- STATUT -->
                    <!-- ========================================================= -->
                    <div class="col-12 mt-3">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-info-circle"></i> Statut
                        </h5>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Statut de l'employé</label>
                        <p class="fs-5">
                            @if($employe->Actived)
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle"></i> Actif
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-x-circle"></i> Inactif
                                </span>
                            @endif
                            @if($employe->deleted)
                                <span class="badge bg-danger fs-6 ms-2">
                                    <i class="bi bi-trash"></i> Supprimé
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold text-muted">Méthode de pointage</label>
                        <p class="fs-5">
                            @php
                                $methodes = [];
                                if ($employe->BadgeID) $methodes[] = 'Badge';
                                if ($employe->HasFaceSetup) $methodes[] = 'Reconnaissance faciale';
                                if ($employe->Pin) $methodes[] = 'Code PIN';
                                echo !empty($methodes) ? implode(' • ', $methodes) : 'Aucune méthode configurée';
                            @endphp
                        </p>
                    </div>

                    <!-- ========================================================= -->
                    <!-- DATES -->
                    <!-- ========================================================= -->
                    <div class="col-12 mt-3">
                        <h5 class="text-primary border-bottom pb-2 mb-3">
                            <i class="bi bi-calendar-event"></i> Dates
                        </h5>
                    </div>

                    @if($employe->CreatedAt)
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Date de création</label>
                            <p class="text-muted"><x-local-date-time :datetime="$employe->CreatedAt"/></p>
                        </div>
                    @endif

                    <!-- Dernière mise à jour (si disponible) -->
                    @if(isset($employe->updated_at))
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold text-muted">Dernière mise à jour</label>
                            <p class="text-muted"><x-local-date-time :datetime="$employe->updated_at"/></p>
                        </div>
                    @endif

                    <!-- ========================================================= -->
                    <!-- ACTIONS -->
                    <!-- ========================================================= -->
                    <div class="col-12 mt-3">
                        <div class="border-top pt-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route('employes.edit', $employe->ID) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                @if(auth()->user()->isTrueSuperAdmin())
                                    @if($employe->deleted)
                                        <button type="button" class="btn btn-success" 
                                                onclick="if(confirm('Voulez-vous vraiment restaurer cet employé ?')) { 
                                                    document.getElementById('reset-form').submit(); 
                                                }">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restaurer
                                        </button>
                                        <form id="reset-form" action="{{ route('employes.reset', $employe->ID) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-danger" 
                                                onclick="if(confirm('Voulez-vous vraiment supprimer cet employé ?')) { 
                                                    document.getElementById('delete-form').submit(); 
                                                }">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                        <form id="delete-form" action="{{ route('employes.destroy', $employe->ID) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                @endif

                                @if(auth()->user()->isSimpleAdmin() && !empty($employe->Pin))
                                    <button type="button" class="btn btn-info" 
                                            onclick="setResetPinAction('{{ route('employes.reset-pin', $employe->ID) }}', '{{ $employe->Nom }}')"
                                            data-bs-toggle="modal"
                                            data-bs-target="#resetPinModal">
                                        <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser PIN
                                    </button>
                                @endif

                                @if(auth()->user()->isTrueSuperAdmin() || auth()->user()->isSimpleAdmin())
                                    <button type="button" class="btn btn-primary" 
                                            onclick="setAssignWebAccessAction('{{ route('employes.assign-web-access', $employe->ID) }}', '{{ addslashes($employe->Nom) }}', '{{ $employe->email ?? '' }}', {{ $employe->email ? 'true' : 'false' }})"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignWebAccessModal">
                                        <i class="bi bi-globe"></i> {{ $employe->email ? 'Modifier accès Web' : 'Créer accès Web' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

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

    <!-- Modal Assigner Accès Web -->
    <div class="modal fade" id="assignWebAccessModal" tabindex="-1" aria-labelledby="assignWebAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignWebAccessModalLabel">{{ __('Gérer l\'accès Web') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignWebAccessForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>{{ __('Configurez l\'accès au portail pour :') }} <strong id="assignWebAccess_employee_name"></strong></p>
                        
                        <div class="form-group mb-3">
                            <label for="assign_email" class="form-label">{{ __('Adresse e-mail') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="assign_email" name="email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="assign_password" class="form-label">{{ __('Mot de passe') }}</label>
                            <input type="password" class="form-control" id="assign_password" name="password" minlength="6">
                            <small class="text-muted" id="password_help_text">{{ __('Laissez vide si vous ne souhaitez pas le modifier. (Minimum 6 caractères)') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Sauvegarder') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function setResetPinAction(url, nom) {
            document.getElementById('resetPinForm').action = url;
            document.getElementById('details_employee_pin').textContent = nom;
        }

        function setAssignWebAccessAction(url, nom, email, isEdit) {
            document.getElementById('assignWebAccessForm').action = url;
            document.getElementById('assignWebAccess_employee_name').textContent = nom;
            document.getElementById('assign_email').value = email;
            document.getElementById('assign_password').value = '';
            
            if (isEdit) {
                document.getElementById('assignWebAccessModalLabel').textContent = "Modifier l'accès Web";
                document.getElementById('password_help_text').textContent = "Laissez vide si vous ne souhaitez pas modifier le mot de passe actuel. (Minimum 6 caractères)";
            } else {
                document.getElementById('assignWebAccessModalLabel').textContent = "Créer un accès Web";
                document.getElementById('password_help_text').textContent = "Veuillez définir un mot de passe pour cette première assignation. (Minimum 6 caractères)";
            }
        }
    </script>
    @endpush
</x-app-layout>