{{-- resources/views/supervisors/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détail du responsable de service') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('supervisors.edit', $supervisor->ID) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('supervisors.index') }}" class="btn btn-secondary">
                    {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">

                <dl class="row mb-0">
                    <dt class="col-sm-3">{{ __('Identifiant / E-mail') }}</dt>
                    <dd class="col-sm-9">{{ $supervisor->Identifiant_email }}</dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">{{ $supervisor->siege?->Nom ?? '-' }}</dd>

                    <dt class="col-sm-3">{{ __('Rôle') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge" style="background: linear-gradient(135deg, #F59E0B, #D97706);">
                            {{ __('Responsable de service') }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        @if ($supervisor->Actived && !$supervisor->deleted)
                            <span class="badge bg-success">{{ __('Activé') }}</span>
                        @elseif ($supervisor->deleted)
                            <span class="badge bg-danger">{{ __('Supprimé') }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ __('Désactivé') }}</span>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Date de création') }}</dt>
                    <dd class="col-sm-9">
                        @if($supervisor->created_at)
                            <x-local-date-time :datetime="$supervisor->created_at"/>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Services affectés') }}</dt>
                    <dd class="col-sm-9">
                        @if($supervisor->supervisorServices && $supervisor->supervisorServices->count() > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($supervisor->supervisorServices as $service)
                                    <li>
                                        <i class="bi bi-check-circle text-success"></i>
                                        <strong>{{ $service->name }}</strong>
                                        @if($service->code)
                                            <small class="text-muted">({{ $service->code }})</small>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted fst-italic">{{ __('Aucun service affecté — ce compte ne voit aucun employé.') }}</span>
                        @endif
                    </dd>
                </dl>

            </div>
        </div>
    </div>
</x-app-layout>