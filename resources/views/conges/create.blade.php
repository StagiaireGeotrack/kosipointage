{{-- resources/views/conges/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouveau Congé') }}
            </h2>
            <a href="{{ route('conges.index') }}" class="btn btn-secondary">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('conges.store') }}">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="employee_id" :value="__('Employé')" />
                            <span class="text-danger">*</span>
                            <select id="employee_id" name="employee_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un employé') }}</option>
                                @foreach($employes as $employe)
                                    <option value="{{ $employe->ID }}" {{ old('employee_id') == $employe->ID ? 'selected' : '' }}>
                                        {{ $employe->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="type_conge" :value="__('Type de congé')" />
                            <span class="text-danger">*</span>
                            <select id="type_conge" name="type_conge" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                @foreach($typesConge as $type)
                                    <option value="{{ $type }}" {{ old('type_conge') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type_conge')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="date_debut" :value="__('Date et heure de début')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="date_debut" name="date_debut" type="datetime-local" class="form-control mt-1" :value="old('date_debut')" required />
                            <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="date_fin" :value="__('Date et heure de fin')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="date_fin" name="date_fin" type="datetime-local" class="form-control mt-1" :value="old('date_fin')" required />
                            <x-input-error :messages="$errors->get('date_fin')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="commentaire" :value="__('Commentaire')" />
                        <textarea id="commentaire" name="commentaire" class="form-control mt-1" rows="4" placeholder="{{ __('Commentaire optionnel') }}">{{ old('commentaire') }}</textarea>
                        <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
                    </div>

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Créer') }}
                        </x-primary-button>
                        <a href="{{ route('conges.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>