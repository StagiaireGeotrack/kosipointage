{{-- resources/views/departments/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Modifier le Service') }} : {{ $department->name }}
            </h2>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('departments.update', $department) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="site_id" :value="__('Siège')" />
                            <span class="text-danger">*</span>
                            <select id="site_id" name="site_id" class="form-select mt-1" required>
                                <option value="">{{ __('Sélectionnez un siège') }}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->ID }}" {{ old('site_id', $department->site_id) == $site->ID ? 'selected' : '' }}>
                                        {{ $site->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="name" :value="__('Nom du service')" />
                            <span class="text-danger">*</span>
                            <x-text-input id="name" name="name" type="text" class="form-control mt-1" 
                                :value="old('name', $department->name)" placeholder="{{ __('Ex: Service Informatique') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-6">
                            <x-input-label for="code" :value="__('Code')" />
                            <x-text-input id="code" name="code" type="text" class="form-control mt-1" 
                                :value="old('code', $department->code)" placeholder="{{ __('Ex: SI, RH, COM') }}" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            <small class="text-muted">{{ __('Code court pour identifier le service') }}</small>
                        </div>

                        <div class="col-lg-6">
                            <x-input-label for="manager_employee_id" :value="__('Responsable')" />
                            <select id="manager_employee_id" name="manager_employee_id" class="form-select mt-1">
                                <option value="">{{ __('Aucun responsable') }}</option>
                                @foreach($employes as $emp)
                                    <option value="{{ $emp->ID }}" {{ old('manager_employee_id', $department->manager_employee_id) == $emp->ID ? 'selected' : '' }}>
                                        {{ $emp->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('manager_employee_id')" class="mt-2" />
                            <small class="text-muted">{{ __('Responsable du service (optionnel)') }}</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <x-primary-button class="btn btn-primary">
                            {{ __('Mettre à jour') }}
                        </x-primary-button>
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            {{ __('Annuler') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('site_id');
            const mgrSelect = document.getElementById('manager_employee_id');
            const currentManagerId = '{{ old('manager_employee_id', $department->manager_employee_id) }}';

            if (!siteSelect || !mgrSelect) return;

            siteSelect.addEventListener('change', function() {
                const siteId = this.value;
                if (!siteId) {
                    mgrSelect.innerHTML = '<option value="">{{ __("Aucun responsable") }}</option>';
                    return;
                }

                fetch(`/api/managers-by-site/${siteId}`)
                    .then(r => r.json())
                    .then(data => {
                        mgrSelect.innerHTML = '<option value="">{{ __("Aucun responsable") }}</option>';
                        data.forEach(e => {
                            const selected = (e.id == currentManagerId) ? 'selected' : '';
                            mgrSelect.innerHTML += `<option value="${e.id}" ${selected}>${e.name}</option>`;
                        });
                    })
                    .catch(() => {
                        mgrSelect.innerHTML = '<option value="">{{ __("Erreur chargement") }}</option>';
                    });
            });
        });
    </script>
    @endpush
</x-app-layout>