{{-- resources/views/conges/company_holidays/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Détails du Jour Férié') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.company-holidays.edit', $companyHoliday->id) }}" class="btn btn-warning">
                    {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.company-holidays.index') }}" class="btn btn-secondary">
                    {{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @php
                    $isSuperAdmin = auth()->user()->IsSuperAdmin ?? false;
                    $isGlobal = $companyHoliday->isGlobal();
                    $hasOverride = isset($resolved) && isset($resolved->is_overridden) && $resolved->is_overridden;
                @endphp

                @if($isGlobal && !$hasOverride)
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Ce jour férié est global et s\'applique à tous les sièges.') }}
                        @if($companyHoliday->is_customizable)
                            <span class="badge bg-primary ms-2">Personnalisable</span>
                        @endif
                    </div>
                @endif

                @if($hasOverride)
                    <div class="alert alert-warning">
                        <i class="bi bi-pencil-square"></i>
                        {{ __('Ce jour férié est personnalisé pour votre siège.') }}
                    </div>
                @endif

                @if($companyHoliday->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce jour férié a été supprimé le ') . $companyHoliday->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.company-holidays.restore', $companyHoliday->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                <dl class="row">
                    <dt class="col-sm-3">{{ __('Date') }}</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $companyHoliday->date->format('d/m/Y') }}</strong>
                        <span class="text-muted ms-2">({{ $companyHoliday->date->translatedFormat('l') }})</span>
                        @if($hasOverride && isset($resolved->date) && $resolved->date != $companyHoliday->date)
                            <br><small class="text-muted">Valeur globale : {{ $companyHoliday->date->format('d/m/Y') }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Nom') }}</dt>
                    <dd class="col-sm-9">
                        <strong>{{ $companyHoliday->name }}</strong>
                        @if($isGlobal)
                            <span class="badge bg-info ms-2">Global</span>
                        @endif
                        @if($hasOverride)
                            <span class="badge bg-warning text-dark ms-2">Personnalisé</span>
                        @endif
                        @if($hasOverride && isset($resolved->name) && $resolved->name != $companyHoliday->name)
                            <br><small class="text-muted">Valeur globale : {{ $companyHoliday->name }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Siège') }}</dt>
                    <dd class="col-sm-9">
                        @if($isGlobal)
                            <span class="text-muted">Tous les sièges</span>
                        @else
                            {{ $companyHoliday->site->Nom ?? 'N/A' }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Récurrent') }}</dt>
                    <dd class="col-sm-9">
                        @if($companyHoliday->is_recurring)
                            <span class="badge bg-success">
                                <i class="bi bi-arrow-repeat"></i> Oui, tous les ans
                            </span>
                        @else
                            <span class="badge bg-secondary">Non</span>
                        @endif
                        @if($hasOverride && isset($resolved->is_recurring) && $resolved->is_recurring != $companyHoliday->is_recurring)
                            <br><small class="text-muted">Valeur globale : {{ $companyHoliday->is_recurring ? 'Oui' : 'Non' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Statut') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $companyHoliday->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $companyHoliday->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                        @if($hasOverride && isset($resolved->is_active) && $resolved->is_active != $companyHoliday->is_active)
                            <br><small class="text-muted">Valeur globale : {{ $companyHoliday->is_active ? 'Actif' : 'Inactif' }}</small>
                        @endif
                    </dd>

                    <dt class="col-sm-3">{{ __('Personnalisable') }}</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $companyHoliday->is_customizable ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $companyHoliday->is_customizable ? 'Oui' : 'Non' }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">{{ __('Créé le') }}</dt>
                    <dd class="col-sm-9">{{ $companyHoliday->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-3">{{ __('Dernière mise à jour') }}</dt>
                    <dd class="col-sm-9">{{ $companyHoliday->updated_at->format('d/m/Y H:i') }}</dd>

                    @if($companyHoliday->deleted_at)
                        <dt class="col-sm-3">{{ __('Supprimé le') }}</dt>
                        <dd class="col-sm-9">{{ $companyHoliday->deleted_at->format('d/m/Y H:i') }}</dd>
                    @endif

                    @if($hasOverride && isset($resolved->override_id))
                        <dt class="col-sm-3">{{ __('Override ID') }}</dt>
                        <dd class="col-sm-9">{{ $resolved->override_id }}</dd>
                    @endif
                </dl>

                @if(!$companyHoliday->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.company-holidays.edit', $companyHoliday->id) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if(!$isGlobal || $isSuperAdmin)
                                <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Voulez-vous vraiment supprimer ce jour férié ?')) { 
                                        document.getElementById('delete-form').submit(); 
                                    }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.company-holidays.destroy', $companyHoliday->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>