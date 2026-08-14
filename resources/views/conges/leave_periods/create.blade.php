<x-app-layout>

<div class="container-fluid">
    <h2 class="mb-2">Nouvelle période de référence</h2>

    @if($isSuperAdmin)
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <i class="bi bi-shield-fill me-2"></i>
            <div>
                <strong>Mode Super Admin</strong> — Vous pouvez créer une période <u>globale</u> (tous les sièges) ou <u>spécifique</u> à un siège.
            </div>
        </div>
    @else
        <div class="alert alert-info d-flex align-items-center mb-3">
            <i class="bi bi-building me-2"></i>
            <div>
                Cette période sera créée pour votre siège : <strong>{{ Auth::user()->employe?->siege?->nom ?? 'Votre siège' }}</strong>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.leave-periods.store') }}" method="POST">
                @csrf

                {{-- SUPER ADMIN : choix Global vs Spécifique --}}
                @if($isSuperAdmin)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Portée de la période *</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check card p-3 border">
                                    <input class="form-check-input" type="radio" name="scope" id="scopeGlobal" value="global" checked onchange="toggleSiteSelect()">
                                    <label class="form-check-label fw-bold" for="scopeGlobal">
                                        🌍 <strong>Global</strong> — Tous les sièges
                                    </label>
                                    <small class="text-muted d-block">Les admins de chaque siège pourront personnaliser cette période si besoin.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check card p-3 border">
                                    <input class="form-check-input" type="radio" name="scope" id="scopeSite" value="site" onchange="toggleSiteSelect()">
                                    <label class="form-check-label fw-bold" for="scopeSite">
                                        🏢 <strong>Spécifique</strong> — Un seul siège
                                    </label>
                                    <small class="text-muted d-block">Cette période n'appartiendra qu'au siège choisi.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="siteSelectContainer" style="display: none;">
                        <label class="form-label fw-bold">Siège concerné *</label>
                        <select name="site_id" class="form-select">
                            <option value="">-- Choisir un siège --</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->ID }}">{{ $site->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="scope" value="site">
                    <input type="hidden" name="site_id" value="{{ $userSiteId }}">
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Type de congé *</label>
                        <select name="leave_type_id" class="form-select" required>
                            <option value="">-- Choisir --</option>
                            
                            @php
                                $globalTypes = $leaveTypes->whereNull('site_id');
                                $siteTypes = $leaveTypes->whereNotNull('site_id');
                            @endphp
                            
                            @if($globalTypes->count())
                                <optgroup label="🌍 Types globaux">
                                    @foreach($globalTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            
                            @foreach($siteTypes->groupBy('site_id') as $sid => $types)
                                <optgroup label="🏢 {{ $types->first()->site?->nom ?? 'Siège #'.$sid }}">
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nom de la période *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ex: Période 2026-2027" required>
                    </div>
                </div>

                {{-- ... reste du formulaire identique ... --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Date de début *</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Date de fin *</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Limite de pose</label>
                        <input type="date" name="submission_deadline" class="form-control" value="{{ old('submission_deadline') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="allow_rollover" value="1" id="allowRollover" @checked(old('allow_rollover'))>
                            <label class="form-check-label" for="allowRollover">Report autorisé</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Max jours reportés</label>
                        <input type="number" name="max_rollover_days" class="form-control" value="{{ old('max_rollover_days') }}" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Expiration report</label>
                        <input type="date" name="rollover_expiry_date" class="form-control" value="{{ old('rollover_expiry_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Statut</label>
                        <select name="status" class="form-select">
                            <option value="preparing" @selected(old('status') == 'preparing')>Préparation</option>
                            <option value="open" @selected(old('status') == 'open')>Ouverte</option>
                            <option value="closed" @selected(old('status') == 'closed')>Clôturée</option>
                        </select>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault" @checked(old('is_default'))>
                    <label class="form-check-label fw-bold" for="isDefault">Période par défaut pour ce type de congé</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="{{ route('admin.leave-periods.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSiteSelect() {
    const isSite = document.getElementById('scopeSite').checked;
    document.getElementById('siteSelectContainer').style.display = isSite ? 'block' : 'none';
    document.querySelector('select[name="site_id"]').required = isSite;
}
</script>

</x-app-layout>