@extends('layouts.app')

@section('title', 'Détail du planning')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-0">📋 {{ $planning->nom }}</h4>
        <span class="text-muted small">{{ $planning->libelle_semaine }}</span>
    </div>
    <div>
        <span class="badge bg-{{ $planning->couleur_statut }}">{{ $planning->libelle_statut }}</span>
        <a href="{{ route('planning.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <strong>Service :</strong><br>
                {{ $planning->service?->name ?? 'N/A' }}
            </div>
            <div class="col-md-3">
                <strong>Poste :</strong><br>
                {{ $planning->poste?->name ?? 'N/A' }}
            </div>
            <div class="col-md-3">
                <strong>Employés :</strong><br>
                {{ $planning->nb_employes }} employés
            </div>
            <div class="col-md-3">
                <strong>Créneaux :</strong><br>
                {{ $planning->nb_creneaux }} créneaux
            </div>
        </div>

        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Employé</th>
                        @foreach(['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'] as $jour)
                            <th>{{ $jour }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $employes = $planning->details->groupBy('employe_id');
                    @endphp
                    @foreach($employes as $employeId => $details)
                        @php
                            $employe = $details->first()->employe;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $employe->Nom }} {{ $employe->Prenom ?? '' }}</strong>
                            </td>
                            @foreach(['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'] as $jour)
                                @php
                                    $detail = $details->firstWhere('date', Carbon\Carbon::parse($planning->date_debut_semaine)->modify($jour)->format('Y-m-d'));
                                @endphp
                                <td>
                                    @if($detail)
                                        <span class="badge bg-primary">{{ $detail->heure_debut }} - {{ $detail->heure_fin }}</span>
                                        @if($detail->pause_debut && $detail->pause_fin)
                                            <br><small class="text-muted">Pause: {{ $detail->pause_debut }}-{{ $detail->pause_fin }}</small>
                                        @endif
                                        @if($detail->deuxieme_debut && $detail->deuxieme_fin)
                                            <br><small class="text-muted">2ème: {{ $detail->deuxieme_debut }}-{{ $detail->deuxieme_fin }}</small>
                                        @endif
                                        <br><span class="badge bg-{{ $detail->statut === 'planifie' ? 'info' : ($detail->statut === 'confirme' ? 'success' : 'secondary') }}">
                                            {{ $detail->libelle_statut }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($planning->commentaire)
            <div class="mt-3 p-3 bg-light rounded">
                <strong>Commentaire :</strong><br>
                {{ $planning->commentaire }}
            </div>
        @endif
    </div>
</div>
@endsection
