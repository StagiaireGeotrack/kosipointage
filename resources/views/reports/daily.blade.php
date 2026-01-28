{{-- resources/views/reports/daily.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Rapport (Quotidien)') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.export.excel', ['type' => 'daily'] + request()->query()) }}" class="btn btn-success">
                    {{ __('Exporter en EXCEL') }}
                </a>
                <a href="{{ route('reports.export.pdf', ['type' => 'daily'] + request()->query()) }}" class="btn btn-danger">
                    {{ __('Exporter en PDF') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Filtres -->
                <form action="{{ route('reports.daily') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="SiegeID" :value="__('Siège')" />
                            <select id="SiegeID" name="SiegeID" class="form-select mt-1" onchange="this.form.submit()">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <select id="employee_id" name="employee_id" class="form-select mt-1">
                                <option value="">{{ __('Tous') }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->ID }}" {{ isset($filters['employee_id']) && $filters['employee_id'] == $employe->ID ? 'selected' : '' }}>
                                        {{ $employe->Nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="date_from" :value="__('Date début')" />
                            <x-text-input id="date_from" type="date" name="date_from" class="form-control mt-1" :value="$filters['date_from'] ?? ''" />
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <x-input-label for="date_to" :value="__('Date fin')" />
                            <x-text-input id="date_to" type="date" name="date_to" class="form-control mt-1" :value="$filters['date_to'] ?? ''" />
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Valider') }}
                            </button>
                            <a href="{{ route('reports.daily') }}" class="btn btn-secondary">
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    </div>
                </form>
                
                <!-- Tableau des rapports -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Siège') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Employé') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Heure d\'entrée') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Déjeuner') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Heure de sortie') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Heure totale') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rapports as $rapport)
                                <tr>
                                    <td class="align-middle">
                                        {{ ucfirst(\Carbon\Carbon::parse($rapport->date_reel)->isoFormat('dddd D MMMM YYYY')) }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->siege_nom }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->employee_nom }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->heure_entree }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->pause_dejeuner }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->heure_sortie }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $rapport->total_heure_journee }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('pointages.show.details', [ $rapport->employee_id , $rapport->date_reel , $rapport->type_travail ?? null ]) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        {{ __('Aucun rapport pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-1">
                    {{ $rapports->links("pagination.custom") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>