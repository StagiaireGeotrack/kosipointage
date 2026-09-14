<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">{{ __('Master (Adjoint)') }}</h2>
            @if(!$master || $master->deleted)
                <a href="{{ route('master.create') }}" class="btn btn-primary">{{ __('Créer le Master') }}</a>
            @endif
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if(!$master)
                    <div class="alert alert-info">
                        {{ __('Aucun Master n\'est défini pour votre entreprise.') }}
                    </div>
                @else
                    <dl class="row mb-0">
                        <dt class="col-sm-3">{{ __('Email') }}</dt>
                        <dd class="col-sm-9">{{ $master->Identifiant_email }}</dd>

                        <dt class="col-sm-3">{{ __('Statut') }}</dt>
                        <dd class="col-sm-9">
                            @if($master->Actived && !$master->deleted)
                                <span class="badge bg-success">{{ __('Actif') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactif') }}</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">{{ __('Date de création') }}</dt>
                        <dd class="col-sm-9">{{ $master->created_at?->format('d/m/Y H:i') }}</dd>
                    </dl>

                    <hr>
                    <div class="d-flex gap-2">
                        @if(!$master->deleted)
                            <a href="{{ route('master.edit') }}" class="btn btn-warning">{{ __('Modifier') }}</a>
                            <form action="{{ route('master.destroy') }}" method="POST" onsubmit="return confirm('Confirmer la désactivation ?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger">{{ __('Désactiver') }}</button>
                            </form>
                        @else
                            <form action="{{ route('master.reset') }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-success">{{ __('Réactiver') }}</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
