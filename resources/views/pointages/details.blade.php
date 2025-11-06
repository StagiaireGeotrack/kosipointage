{{-- resources/views/pointages/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails pointages: ' . $date->format('d/m/Y') . ' - Employé(e): ' . $employe->Nom ) }}
            </h6>
            <a href="{{ route('pointages.create') }}" class="btn btn-primary">
                {{ __('Nouveau pointage') }}
            </a>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                
                <!-- Tableau des pointages -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Employé') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Date et Heure') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Type') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Méthode') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Siège') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Face image') }}
                                </th>
                                <th class="text-uppercase small fw-semibold text-secondary">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pointages as $pointage)
                                <tr>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            @if ($pointage->employe->HasFaceSetup)
                                                <img src="{{ route('employes.face.thumbnail', $pointage->employe->ID) }}" alt="{{ $pointage->employe->Nom }}" class="rounded-circle me-2" style="height: 32px; width: 32px; object-fit: cover;">
                                            @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" style="height: 32px; width: 32px;">
                                                <span class="small fw-semibold text-white">{{ substr($pointage->employe->Nom, 0, 1) }}</span>
                                            </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">{{ $pointage->employe->Nom }}</div>
                                                <small class="text-muted">{{ $pointage->employe->BadgeID }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        {{ $pointage->timestamp_->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($pointage->type_ == 'entry')
                                            <span class="badge bg-success">
                                                {{ __('Entrée') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                {{ __('Sortie') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @switch($pointage->auth_method)
                                            @case('rfid')
                                                <span class="badge bg-info">
                                                    {{ __('Badge') }}
                                                </span>
                                                @break
                                            @case('face')
                                                <span class="badge bg-primary">
                                                    {{ __('Face image') }}
                                                </span>
                                                @break
                                            @case('pin')
                                                <span class="badge bg-warning text-dark">
                                                    {{ __('PIN') }}
                                                </span>
                                                @break
                                            @case('admin')
                                                <span class="badge bg-secondary">
                                                    {{ __('Administrateur') }}
                                                </span>
                                                @break
                                            @default
                                                {{ $pointage->auth_method }}
                                        @endswitch
                                    </td>
                                    <td class="align-middle">
                                        {{ $pointage->siege->Nom }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($pointage->photo_path)
                                            <a href="{{ route('pointages.photo', $pointage->ID) }}" target="_blank">
                                                <img src="{{ route('pointages.photo.thumbnail', $pointage->ID) }}" alt="{{ __('Face image') }}" class="rounded-circle" style="height: 40px; width: 40px; object-fit: cover;">
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('Aucune image') }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('pointages.show', $pointage->ID) }}" class="text-primary" title="Voir">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('pointages.edit', $pointage->ID) }}" class="text-warning" title="Modifier">
                                                <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('pointages.destroy', $pointage->ID) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('{{ __('Voulez-vous vraiment supprimer ce pointage ?') }}')" title="Supprimer">
                                                    <svg class="bi" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        {{ __('Aucun pointage pour le moment') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm m-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>                
            </div>
        </div>
    </div>
</x-app-layout>