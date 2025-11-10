{{-- resources/views/admin/sellers/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Modifier le Vendeur</h2>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sellers.update', $seller->ID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $seller->Identifiant_email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nouveau mot de passe
                            </label>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password"
                                minlength="6"
                                placeholder="Laisser vide pour ne pas modifier"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Laisser vide pour conserver le mot de passe actuel
                            </small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="actived" 
                            name="actived"
                            value="1"
                            {{ old('actived', $seller->Actived) ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="actived">
                            Compte actif
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Sièges accessibles <span class="text-danger">*</span>
                    </label>
                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                        @if($sieges->isEmpty())
                            <p class="text-muted">Aucun siège disponible</p>
                        @else
                            @php
                                $selectedSiegeIds = old('siege_ids', $seller->sellerSieges->pluck('ID')->toArray());
                            @endphp
                            <div class="row">
                                @foreach($sieges as $siege)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-check mb-2">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="siege_ids[]" 
                                                value="{{ $siege->ID }}"
                                                id="siege_{{ $siege->ID }}"
                                                {{ in_array($siege->ID, $selectedSiegeIds) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="siege_{{ $siege->ID }}">
                                                {{ $siege->Nom }}
                                                @if($siege->Nom_Lieu_Ville)
                                                    <small class="text-muted d-block">
                                                        {{ $siege->Nom_Lieu_Ville }}
                                                    </small>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @error('siege_ids')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        Le vendeur doit avoir accès à au moins un siège
                    </small>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.sellers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.border.rounded.p-3');
        if (container && container.querySelector('.form-check-input')) {
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.className = 'btn btn-sm btn-outline-primary mb-2';
            
            const updateButtonText = () => {
                const checkboxes = container.querySelectorAll('.form-check-input');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                selectAllBtn.textContent = allChecked ? 'Tout désélectionner' : 'Tout sélectionner';
            };
            
            updateButtonText();
            
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = container.querySelectorAll('.form-check-input');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });
                
                updateButtonText();
            });
            
            container.insertBefore(selectAllBtn, container.firstChild);
        }
    });
</script>
@endpush
@endsection