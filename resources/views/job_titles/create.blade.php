{{-- resources/views/job_titles/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-plus-circle"></i> {{ __('Nouveau Poste') }}
            </h2>
            <a href="{{ route('admin.job-titles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.job-titles.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="name" :value="__('Nom du poste')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1"
                                :value="old('name')" placeholder="{{ __('Ex: Technicien, Vendeur...') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="code" :value="__('Code')" />
                            <x-text-input id="code" name="code" type="text" class="form-control mt-1"
                                :value="old('code')" placeholder="{{ __('Ex: TECH, VEN...') }}" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            <small class="text-muted">{{ __('Code court pour identifier le poste') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="hierarchy_level_id" :value="__('Niveau KOSI')" />
                            <select id="hierarchy_level_id" name="hierarchy_level_id" class="form-select mt-1">
                                <option value="">{{ __('Non défini') }}</option>
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('hierarchy_level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->code }} - {{ $level->name }} (Rang {{ $level->rank }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('hierarchy_level_id')" class="mt-2" />
                            <small class="text-muted">{{ __('Niveau hiérarchique KOSI associé à ce poste') }}</small>
                        </div>

                        {{-- NOUVEAU CHAMP : Service --}}
                        <div class="col-md-6">
                            <x-input-label for="department_id" :value="__('Service')" />
                            <select id="department_id" name="department_id" class="form-select mt-1">
                                <option value="">{{ __('Aucun service') }}</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} ({{ $dept->site?->Nom ?? 'Site inconnu' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                            <small class="text-muted">{{ __('Service auquel appartient ce poste (optionnel)') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Exemples de postes par niveau KOSI :</strong>
                                <ul class="mb-0 mt-1">
                                    <li><strong>N0</strong> - Agent, ouvrier, opérateur, formateur, vendeur, chauffeur, serveur</li>
                                    <li><strong>N1</strong> - Team Leader, chef d'équipe, agent senior, référent</li>
                                    <li><strong>N2</strong> - Superviseur, chef de chantier, responsable rayon</li>
                                    <li><strong>N3</strong> - Responsable RH, responsable production, responsable logistique</li>
                                    <li><strong>N4</strong> - Manager, chef de département, responsable régional</li>
                                    <li><strong>N5</strong> - Directeur technique, directeur commercial, directeur usine</li>
                                    <li><strong>N6</strong> - Directeur général, direction générale</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <x-primary-button class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('admin.job-titles.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
