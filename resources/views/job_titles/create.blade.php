<x-app-layout>
<div class="container">
    <h2>Nouveau Poste</h2>
    <form method="POST" action="{{ route('job-titles.store') }}">
        @csrf
        <div class="mb-3">
            <label>Nom *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('job-titles.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</x-app-layout>