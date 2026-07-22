<x-employe-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Nouvelle Demande de Congé') }}
            </h2>
            <a href="{{ route('employe.conges.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('employe.conges.store') }}">
                        @csrf

                        <div class="row">
                            <!-- Date de début -->
                            <div class="col-md-6 mb-4">
                                <label for="date_heure_debut" class="form-label fw-semibold">{{ __('Date et heure de début') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('date_heure_debut') is-invalid @enderror" id="date_heure_debut" name="date_heure_debut" value="{{ old('date_heure_debut') }}" required>
                                <x-input-error :messages="$errors->get('date_heure_debut')" class="mt-2" />
                            </div>

                            <!-- Date de fin -->
                            <div class="col-md-6 mb-4">
                                <label for="date_heure_fin" class="form-label fw-semibold">{{ __('Date et heure de fin') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('date_heure_fin') is-invalid @enderror" id="date_heure_fin" name="date_heure_fin" value="{{ old('date_heure_fin') }}" required>
                                <x-input-error :messages="$errors->get('date_heure_fin')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Motif -->
                        <div class="form-group mb-4">
                            <label for="raison" class="form-label fw-semibold">{{ __('Motif de l\'absence') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('raison') is-invalid @enderror" id="raison" name="raison" rows="4" required placeholder="Veuillez préciser la raison de votre demande de congé...">{{ old('raison') }}</textarea>
                            <x-input-error :messages="$errors->get('raison')" class="mt-2" />
                        </div>

                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                {{ __('Une fois soumise, votre demande sera en statut "En cours". Vous pourrez l\'annuler tant qu\'elle n\'est pas validée ou refusée par l\'administration.') }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-send me-2"></i> {{ __('Soumettre la demande') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-employe-layout>
