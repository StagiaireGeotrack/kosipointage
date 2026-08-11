<x-app-layout>
<div class="container">
    <h2>Modifier Service : {{ $department->name }}</h2>

    <form method="POST" action="{{ route('departments.update', $department) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Siège *</label>
            <select name="site_id" id="site_id" class="form-select" required>
                @foreach($sites as $site)
                    <option value="{{ $site->ID }}" {{ $department->site_id == $site->ID ? 'selected' : '' }}>
                        {{ $site->Nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nom *</label>
            <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
        </div>

        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ $department->code }}">
        </div>

        <div class="mb-3">
            <label>Responsable</label>
            <select name="manager_employee_id" id="manager_employee_id" class="form-select">
                <option value="">-- Aucun --</option>
                @foreach($employes as $emp)
                    <option value="{{ $emp->ID }}" {{ $department->manager_employee_id == $emp->ID ? 'selected' : '' }}>
                        {{ $emp->Nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siteSelect = document.getElementById('site_id');
        const mgrSelect = document.getElementById('manager_employee_id');
        const currentManagerId = '{{ $department->manager_employee_id }}';

        if (!siteSelect || !mgrSelect) return;

        siteSelect.addEventListener('change', function() {
            const siteId = this.value;
            if (!siteId) {
                mgrSelect.innerHTML = '<option value="">-- Aucun --</option>';
                return;
            }

            fetch(`/api/managers-by-site/${siteId}`)
                .then(r => r.json())
                .then(data => {
                    mgrSelect.innerHTML = '<option value="">-- Aucun --</option>';
                    data.forEach(e => {
                        const selected = (currentManagerId == e.id) ? 'selected' : '';
                        mgrSelect.innerHTML += `<option value="${e.id}" ${selected}>${e.name}</option>`;
                    });
                })
                .catch(() => {
                    mgrSelect.innerHTML = '<option value="">-- Erreur chargement --</option>';
                });
        });
    });
</script>
@endpush
</x-app-layout>