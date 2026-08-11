<x-app-layout>
<div class="container">
    <h2>Nouveau Niveau Hiérarchique</h2>
    <form method="POST" action="{{ route('hierarchy-levels.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nom *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rang * (1 = bas, 10 = haut)</label>
            <input type="number" name="rank" class="form-control" min="1" max="100" value="5" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_managerial" value="1" class="form-check-input" id="is_mgr">
            <label class="form-check-label" for="is_mgr">Est un poste de management</label>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('hierarchy-levels.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</x-app-layout>