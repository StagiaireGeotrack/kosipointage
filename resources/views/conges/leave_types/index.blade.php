<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catalogue des Types de Congés
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="flash-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="flash-error">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="page-header">
                        <h3 class="text-lg font-medium text-gray-900">Types de congés globaux</h3>
                        <a href="{{ route('leave-types.create') }}" class="btn-primary">
                            + Nouveau Type Global
                        </a>
                    </div>

                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Couleur</th>
                                    <th>Statut</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveTypes as $type)
                                <tr>
                                    <td class="code-cell">{{ $type->code }}</td>
                                    <td><strong>{{ $type->name }}</strong></td>
                                    <td class="description-cell">{{ $type->description ?? '-' }}</td>
                                    <td>
                                        @if($type->color)
                                            <span class="color-dot" style="background-color: {{ $type->color }}"></span>
                                            <span class="color-code">{{ $type->color }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->is_active)
                                            <span class="badge badge-active">Actif</span>
                                        @else
                                            <span class="badge badge-inactive">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="actions">
                                        <a href="{{ route('leave-types.edit', $type) }}" class="btn-link">Modifier</a>
                                        <form action="{{ route('leave-types.destroy', $type) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce type global ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        Aucun type de congé dans le catalogue.
                                        <a href="{{ route('leave-types.create') }}">En créer un</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper">
                        {{ $leaveTypes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>