<?php
// app/Http/Requests/AdministrationRequest.php

namespace App\Http\Requests;

use App\Models\Administration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AdministrationRequest extends FormRequest
{
    // Variable pour stocker si la checkbox était présente dans le formulaire original
    private $originalHasIsSuperAdmin = false;
    private $originalHasIsManager = false;
    private $originalHasActived = false;

    public function authorize(): bool
    {
        $user = auth()->user();
        return $user && ($user->isTrueSuperAdmin() || $user->isManagerSuperAdmin() || $user->isSimpleAdmin() || $user->isSeller());
    }

    public function rules(): array
    {
        $isSuperAdmin = $this->input('IsSuperAdmin', 0);

        // ⚠️ IMPORTANT : En mode UPDATE, vérifier si c'est déjà un super admin dans la BD
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $adminId = $this->route('administrateur');
            if ($adminId) {
                $existingAdmin = Administration::find($adminId);
                if ($existingAdmin && $existingAdmin->IsSuperAdmin == 1) {

                    // Si la checkbox n'était pas dans le formulaire, on conserve l'état super admin
                    if (!$this->originalHasIsSuperAdmin) {
                        $isSuperAdmin = 1;
                        $this->merge(['IsSuperAdmin' => 1]);
                    }
                }
            }
        }

        $rules = [
            'Identifiant_email' => 'required|email|max:255|unique:administration,Identifiant_email',
            'IsSuperAdmin' => 'nullable|boolean',
            'IsManager' => 'nullable|boolean',
            'Actived' => 'nullable|boolean',
            'IsSupervisor' => 'nullable|boolean',
            'service_ids'  => 'nullable|array',
            'service_ids.*'=> 'exists:departments,id',
        ];

        // Gestion conditionnelle du SiegeID
        if ($isSuperAdmin == 1) {
            $rules['SiegeID'] = 'nullable|exists:Entreprises_sieges,ID';
        } else {
            $rules['SiegeID'] = 'required|exists:Entreprises_sieges,ID';
        }


        // Pour la création, le mot de passe est obligatoire
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        } else {
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];

            $rules['Identifiant_email'] = 'required|email|max:255|unique:administration,Identifiant_email,'.$this->route('administrateur').',ID';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'Identifiant_email.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.email' => __('Le champ :attribute doit être un e-mail valide', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.max' => __('Le champ :attribute ne doit pas dépasser :max caractères', ['attribute' => __('Identifiant ou E-mail'), 'max' => 255]),
            'Identifiant_email.unique' => __('La valeur de :attribute est déjà utilisée', ['attribute' => __('Identifiant ou E-mail')]),

            'password.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Mot de passe')]),
            'password.confirmed' => __('Veuillez bien confirmer le champ :attribute', ['attribute' => __('Mot de passe')]),
            'password.min' => __('Le champ :attribute doit contenir au moins :min caractères', ['attribute' => __('Mot de passe'), 'min' => 8]),

            'IsSuperAdmin.boolean' => __('Le champ :attribute doit être vrai ou faux', ['attribute' => __('Super Administrateur')]),

            'SiegeID.required' => __('Le champ :attribute est obligatoire pour les administrateurs simples', ['attribute' => __('Siège')]),
            'SiegeID.exists' => __('Le siège sélectionné n\'existe pas'),

            'Actived.boolean' => __('Le champ :attribute doit être vrai ou faux', ['attribute' => __('Activé')]),
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // 🎯 STOCKER l'état ORIGINAL avant toute modification
        $this->originalHasIsSuperAdmin = $this->has('IsSuperAdmin');
        $this->originalHasIsManager = $this->has('IsManager');
        $this->originalHasActived = $this->has('Actived');

        $user = auth()->user();

        // ============================================================
        // SÉCURITÉ MANAGER SUPER ADMIN
        // ============================================================
        if ($user && $user->isManagerSuperAdmin()) {
            $this->originalHasIsSuperAdmin = false;
        }

        // ============================================================
        // SÉCURITÉ REVENDEUR (à traiter EN PREMIER)
        // Le Revendeur ne peut créer QUE des Simple Admins sur SES sièges
        // ============================================================
        if ($user && $user->isSeller()) {
            $this->originalHasIsSuperAdmin = false;
            $this->originalHasIsManager = true;

            $siegeId = (int) $this->input('SiegeID');
            $accessibleSieges = $user->getSiegeIdsAccessibles();

            // Si aucun siège fourni OU siège hors périmètre → forcer le premier accessible
            if (empty($siegeId) || !in_array($siegeId, $accessibleSieges, true)) {
                if (!empty($accessibleSieges)) {
                    $this->merge(['SiegeID' => $accessibleSieges[0]]);
                } else {
                    // Revendeur sans aucun siège → aucun admin ne peut être créé
                    // On force une valeur impossible pour faire échouer la validation `exists`
                    $this->merge(['SiegeID' => -1]);
                }
            }
        }

        // ============================================================
        // SÉCURITÉ ADMIN SIMPLE (uniquement si PAS Revendeur)
        // ============================================================
        elseif ($user && $user->isSimpleAdmin() && !$user->isManagerSimpleAdmin()) {
            $this->originalHasIsSuperAdmin = false;
            $this->originalHasIsManager = true;
            $this->merge(['SiegeID' => $user->SiegeID]);
        }

        // ============================================================
        // CONVERSION CHECKBOXES EN 1/0
        // ============================================================
        $this->merge([
            'IsSuperAdmin' => $this->originalHasIsSuperAdmin ? 1 : 0,
            'IsManager'    => $this->originalHasIsManager    ? 1 : 0,
            'IsSupervisor' => $this->has('IsSupervisor')     ? 1 : 0,
            'Actived'      => $this->originalHasActived      ? 1 : 0,
        ]);

        // ============================================================
        // CONVERSION SiegeID vide en null
        // ⚠️ UNIQUEMENT si l'utilisateur N'EST PAS Revendeur
        // (un Revendeur DOIT toujours avoir un SiegeID valide)
        // ============================================================
        if (!$user || !$user->isSeller()) {
            if ($this->input('SiegeID') === '' || $this->input('SiegeID') === null) {
                $this->merge(['SiegeID' => null]);
            }
        }
    }
    
}
