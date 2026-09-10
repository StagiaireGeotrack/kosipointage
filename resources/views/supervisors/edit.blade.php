@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-pencil-square me-2 text-primary"></i>
            {{ __('Modifier un responsable de service') }}
        </h1>
        <a href="{{ route('supervisors.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Retour') }}
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('supervisors.update', $supervisor->ID) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Email --}}
                <div class="mb-3">
                    <label for="Identifiant_email" class="form-label">
                        {{ __('Email') }} <span class="text-danger">*</span>
                    </label>
                    <input type="email"
                           name="Identifiant_email"
                           id="Identifiant_email"
                           class="form-control @error('Identifiant_email') is-invalid @enderror"
                           value="{{ old('Identifiant_email', $supervisor->Identifiant_email) }}"
                           required>
                    @error('Identifiant_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mot de passe --}}
                <div class="mb-3">
                    <label for="password" class="form-label">
                        {{ __('Nouveau mot de passe') }}
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror">
                    <small class="text-muted">
                        {{ __('Laisser vide pour conserver le mot de passe actuel.') }}
                    </small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Services --}}
                <div class="mb-3">
                    <label class="form-label">
                        {{ __('Services supervisés') }} <span class="text-danger">*</span>
                    </label>
                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                        @foreach($services as $srv)
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="services[]"
                                       value="{{ $srv->id }}"
                                       id="srv_{{ $srv->id }}"
                                       {{ in_array($srv->id, old('services', $serviceIds)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="srv_{{ $srv->id }}">
                                    {{ $srv->name }}
                                    @if($srv->code)
                                        <span class="text-muted small">({{ $srv->code }})</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('services')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('supervisors.index') }}" class="btn btn-outline-secondary">
                        {{ __('Annuler') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> {{ __('Enregistrer') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection