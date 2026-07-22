<x-employe-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Mes Pointages') }}
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employe.pointages') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="date_debut" class="form-label text-muted small fw-bold">Date de début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label text-muted small fw-bold">Date de fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <label for="type_pointage" class="form-label text-muted small fw-bold">Type</label>
                    <select class="form-select" id="type_pointage" name="type_pointage">
                        <option value="">Tous les types</option>
                        <option value="entree" {{ request('type_pointage') == 'entree' ? 'selected' : '' }}>Entrée</option>
                        <option value="sortie" {{ request('type_pointage') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-dark">{{ __('Date et Heure') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Type') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Méthode') }}</th>
                            <th class="px-4 py-3 text-center text-dark">{{ __('Photo') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Localisation') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pointages as $pointage)
                            <tr>
                                <td class="px-4 py-3 fw-medium">
                                    {{ \Carbon\Carbon::parse($pointage->timestamp_)->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if(strtolower($pointage->type_) == 'entree' || strtolower($pointage->type_) == 'entrée' || strtolower($pointage->type_) == 'entry')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Entrée
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                            <i class="bi bi-box-arrow-right me-1"></i> Sortie
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-muted small">
                                        {{ $pointage->auth_method ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($pointage->photo_path)
                                        <a href="{{ asset('storage/' . $pointage->photo_path) }}" target="_blank" class="text-primary" title="Voir la photo">
                                            <i class="bi bi-camera-fill fs-5"></i>
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="bi bi-camera me-1"></i> -</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($pointage->latitude && $pointage->longitude)
                                        <a href="https://www.google.com/maps?q={{ $pointage->latitude }},{{ $pointage->longitude }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Carte
                                        </a>
                                    @else
                                        <span class="text-muted small">Non disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-clock-history fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    {{ __('Aucun pointage trouvé.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pointages->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $pointages->links('pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</x-employe-layout>
