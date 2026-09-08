@extends('layouts.app')

@section('title', 'Périodes de travail')

@section('header')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">⚙️ Périodes de travail</h4>
        <span class="text-muted small">Définir les horaires par poste</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('planning.horaires-types.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter une période
        </a>
        <a href="{{ route('planning.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">

        {{-- Filtres --}}
        <form method="GET" id="filterForm" class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Service</label>
                <select name="service_id" id="filter_service_id" class="form-select">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Poste</label>
                <select name="poste_id" id="filter_poste_id" class="form-select">
                    <option value="">Tous les postes</option>
                    @if(request('service_id'))
                        @foreach($postes->where('department_id', request('service_id')) as $poste)
                            <option value="{{ $poste->id }}" {{ request('poste_id') == $poste->id ? 'selected' : '' }}>
                                {{ $poste->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach($postes as $poste)
                            <option value="{{ $poste->id }}" {{ request('poste_id') == $poste->id ? 'selected' : '' }}>
                                {{ $poste->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <a href="{{ route('planning.horaires-types.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                </a>
                <a href="{{ route('planning.horaires-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle période
                </a>
            </div>
        </form>

        {{-- Compteur --}}
        <div class="mb-3">
            <span class="badge bg-info text-dark">
                <i class="bi bi-list-ul"></i> {{ $horaires->total() }} période(s) trouvée(s)
            </span>
        </div>

        {{-- Tableau --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="color: black; font-weight: 600;">Service</th>
                        <th style="color: black; font-weight: 600;">Poste</th>
                        <th style="color: black; font-weight: 600;">Jours travaillés</th>
                        <th style="color: black; font-weight: 600;">Horaires</th>
                        <th style="color: black; font-weight: 600;">Pause</th>
                        <th style="color: black; font-weight: 600;">2ème période</th>
                        <th style="color: black; font-weight: 600;">Par défaut</th>
                        <th style="color: black; font-weight: 600; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horaires as $horaire)
                        <tr>
                            <td>{{ $horaire->poste?->department?->name ?? 'N/A' }}</td>
                            <td><strong>{{ $horaire->poste?->name ?? 'N/A' }}</strong></td>
                            <td>
                                @php
                                    $jours = explode(',', $horaire->jours_travailles);
                                    $couleurs = ['lundi' => 'primary', 'mardi' => 'secondary', 'mercredi' => 'success', 'jeudi' => 'info', 'vendredi' => 'warning', 'samedi' => 'dark', 'dimanche' => 'danger'];
                                @endphp
                                @foreach($jours as $jour)
                                    <span class="badge bg-{{ $couleurs[$jour] ?? 'secondary' }} me-1">
                                        {{ ucfirst($jour) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $horaire->libelle_horaire }}</span>
                            </td>
                            <td>
                                @if($horaire->pause_debut && $horaire->pause_fin)
                                    <span class="badge bg-warning text-dark">{{ $horaire->libelle_pause }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($horaire->deuxieme_debut && $horaire->deuxieme_fin)
                                    <span class="badge bg-info">{{ $horaire->deuxieme_debut }} - {{ $horaire->deuxieme_fin }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($horaire->par_defaut)
                                    <span class="badge bg-success"> Oui</span>
                                @else
                                    <span class="badge bg-secondary"> Non</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('planning.horaires-types.edit', $horaire->id) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('planning.horaires-types.destroy', $horaire->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Supprimer cette période ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x" style="font-size: 24px;"></i>
                                <br>
                                Aucune période de travail définie.
                                <br>
                                <a href="{{ route('planning.horaires-types.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Créer la première période
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $horaires->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var serviceSelect = document.getElementById('filter_service_id');
        var posteSelect = document.getElementById('filter_poste_id');
        var form = document.getElementById('filterForm');

        function loadPostes(serviceId, selectedPosteId) {
            if (!serviceId) {
                window.location.href = '{{ route("planning.horaires-types.index") }}';
                return;
            }

            posteSelect.innerHTML = '<option value="">Chargement...</option>';

            fetch('/planning/api/job-titles-by-department/' + serviceId)
                .then(response => response.json())
                .then(data => {
                    posteSelect.innerHTML = '<option value="">Tous les postes</option>';
                    data.forEach(function(poste) {
                        var selected = (poste.id == selectedPosteId) ? 'selected' : '';
                        posteSelect.innerHTML += '<option value="' + poste.id + '" ' + selected + '>' + poste.name + '</option>';
                    });
                })
                .catch(function() {
                    posteSelect.innerHTML = '<option value="">Tous les postes</option>';
                });
        }

        serviceSelect.addEventListener('change', function() {
            form.submit();
        });

        posteSelect.addEventListener('change', function() {
            form.submit();
        });

        var currentServiceId = '{{ request('service_id') }}';
        var currentPosteId = '{{ request('poste_id') }}';
        
        if (currentServiceId) {
            loadPostes(currentServiceId, currentPosteId);
        }
    });
</script>
@endpush