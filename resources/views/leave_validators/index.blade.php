{{-- resources/views/leave_validators/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-people"></i> Gestion des validateurs de congés
            </h2>
            <a href="{{ route('admin.leave-validators.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter un validateur
            </a>
        </div>
    </x-slot>

    <div class="p-3">
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Rôle</th>
                                <th>Site</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($validators as $validator)
                                <tr>
                                    <td>{{ $validator->employee->Nom ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $validator->role }}</span>
                                    </td>
                                    <td>{{ $validator->site->Nom ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $validator->is_active ? 'success' : 'danger' }}">
                                            {{ $validator->is_active ? 'Oui' : 'Non' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.leave-validators.edit', $validator) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.leave-validators.destroy', $validator) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce validateur ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun validateur défini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $validators->links() }}
            </div>
        </div>
    </div>
</x-app-layout>