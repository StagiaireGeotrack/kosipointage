{{-- resources/views/job_titles/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-person-badge"></i> {{ __('Détails du Poste') }} : {{ $jobTitle->name }}
            </h2>
            <div>
                <a href="{{ route('admin.job-titles.edit', $jobTitle) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> {{ __('Modifier') }}
                </a>
                <a href="{{ route('admin.job-titles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="p-2">
        <div class="card shadow-sm">
            <div class="card-body">
                @if($jobTitle->trashed())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ __('Ce poste a été supprimé le ') . $jobTitle->deleted_at->format('d/m/Y H:i') }}
                        <form action="{{ route('admin.job-titles.restore', $jobTitle->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success ms-2">
                                {{ __('Restaurer') }}
                            </button>
                        </form>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4 fw-bold">{{ __('Nom') }}</dt>
                            <dd class="col-sm-8"><strong>{{ $jobTitle->name }}</strong></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Code') }}</dt>
                            <dd class="col-sm-8"><span class="badge bg-secondary">{{ $jobTitle->code ?? '-' }}</span></dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Niveau KOSI') }}</dt>
                            <dd class="col-sm-8">
                                @if($jobTitle->hierarchyLevel)
                                    <span class="badge bg-primary">{{ $jobTitle->hierarchyLevel->code }}</span>
                                    <span class="text-muted">{{ $jobTitle->hierarchyLevel->name }}</span>
                                @else
                                    <span class="text-muted">Non défini</span>
                                @endif
                            </dd>

                            {{-- NOUVEAU : Service --}}
                            <dt class="col-sm-4 fw-bold">{{ __('Service') }}</dt>
                            <dd class="col-sm-8">
                                @if($jobTitle->department)
                                    <span class="badge bg-info">{{ $jobTitle->department->name }}</span>
                                    <small class="text-muted">({{ $jobTitle->department->site?->Nom ?? 'Site inconnu' }})</small>
                                @else
                                    <span class="text-muted">{{ __('Aucun service') }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Employés') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-info">{{ $jobTitle->employes->count() }}</span>
                            </dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Créé le') }}</dt>
                            <dd class="col-sm-8">{{ $jobTitle->created_at ? $jobTitle->created_at->format('d/m/Y H:i') : '-' }}</dd>

                            <dt class="col-sm-4 fw-bold">{{ __('Modifié le') }}</dt>
                            <dd class="col-sm-8">{{ $jobTitle->updated_at ? $jobTitle->updated_at->format('d/m/Y H:i') : '-' }}</dd>

                            @if($jobTitle->deleted_at)
                                <dt class="col-sm-4 fw-bold">{{ __('Supprimé le') }}</dt>
                                <dd class="col-sm-8">{{ $jobTitle->deleted_at->format('d/m/Y H:i') }}</dd>
                            @endif
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light h-100">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-people"></i> {{ __('Employés avec ce poste') }}</h6>
                                @php
                                    $employees = $jobTitle->employes()->take(10)->get();
                                @endphp
                                @if($employees->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($employees as $employee)
                                            <li><i class="bi bi-person"></i> {{ $employee->Nom }}</li>
                                        @endforeach
                                        @if($jobTitle->employes->count() > 10)
                                            <li class="text-muted"><i class="bi bi-plus-circle"></i> {{ $jobTitle->employes->count() - 10 }} {{ __('autres') }}...</li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted">{{ __('Aucun employé avec ce poste') }}</p>
                                @endif

                                <hr>

                                <h6 class="card-title"><i class="bi bi-info-circle"></i> {{ __('Description du niveau KOSI') }}</h6>
                                @if($jobTitle->hierarchyLevel)
                                    <p class="card-text">
                                        @switch($jobTitle->hierarchyLevel->code)
                                            @case('N0')
                                                <strong>Exécution / Opérationnel</strong><br>
                                                Agent, ouvrier, opérateur, formateur, vendeur, chauffeur, serveur
                                                @break
                                            @case('N1')
                                                <strong>Référent / Senior / Chef d\'équipe</strong><br>
                                                Team Leader, chef d\'équipe, agent senior, référent
                                                @break
                                            @case('N2')
                                                <strong>Superviseur / Encadrement de proximité</strong><br>
                                                Superviseur, chef de chantier, responsable rayon
                                                @break
                                            @case('N3')
                                                <strong>Responsable de service</strong><br>
                                                Responsable RH, responsable production, responsable logistique
                                                @break
                                            @case('N4')
                                                <strong>Manager / Chef de département</strong><br>
                                                Manager, chef de département, responsable régional
                                                @break
                                            @case('N5')
                                                <strong>Directeur</strong><br>
                                                Directeur technique, directeur commercial, directeur usine
                                                @break
                                            @case('N6')
                                                <strong>Directeur général / Direction</strong><br>
                                                Directeur général, direction générale
                                                @break
                                            @default
                                                {{ __('Non défini') }}
                                        @endswitch
                                    </p>
                                @else
                                    <p class="text-muted">{{ __('Aucun niveau KOSI associé') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$jobTitle->trashed())
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-titles.edit', $jobTitle) }}" class="btn btn-warning">
                                {{ __('Modifier') }}
                            </a>
                            @if($jobTitle->employes->count() == 0)
                                <button type="button" class="btn btn-danger" 
                                        onclick="if(confirm('Voulez-vous vraiment supprimer ce poste ?')) {
                                            document.getElementById('delete-form').submit();
                                        }">
                                    {{ __('Supprimer') }}
                                </button>
                                <form id="delete-form" action="{{ route('admin.job-titles.destroy', $jobTitle) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <span class="text-muted" title="Impossible de supprimer : {{ $jobTitle->employes->count() }} employé(s) ont ce poste">
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="bi bi-lock"></i> {{ __('Supprimer') }}
                                    </button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>