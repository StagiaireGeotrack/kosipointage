<x-employe-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Tableau de bord Employé') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">{{ __('Bienvenue,') }} {{ auth()->guard('employe')->user()->Nom }}!</h5>
                    <p class="text-muted">{{ __('Bienvenue sur votre portail personnel. Utilisez le menu en haut à droite pour gérer votre profil.') }}</p>
                    <p class="text-muted mb-0">{{ __('Les prochaines fonctionnalités (Pointages, Rapports, Congés) seront bientôt disponibles.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-employe-layout>
