<x-app-layout>
<div class="container">
    <h2>Gestion des Services</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <select name="site_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tous les sièges --</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->ID }}" {{ request('site_id') == $site->ID ? 'selected' : '' }}>
                                {{ $site->Nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('departments.create') }}" class="btn btn-primary">+ Nouveau service</a>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Code</th>
                <th>Siège</th>
                <th>Responsable</th>
                <th>Employés</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
            <tr>
                <td>{{ $dept->name }}</td>
                <td>{{ $dept->code }}</td>
                <td>{{ $dept->site->Nom ?? '-' }}</td>
                <td>{{ $dept->managerEmployee->Nom ?? '-' }}</td>
                <td>{{ $dept->employes->count() }}</td>
                <td>
                    <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('departments.destroy', $dept) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $departments->links() }}
</div>
</x-app-layout>