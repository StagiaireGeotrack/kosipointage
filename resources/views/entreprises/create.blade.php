@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Créer une entreprise') }}</h5>
                    <a href="{{ route('entreprises.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> {{ __('Retour') }}
                    </a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('entreprises.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="Nom">{{ __('Nom') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('Nom') is-invalid @enderror" id="Nom" name="Nom" value="{{ old('Nom') }}" required>
                            @error('Nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="SiegeID">{{ __('Siège') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('SiegeID') is-invalid @enderror" id="SiegeID" name="SiegeID" required>
                                <option value="">{{ __('Sélectionner un siège') }}</option>
                                @foreach($sieges as $siege)
                                    <option value="{{ $siege->ID }}" {{ old('SiegeID') == $siege->ID ? 'selected' : '' }}>
                                        {{ $siege->Nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('SiegeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Email">{{ __('Email') }}</label>
                            <input type="email" class="form-control @error('Email') is-invalid @enderror" id="Email" name="Email" value="{{ old('Email') }}">
                            @error('Email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Telephone">{{ __('Téléphone') }}</label>
                            <input type="text" class="form-control @error('Telephone') is-invalid @enderror" id="Telephone" name="Telephone" value="{{ old('Telephone') }}">
                            @error('Telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Adresse">{{ __('Adresse') }}</label>
                            <textarea class="form-control @error('Adresse') is-invalid @enderror" id="Adresse" name="Adresse" rows="3">{{ old('Adresse') }}</textarea>
                            @error('Adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="Logo">{{ __('Logo') }}</label>
                            <input type="file" class="form-control @error('Logo') is-invalid @enderror" id="Logo" name="Logo">
                            <small class="form-text text-muted">{{ __('Formats acceptés : JPG, PNG. Max : 2Mo') }}</small>
                            @error('Logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('Actived') is-invalid @enderror" type="checkbox" value="1" id="Actived" name="Actived" {{ old('Actived', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="Actived">{{ __('Actif') }}</label>
                                @error('Actived')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection