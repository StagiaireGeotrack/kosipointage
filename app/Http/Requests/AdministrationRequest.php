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
    private $originalHasActived = false;

    public function authorize(): bool
    {
        return Gate::allows('superadmin');
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
            'Actived' => 'nullable|boolean',
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
        $this->originalHasActived = $this->has('Actived');
        
        // Convertir les checkboxes en 1/0
        $this->merge([
            'IsSuperAdmin' => $this->originalHasIsSuperAdmin ? 1 : 0,
            'Actived' => $this->originalHasActived ? 1 : 0,
        ]);
        
        // Convertir SiegeID vide en null
        if ($this->input('SiegeID') === '' || $this->input('SiegeID') === null) {
            $this->merge(['SiegeID' => null]);
        } 
    }
}