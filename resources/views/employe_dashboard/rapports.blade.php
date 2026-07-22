<x-employe-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Mes Rapports Quotidiens') }}
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employe.rapports') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date_debut" class="form-label text-muted small fw-bold">Date de début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-4">
                    <label for="date_fin" class="form-label text-muted small fw-bold">Date de fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-4">
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
                            <th class="px-4 py-3 text-dark">{{ __('Date') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Heure Entrée') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Pause Déjeuner') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Heure Sortie') }}</th>
                            <th class="px-4 py-3 text-dark">{{ __('Total Heure') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rapports as $rapport)
                            <tr>
                                <td class="px-4 py-3 fw-medium">
                                    {{ ucfirst(\Carbon\Carbon::parse($rapport->date_reel)->isoFormat('dddd D MMMM YYYY')) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-success"><i class="bi bi-box-arrow-in-right me-1"></i> {{ $rapport->heure_entree ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    <i class="bi bi-cup-hot me-1"></i> {{ $rapport->pause_dejeuner ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-danger"><i class="bi bi-box-arrow-right me-1"></i> {{ $rapport->heure_sortie ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 fw-bold text-primary">
                                    <i class="bi bi-clock me-1"></i> {{ $rapport->total_heure_journee ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                    {{ __('Aucun rapport trouvé.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rapports->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $rapports->links('pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</x-employe-layout>
