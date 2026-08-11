<x-app-layout>
<div class="container">
    <h2>Modifier Niveau</h2>
    <form method="POST" action="{{ route('hierarchy-levels.update', $hierarchyLevel) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nom *</label>
            <input type="text" name="name" class="form-control" value="{{ $hierarchyLevel->name }}" required>
        </div>
        <div class="mb-3">
            <label>Rang *</label>
            <input type="number" name="rank" class="form-control" value="{{ $hierarchyLevel->rank }}" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_managerial" value="1" class="form-check-input" id="is_mgr" {{ $hierarchyLevel->is_managerial ? 'checked' : '' }}>
            <label class="form-check-label" for="is_mgr">Est un poste de management</label>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('hierarchy-levels.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</x-app-layout>