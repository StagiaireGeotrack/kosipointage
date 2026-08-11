<x-app-layout>
<div class="container">
    <h2>Modifier Poste</h2>
    <form method="POST" action="{{ route('job-titles.update', $jobTitle) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nom *</label>
            <input type="text" name="name" class="form-control" value="{{ $jobTitle->name }}" required>
        </div>
        <div class="mb-3">
            <label>Code</label>
            <input type="text" name="code" class="form-control" value="{{ $jobTitle->code }}">
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('job-titles.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</x-app-layout>