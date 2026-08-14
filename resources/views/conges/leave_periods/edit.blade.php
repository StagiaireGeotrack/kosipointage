<x-app-layout>

<div class="container-fluid">

    {{-- TITRE ADAPTATIF --}}
    @if($isEditingGlobalAsSite)
        <h2 class="mb-2">Personnaliser pour mon siège</h2>
        <div class="alert alert-primary d-flex align-items-center mb-4">
            <i class="bi bi-globe me-2"></i>
            <div>
                Vous personnalisez le global <strong>{{ $period->name }}</strong> pour votre siège.
                <span class="badge bg-dark ms-2">{{ Auth::user()->employe?->siege?->nom ?? 'Votre siège' }}</span>
            </div>
        </div>
    @else
        <h2 class="mb-2">Modifier la période : {{ $period->name }}</h2>

        <div class="alert alert-secondary d-flex align-items-center mb-4">
            <i class="bi bi-building me-2"></i>
            <div>
                @if(is_null($period->site_id))
                    <span class="badge bg-dark me-2"><i class="bi bi-globe"></i> Période globale</span>
                    Visible par tous les sièges. Chaque siège peut la personnaliser si besoin.
                @else
                    <span class="badge bg-secondary me-2"><i class="bi bi-building"></i> Période spécifique</span>
                    Siège propriétaire : <strong>{{ $period->site?->Nom ?? 'Siège #'.$period->site_id }}</strong>
                @endif

                @if($isSuperAdmin)
                    <span class="badge bg-dark ms-2">Super Admin</span>
                @endif
            </div>
        </div>
    @endif

    <div class="row">
        <!-- PARTIE GAUCHE : Formulaire -->
        <div class="col-lg-8">
            <div class="card mb-4 {{ is_null($period->site_id) ? 'border-primary' : 'border-secondary' }}">
                <div class="card-header {{ is_null($period->site_id) ? 'bg-primary text-white' : 'bg-secondary text-white' }}">
                    @if($isEditingGlobalAsSite)
                        <i class="bi bi-sliders"></i> Vos réglages pour ce siège
                    @elseif(is_null($period->site_id))
                        <i class="bi bi-globe"></i> Règle globale (modèle par défaut pour tous les sièges)
                    @else
                        <i class="bi bi-building"></i> Règle du siège {{ $period->site?->Nom ?? '#'.$period->site_id }}
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.leave-periods.update', $period) }}" method="POST">
                        @csrf @method('PUT')

                        {{-- Si Admin Siège édite un global : leave_type verrouillé --}}
                        @if($isEditingGlobalAsSite)
                            <input type="hidden" name="leave_type_id" value="{{ $period->leave_type_id }}">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Type de congé</label>
                                <div class="form-control bg-light" readonly>
                                    {{ $period->leaveType->name ?? 'N/A' }} ({{ $period->leaveType->code ?? '' }})
                                </div>
                                <small class="text-muted">Le type de congé ne peut pas être changé lors d'une personnalisation.</small>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Type de congé</label>
                                    <select name="leave_type_id" class="form-select" required>
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}" @selected($period->leave_type_id == $type->id)>
                                                {{ $type->name }} ({{ $type->code }})
                                                @if(is_null($type->site_id))
                                                    — Global
                                                @else
                                                    — {{ $type->site?->Nom ?? 'Siège #'.$type->site_id }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nom de la période</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $isEditingGlobalAsSite ? ($siteOverride->name ?? $period->name) : $period->name) }}" required>
                                </div>
                            </div>
                        @endif

                        {{-- Si Super Admin ou période spécifique : name dans une row séparée si pas déjà affiché --}}
                        @if(!$isEditingGlobalAsSite)
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nom de la période</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $period->name) }}" required>
                            </div>
                        </div>
                        @else
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom de la période</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $siteOverride->name ?? $period->name) }}"
                                   placeholder="Laissez vide pour garder le nom global">
                            <small class="text-muted">Laissez vide pour reprendre le nom du global.</small>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date de début</label>
                                <input type="date" name="start_date" class="form-control"
                                       value="{{ old('start_date', $isEditingGlobalAsSite ? ($siteOverride?->start_date?->format('Y-m-d') ?? $period->start_date->format('Y-m-d')) : $period->start_date->format('Y-m-d')) }}">
                                @if($isEditingGlobalAsSite)<small class="text-muted">Vide = global</small>@endif
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Date de fin</label>
                                <input type="date" name="end_date" class="form-control"
                                       value="{{ old('end_date', $isEditingGlobalAsSite ? ($siteOverride?->end_date?->format('Y-m-d') ?? $period->end_date->format('Y-m-d')) : $period->end_date->format('Y-m-d')) }}">
                                @if($isEditingGlobalAsSite)<small class="text-muted">Vide = global</small>@endif
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Limite de pose</label>
                                <input type="date" name="submission_deadline" class="form-control"
                                       value="{{ old('submission_deadline', $isEditingGlobalAsSite ? ($siteOverride?->submission_deadline?->format('Y-m-d') ?? $period->submission_deadline?->format('Y-m-d')) : $period->submission_deadline?->format('Y-m-d')) }}">
                                @if($isEditingGlobalAsSite)<small class="text-muted">Vide = global</small>@endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="allow_rollover"
                                           value="1" id="allowRollover"
                                           @checked(old('allow_rollover', $isEditingGlobalAsSite ? ($siteOverride?->allow_rollover ?? $period->allow_rollover) : $period->allow_rollover))>
                                    <label class="form-check-label fw-bold" for="allowRollover">Report autorisé</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Max jours reportés</label>
                                <input type="number" name="max_rollover_days" class="form-control"
                                       value="{{ old('max_rollover_days', $isEditingGlobalAsSite ? ($siteOverride?->max_rollover_days ?? $period->max_rollover_days) : $period->max_rollover_days) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Expiration du report</label>
                                <input type="date" name="rollover_expiry_date" class="form-control"
                                       value="{{ old('rollover_expiry_date', $isEditingGlobalAsSite ? ($siteOverride?->rollover_expiry_date?->format('Y-m-d') ?? $period->rollover_expiry_date?->format('Y-m-d')) : $period->rollover_expiry_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="preparing" @selected(old('status', $isEditingGlobalAsSite ? ($siteOverride?->status ?? $period->status) : $period->status) == 'preparing')>Préparation</option>
                                    <option value="open" @selected(old('status', $isEditingGlobalAsSite ? ($siteOverride?->status ?? $period->status) : $period->status) == 'open')>Ouverte</option>
                                    <option value="closed" @selected(old('status', $isEditingGlobalAsSite ? ($siteOverride?->status ?? $period->status) : $period->status) == 'closed')>Clôturée</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_default"
                                           value="1" id="isDefault"
                                           @checked(old('is_default', $isEditingGlobalAsSite ? ($siteOverride?->is_default ?? $period->is_default) : $period->is_default))>
                                    <label class="form-check-label fw-bold" for="isDefault">Période par défaut</label>
                                    <small class="text-muted d-block">C'est cette période qui sera proposée automatiquement aux employés</small>
                                </div>
                            </div>
                            @if(!$isEditingGlobalAsSite)
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           value="1" id="isActive" @checked(old('is_active', $period->is_active))>
                                    <label class="form-check-label fw-bold" for="isActive">Actif</label>
                                </div>
                            </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            @if($isEditingGlobalAsSite)
                                Enregistrer ma personnalisation
                            @elseif(is_null($period->site_id))
                                Mettre à jour la règle globale
                            @else
                                Mettre à jour la période
                            @endif
                        </button>

                        <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- PARTIE DROITE -->
        <div class="col-lg-4">
            @if($isEditingGlobalAsSite)
                {{-- Admin Siège : infos du global original --}}
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-globe"></i> Global original (non modifié)
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">Ces valeurs servent de base si vous laissez un champ vide.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Nom</span><strong>{{ $period->name }}</strong>
                            </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                <span>Début</span><strong>{{ $period->start_date->format('d/m/Y') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Fin</span><strong>{{ $period->end_date->format('d/m/Y') }}</strong>
                            </li>
                            @if($period->submission_deadline)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Limite</span><strong>{{ $period->submission_deadline->format('d/m/Y') }}</strong>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Report</span><strong>{{ $period->allow_rollover ? 'Oui' : 'Non' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Statut</span>
                                <span>
                                    @if($period->status == 'open')
                                        <span class="badge bg-success">Ouverte</span>
                                    @elseif($period->status == 'closed')
                                        <span class="badge bg-secondary">Clôturée</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Préparation</span>
                                    @endif
                                </span>
                            </li>
                        </ul>

                        @if($siteOverride)
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Vous avez déjà une personnalisation active. Les champs laissés vides reprendront ces valeurs globales.
                            </div>
                        @else
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Vous n'avez pas encore personnalisé cette période. Modifiez les champs à gauche pour créer votre version.
                            </div>
                        @endif
                    </div>
                </div>

            @elseif($isSuperAdmin && is_null($period->site_id))
                {{-- SUPER ADMIN : gestion des overrides par siège --}}
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <i class="bi bi-sliders"></i> Personnalisations par siège
                    </div>
                    <div class="card-body">

                        @if($period->siteSettings->count() > 0)
                            <p class="text-muted small mb-2">
                                Ces sièges ont des règles différentes du modèle global :
                            </p>
                            <ul class="list-group mb-3">
                                @foreach($period->siteSettings as $setting)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $setting->site?->nom ?? 'Siège #'.$setting->site_id }}</strong>

                                        <div class="small text-muted mt-1">
                                            @if($setting->name)
                                                <div>• Nom : {{ $setting->name }}</div>
                                            @endif
                                            @if($setting->start_date)
                                                <div>• Début : {{ $setting->start_date->format('d/m/Y') }}</div>
                                            @endif
                                            @if($setting->end_date)
                                                <div>• Fin : {{ $setting->end_date->format('d/m/Y') }}</div>
                                            @endif
                                            @if(!is_null($setting->allow_rollover))
                                                <div>• Report : {{ $setting->allow_rollover ? 'Oui' : 'Non' }}</div>
                                            @endif
                                            @if($setting->max_rollover_days)
                                                <div>• Max report : {{ $setting->max_rollover_days }} jours</div>
                                            @endif
                                            @if($setting->rollover_expiry_date)
                                                <div>• Exp. report : {{ $setting->rollover_expiry_date->format('d/m/Y') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.leave-periods.site-override.destroy', $setting) }}"
                                          method="POST" onsubmit="return confirm('Supprimer cette personnalisation ?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-light border mb-3">
                                <i class="bi bi-info-circle"></i> Aucune personnalisation. Tous les sièges utilisent la règle globale ci-contre.
                            </div>
                        @endif

                        <hr>
                        <h6 class="mb-3 fw-bold">Ajouter une personnalisation</h6>
                        <p class="small text-muted">
                            Choisissez un siège et remplissez <strong>uniquement</strong> les champs à modifier. Les champs laissés vides reprendront la valeur de la règle globale.
                        </p>

                        <form action="{{ route('admin.leave-periods.site-override.store', $period) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Siège concerné *</label>
                                <select name="site_id" class="form-select" required>
                                    <option value="">-- Choisir un siège --</option>
                                    @foreach($siblingSites as $site)
                                        <option value="{{ $site->ID }}">
                                            {{ $site->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nom (laisser vide = identique au global)</label>
                                <input type="text" name="name" class="form-control" placeholder="Ex: Période 2026 (Casablanca)">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Date de début</label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Limite de pose</label>
                                <input type="date" name="submission_deadline" class="form-control">
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Max jours reportés</label>
                                    <input type="number" name="max_rollover_days" class="form-control" min="0">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Expiration report</label>
                                    <input type="date" name="rollover_expiry_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="allow_rollover" value="1" id="ovRollover">
                                    <label class="form-check-label" for="ovRollover">Report autorisé</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="ovDefault">
                                    <label class="form-check-label" for="ovDefault">Par défaut</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-plus-lg"></i> Enregistrer la personnalisation
                            </button>
                        </form>
                    </div>
                </div>

            @else
                {{-- Période spécifique : info à la place du bloc override --}}
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <i class="bi bi-info-circle"></i> Informations
                    </div>
                    <div class="card-body">
                        <p>Cette période est <strong>spécifique</strong> au siège <strong>{{ $period->site?->nom ?? '#'.$period->site_id }}</strong>.</p>
                        <p class="text-muted small">Les périodes spécifiques ne peuvent pas être personnalisées par d'autres sièges. Modifiez directement les valeurs ci-contre.</p>

                        <hr>
                        <div class="alert alert-light border">
                            <small>
                                <strong>Type :</strong> {{ $period->leaveType->name ?? 'N/A' }}<br>
                                <strong>Dates :</strong> {{ $period->start_date->format('d/m/Y') }} → {{ $period->end_date->format('d/m/Y') }}<br>
                                <strong>Statut :</strong>
                                @if($period->status == 'open')
                                    <span class="badge bg-success">Ouverte</span>
                                @elseif($period->status == 'closed')
                                    <span class="badge bg-secondary">Clôturée</span>
                                @else
                                    <span class="badge bg-warning text-dark">Préparation</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

</x-app-layout>