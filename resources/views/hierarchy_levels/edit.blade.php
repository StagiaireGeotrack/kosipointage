{{-- resources/views/hierarchy_levels/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-pencil"></i> {{ __('Modifier le Niveau') }} : {{ $hierarchyLevel->code }}
            </h2>
            <a href="{{ route('admin.hierarchy-levels.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($hierarchyLevel->trashed())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce niveau a été supprimé le ') . $hierarchyLevel->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.hierarchy-levels.restore', $hierarchyLevel->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.hierarchy-levels.update', $hierarchyLevel) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="code" :value="__('Code KOSI')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="code" name="code" type="text" class="form-control mt-1" 
                                :value="old('code', $hierarchyLevel->code)" placeholder="{{ __('Ex: N0, N1, N2...') }}" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            <small class="text-muted">{{ __('Code unique du niveau (N0 à N6)') }}</small>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="name" :value="__('Nom')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name', $hierarchyLevel->name)" placeholder="{{ __('Ex: Employé / Opérationnel') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <x-input-label for="rank" :value="__('Rang')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="rank" name="rank" type="number" class="form-control mt-1" 
                                :value="old('rank', $hierarchyLevel->rank)" placeholder="{{ __('Ex: 0, 1, 2...') }}" required min="0" />
                            <x-input-error :messages="$errors->get('rank')" class="mt-2" />
                            <small class="text-muted">{{ __('Ordre hiérarchique (0 = plus bas)') }}</small>
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="is_managerial" :value="__('Rôle')" />
                            <div class="mt-2">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="is_managerial" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="is_managerial" name="is_managerial" value="1"
                                           {{ old('is_managerial', $hierarchyLevel->is_managerial) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_managerial">
                                        <span id="managerial_label">{{ old('is_managerial', $hierarchyLevel->is_managerial) ? 'Managerial' : 'Non managerial' }}</span>
                                    </label>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('is_managerial')" class="mt-2" />
                            <small class="text-muted">{{ __('Managerial = encadrement / management') }}</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Exemples de niveaux KOSI :</strong>
                                <ul class="mb-0 mt-1">
                                    <li><strong>N0</strong> - Employé / Opérationnel (Agent, ouvrier, opérateur)</li>
                                    <li><strong>N1</strong> - Référent / Senior / Chef d'équipe (Team Leader)</li>
                                    <li><strong>N2</strong> - Superviseur (Encadrement de proximité)</li>
                                    <li><strong>N3</strong> - Responsable de service</li>
                                    <li><strong>N4</strong> - Manager / Chef de département</li>
                                    <li><strong>N5</strong> - Directeur</li>
                                    <li><strong>N6</strong> - Directeur général</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <x-primary-button class="btn btn-primary">
                            <i class="bi bi-save"></i> {{ __('Mettre à jour') }}
                        </x-primary-button>
                        <a href="{{ route('admin.hierarchy-levels.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('is_managerial').addEventListener('change', function() {
            document.getElementById('managerial_label').textContent = this.checked ? 'Managerial' : 'Non managerial';
        });
    </script>
    @endpush
</x-app-layout>