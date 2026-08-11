<x-app-layout>
<div class="container">
    <h2>Niveaux Hiérarchiques</h2>
    <a href="{{ route('hierarchy-levels.create') }}" class="btn btn-primary mb-3">+ Nouveau niveau</a>

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
                <th>Rang</th>
                <th>Managerial</th>
                <th>Employés</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($levels as $lvl)
            <tr>
                <td>{{ $lvl->name }}</td>
                <td>{{ $lvl->rank }}</td>
                <td>{!! $lvl->is_managerial ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td>
                <td>{{ $lvl->employes->count() }}</td>
                <td>
                    <a href="{{ route('hierarchy-levels.edit', $lvl) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('hierarchy-levels.destroy', $lvl) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $levels->links() }}
</div>
</x-app-layout>