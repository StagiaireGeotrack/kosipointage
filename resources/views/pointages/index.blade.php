{{-- resources/views/pointages/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('app.clock_ins') }}
            </h2>
            <a href="{{ route('pointages.create') }}" class="btn btn-primary">
                {{ __('app.create_new') }}
            </a>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Filtres -->
                    <form action="{{ route('pointages.index') }}" method="GET" class="mb-4">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="search" :value="__('app.search')" />
                                <x-text-input id="search" name="search" type="text" class="form-control mt-1" :value="$filters['search'] ?? ''" placeholder="{{ __('app.employee_name_or_badge') }}" />
                            </div>
                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="SiegeID" :value="__('app.office')" />
                                <select id="SiegeID" name="SiegeID" class="form-select mt-1" onchange="updateEmployeesList()">
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
                                            {{ $employe->Nom }} ({{ $employe->BadgeID }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="type_" :value="__('app.type')" />
                                <select id="type_" name="type_" class="form-select mt-1">
                                    <option value="">{{ __('app.all') }}</option>
                                    <option value="entry" {{ isset($filters['type_']) && $filters['type_'] == 'entry' ? 'selected' : '' }}>{{ __('app.entry') }}</option>
                                    <option value="exit" {{ isset($filters['type_']) && $filters['type_'] == 'exit' ? 'selected' : '' }}>{{ __('app.exit') }}</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <x-input-label for="auth_method" :value="__('app.auth_method')" />
                                <select id="auth_method" name="auth_method" class="form-select mt-1">
                                    <option value="">{{ __('app.all') }}</option>
                                    <option value="badge" {{ isset($filters['auth_method']) && $filters['auth_method'] == 'badge' ? 'selected' : '' }}>{{ __('app.badge') }}</option>
                                    <option value="face" {{ isset($filters['auth_method']) && $filters['auth_method'] == 'face' ? 'selected' : '' }}>{{ __('app.face_recognition') }}</option>
                                    <option value="pin" {{ isset($filters['auth_method']) && $filters['auth_method'] == 'pin' ? 'selected' : '' }}>{{ __('app.pin_code') }}</option>
                                    <option value="admin" {{ isset($filters['auth_method']) && $filters['auth_method'] == 'admin' ? 'selected' : '' }}>{{ __('app.admin_manual') }}</option>
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
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('app.filter') }}
                            </button>
                            <a href="{{ route('pointages.index') }}" class="btn btn-secondary">
                                {{ __('app.reset') }}
                            </a>
                        </div>
                    </form>
                    
                    <!-- Exports -->
                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <a href="{{ route('pointages.export.excel', request()->query()) }}" class="btn btn-success">
                            {{ __('app.export_excel') }}
                        </a>
                        <a href="{{ route('pointages.export.pdf', request()->query()) }}" class="btn btn-danger">
                            {{ __('app.export_pdf') }}
                        </a>
                    </div>

                    <!-- Tableau des pointages -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.employee') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.date_time') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.type') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.auth_method') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.office') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.photo') }}
                                    </th>
                                    <th class="text-uppercase small fw-semibold text-secondary">
                                        {{ __('app.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pointages as $pointage)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                @if ($pointage->employe->HasFaceSetup)
                                                    <img src="{{ route('employes.face.thumbnail', $pointage->employe->ID) }}" alt="{{ $pointage->employe->Nom }}" class="rounded-circle me-2" style="height: 32px; width: 32px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $pointage->employe->Nom }}</div>
                                                    <small class="text-muted">{{ $pointage->employe->BadgeID }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            {{ $pointage->timestamp_->format('d/m/Y H:i:s') }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($pointage->type_ == 'entry')
                                                <span class="badge bg-success">
                                                    {{ __('app.entry') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    {{ __('app.exit') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @switch($pointage->auth_method)
                                                @case('badge')
                                                    <span class="badge bg-info">
                                                        {{ __('app.badge') }}
                                                    </span>
                                                    @break
                                                @case('face')
                                                    <span class="badge bg-primary">
                                                        {{ __('app.face') }}
                                                    </span>
                                                    @break
                                                @case('pin')
                                                    <span class="badge bg-warning text-dark">
                                                        {{ __('app.pin') }}
                                                    </span>
                                                    @break
                                                @case('admin')
                                                    <span class="badge bg-secondary">
                                                        {{ __('app.admin') }}
                                                    </span>
                                                    @break
                                                @default
                                                    {{ $pointage->auth_method }}
                                            @endswitch
                                        </td>
                                        <td class="align-middle">
                                            {{ $pointage->siege->Nom }}
                                        </td>
                                        <td class="align-middle">
                                            @if ($pointage->photo_path)
                                                <a href="{{ route('pointages.photo', $pointage->ID) }}" target="_blank">
                                                    <img src="{{ route('pointages.photo.thumbnail', $pointage->ID) }}" alt="{{ __('app.clock_in_photo') }}" class="rounded-circle" style="height: 40px; width: 40px; object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('app.no_photo') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('pointages.show', $pointage->ID) }}" class="text-primary" title="Voir">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('pointages.edit', $pointage->ID) }}" class="text-warning" title="Modifier">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('pointages.destroy', $pointage->ID) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('{{ __('app.confirm_delete') }}')" title="Supprimer">
                                                        <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            {{ __('app.no_clock_ins') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $pointages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        function updateEmployeesList() {
            const siegeId = document.getElementById('SiegeID').value;
            const employeeSelect = document.getElementById('employee_id');
            
            if (!siegeId) {
                // Si aucun siège n'est sélectionné, ne rien faire
                return;
            }
            
            // Vider la liste des employés sauf l'option "Tous"
            while (employeeSelect.options.length > 1) {
                employeeSelect.remove(1);
            }
            
            // Appel AJAX pour récupérer les employés du siège sélectionné
            fetch(`{{ route('pointages.employees-by-siege') }}?siege_id=${siegeId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(employe => {
                        const option = document.createElement('option');
                        option.value = employe.ID;
                        option.textContent = `${employe.Nom} (${employe.BadgeID})`;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des employés:', error));
        }
    </script>
    @endpush
</x-app-layout>