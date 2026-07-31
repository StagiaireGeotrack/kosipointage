<x-app-layout>
    <x-slot name="header">
        <h5 class="font-semibold text-xl text-gray-800 leading-tight">
            Catalogue des types de congés
        </h5>
    </x-slot>

    <div class="py-12 conges-admin">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="ca-flash-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="ca-flash-error">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="ca-page-header">
                        <div>
                            <h3 class="ca-page-title"></h3>
                        </div>
                        <a href="{{ route('leave-types.create') }}" class="ca-btn ca-btn-primary">
                            Nouveau type
                        </a>
                    </div>

                    <div class="ca-table-wrap">
                        <table class="ca-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Couleur</th>
                                    <th>Statut</th>
                                    <th class="ca-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveTypes as $type)
                                <tr>
                                    <td><span class="ca-code">{{ $type->code }}</span></td>
                                    <td style="font-weight: 500; color: #0f172a;">{{ $type->name }}</td>
                                    <td class="ca-desc">{{ $type->description ?? '-' }}</td>
                                    <td>
                                        @if($type->color)
                                            <span class="ca-color-dot" style="background-color: {{ $type->color }}"></span>
                                            <span class="ca-color-code">{{ $type->color }}</span>
                                        @else
                                            <span style="color: #94a3b8; font-size: 0.8125rem;">Non définie</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($type->is_active)
                                            <span class="ca-badge ca-badge-green">Actif</span>
                                        @else
                                            <span class="ca-badge ca-badge-gray">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="ca-actions">
                                        <a href="{{ route('leave-types.edit', $type) }}" style="color: #4f46e5; font-size: 0.8125rem; font-weight: 500; text-decoration: none; margin-right: 1rem;">Modifier</a>
                                        <form action="{{ route('leave-types.destroy', $type) }}" method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de ce type global ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ca-btn-delete">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="ca-empty">
                                        Aucun type de congé dans le catalogue.
                                        <a href="{{ route('leave-types.create') }}">Creer le premier type</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="ca-pagination">
                        {{ $leaveTypes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>