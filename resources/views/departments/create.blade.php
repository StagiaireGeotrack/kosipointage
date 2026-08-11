<x-app-layout>
<div class="container">
    <h2>Nouveau Service</h2>

    <form method="POST" action="{{ route('departments.store') }}">
        @csrf
        
        <div class="mb-3">
            <label>Siège *</label>
            <select name="site_id" id="site_id" class="form-select" required>
                <option value="">Choisir...</option>
                @foreach($sites as $site)
                    <option value="{{ $site->ID }}">{{ $site->Nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nom du service *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" placeholder="ex: RH, TECH, COM...">
        </div>

        <div class="mb-3">
            <label>Responsable (optionnel)</label>
            <select name="manager_employee_id" id="manager_employee_id" class="form-select">
                <option value="">-- Aucun --</option>
                @foreach($employes as $emp)
                    <option value="{{ $emp->ID }}">{{ $emp->Nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const siteSelect = document.getElementById('site_id');
        const mgrSelect = document.getElementById('manager_employee_id');

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
                        mgrSelect.innerHTML += `<option value="${e.id}">${e.name}</option>`;
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