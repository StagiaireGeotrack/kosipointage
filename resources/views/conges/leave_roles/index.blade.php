<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-tags"></i> Gestion des rôles de validation
            </h2>
            <a href="{{ route('admin.leave-roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouveau rôle
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Libellé</th>
                                <th>Site</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td><code>{{ $role->name }}</code></td>
                                    <td>{{ $role->label }}</td>
                                    <td>
                                        @if($role->site_id)
                                            {{ $role->site->Nom ?? 'Site inconnu' }}
                                        @else
                                            <span class="badge bg-info">Global</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}">
                                            {{ $role->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.leave-roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.leave-roles.destroy', $role) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce rôle ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun rôle défini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>