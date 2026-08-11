<x-app-layout>
<div class="container">
    <h2>Postes</h2>
    <a href="{{ route('job-titles.create') }}" class="btn btn-primary mb-3">+ Nouveau poste</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Code</th>
                <th>Employés</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jobTitles as $jt)
            <tr>
                <td>{{ $jt->name }}</td>
                <td>{{ $jt->code }}</td>
                <td>{{ $jt->employes->count() }}</td>
                <td>
                    <a href="{{ route('job-titles.edit', $jt) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('job-titles.destroy', $jt) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $jobTitles->links() }}
</div>
</x-app-layout>