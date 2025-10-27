{{-- resources/views/reports/day_night.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('app.day_night_reports') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.export.excel', ['type' => 'day-night'] + request()->query()) }}" class="btn btn-success">
                    {{ __('app.export_excel') }}
                </a>
                <a href="{{ route('reports.export.pdf', ['type' => 'day-night'] + request()->query()) }}" class="btn btn-danger">
                    {{ __('app.export_pdf') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Filtres -->
                    <form action="{{ route('reports.day-night') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="SiegeID" :value="__('app.office')" />
                                <select id="SiegeID" name="SiegeID" class="form-select mt-1" onchange="this.form.submit()">
                                    <option value="">{{ __('app.all') }}</option>
                                    @foreach($sieges as $siege)
                                        <option value="{{ $siege->ID }}" {{ isset($filters['SiegeID']) && $filters['SiegeID'] == $siege->ID ? 'selected' : '' }}>
                                            {{ $siege->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="employee_id" :value="__('app.employee')" />
                                <select id="employee_id" name="employee_id" class="form-select mt-1">
                                    <option value="">{{ __('app.all') }}</option>
                                    @foreach($employes as $employe)
                                        <option value="{{ $employe->ID }}" {{ isset($filters['employee_id']) && $filters['employee_id'] == $employe->ID ? 'selected' : '' }}>
                                            {{ $employe->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="type_travail" :value="__('app.work_type')" />
                                <select id="type_travail" name="type_travail" class="form-select mt-1">
                                    <option value="">{{ __('app.all') }}</option>
                                    <option value="JOUR" {{ isset($filters['type_travail']) && $filters['type_travail'] == 'JOUR' ? 'selected' : '' }}>{{ __('app.day_shift') }}</option>
                                    <option value="NUIT" {{ isset($filters['type_travail']) && $filters['type_travail'] == 'NUIT' ? 'selected' : '' }}>{{ __('app.night_shift') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="date_from" :value="__('app.date_from')" />
                                <x-text-input id="date_from" type="date" name="date_from" class="form-control mt-1" :value="$filters['date_from'] ?? ''" />
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="date_to" :value="__('app.date_to')" />
                                <x-text-input id="date_to" type="date" name="date_to" class="form-control mt-1" :value="$filters['date_to'] ?? ''" />
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('app.filter') }}
                                </button>
                                <a href="{{ route('reports.day-night') }}" class="btn btn-secondary">
                                    {{ __('app.reset') }}
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
                                        {{ __('app.date') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.siege') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.employee') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.work_type') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.entry_time') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.lunch_break') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.exit_time') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.total_hours') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rapports as $rapport)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $rapport->date_pointage }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->siege_nom }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->employee_nom }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($rapport->type_travail == 'JOUR')
                                                <span class="badge bg-warning text-dark">
                                                    {{ __('app.day_shift') }}
                                                </span>
                                            @else
                                                <span class="badge bg-primary">
                                                    {{ __('app.night_shift') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->heure_entree }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->pause_dejeuner ?: '-' }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->heure_sortie }}
                                        </td>
                                        <td class="align-middle">
                                            {{ $rapport->total_heure_journee }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            {{ __('app.no_records') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $rapports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>