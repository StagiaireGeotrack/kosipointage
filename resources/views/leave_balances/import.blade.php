{{-- resources/views/leave_balances/import.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-upload"></i> {{ __('Importer les soldes') }}
            </h2>
            <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('import_errors'))
                    <div class="alert alert-danger">
                        <h6>{{ __('Erreurs d\'import') }}</h6>
                        <ul class="mb-0">
                            @foreach(session('import_errors') as $error)
                                <li>Ligne {{ $error['row'] }} - {{ $error['field'] }}: {{ $error['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    {{ __('Importez un fichier Excel ou CSV avec les soldes initiaux des employés.') }}
                    <br>
                    <a href="{{ route('leave-balances.download-template') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-download"></i> {{ __('Télécharger le modèle') }}
                    </a>
                </div>

                <form method="POST" action="{{ route('leave-balances.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        {{-- Sélection du siège (pour Super Admin) --}}
                        @if(auth()->user()->IsSuperAdmin)
                            <div class="col-md-6">
                                <x-input-label for="site_id" :value="__('Siège')" />
                                <span class="text-danger">*</span>
                                <select id="site_id" name="site_id" class="form-select mt-1" required>
                                    <option value="">{{ __('Sélectionnez un siège') }}</option>
                                    @foreach($sites ?? [] as $site)
                                        <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                            {{ $site->Nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                            </div>
                        @else
                            {{-- Admin Simple : siège fixe --}}
                            <div class="col-md-6">
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-building"></i>
                                    {{ __('Siège : ') }}
                                    <strong>{{ auth()->user()->siege->Nom ?? 'N/A' }}</strong>
                                    <input type="hidden" name="site_id" value="{{ auth()->user()->SiegeID }}">
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <x-input-label for="period_id" :value="__('Période')" />
                            <span class="text-danger">*</span>
                            <select id="period_id" name="period_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez une période') }}</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} ({{ $period->start_date->format('d/m/Y') }} - {{ $period->end_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <x-input-label for="file" :value="__('Fichier Excel / CSV')" />
                            <span class="text-danger">*</span>
                            <input type="file" name="file" id="file" class="form-control mt-1" accept=".xlsx,.xls,.csv" required>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            <small class="text-muted">{{ __('Formats supportés: .xlsx, .xls, .csv') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <div class="form-check">
                                {{-- MODIFICATION ICI : false au lieu de true pour décoché par défaut --}}
                                <input type="checkbox" name="simulate" id="simulate" class="form-check-input" value="1" 
                                    {{ old('simulate', false) ? 'checked' : '' }}>
                                <label for="simulate" class="form-check-label">
                                    {{ __('Mode simulation (vérifier sans importer)') }}
                                </label>
                            </div>
                            <small class="text-muted">{{ __('En mode simulation, les données sont vérifiées mais pas enregistrées.') }}</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> {{ __('Importer') }}
                        </button>
                        <a href="{{ route('leave-balances.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>

                <!-- Historique des imports -->
                @if(isset($batches) && $batches->count() > 0)
                    <hr>
                    <h5 class="mt-4">{{ __('Historique des imports') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Lot') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Statut') }}</th>
                                    <th>{{ __('Succès') }}</th>
                                    <th>{{ __('Échecs') }}</th>
                                    <th>{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batches as $batch)
                                    <tr>
                                        <td><code>{{ $batch->batch_number }}</code></td>
                                        <td>{{ $batch->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $batch->status_color }}">
                                                {{ $batch->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-success">{{ $batch->successful_records }}</td>
                                        <td class="text-danger">{{ $batch->failed_records }}</td>
                                        <td>{{ $batch->total_records }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $batches->links() }}
                @endif
            </div>
        </div>
    </div>
</x-app-layout>