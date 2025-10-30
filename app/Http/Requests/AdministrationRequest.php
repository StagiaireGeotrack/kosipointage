<?php
// app/Http/Requests/AdministrationRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AdministrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('superadmin');
    }

    public function rules(): array
    {
        $rules = [
            'Identifiant_email' => 'required|email|max:255|unique:administration,Identifiant_email',
            'IsSuperAdmin' => 'nullable|boolean', // nullable car checkbox non cochée n'envoie rien
            'SiegeID' => 'required_unless:IsSuperAdmin,1|nullable|exists:Entreprises_sieges,ID', 
            'Actived' => 'nullable|boolean',
        ];
        
        // Pour la création, le mot de passe est obligatoire
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        } else {
            // Pour la mise à jour, le mot de passe est optionnel
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];
            
            // Permettre de conserver le même email pour l'administrateur actuel
            $rules['Identifiant_email'] = 'required|email|max:255|unique:administration,Identifiant_email,'.$this->route('administrateur').',ID';
        }
        
        return $rules;
    }

    public function messages(): array
    {
        return [
            'Identifiant_email.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.email' => __('Le champ :attribute doit être un e-mail', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.max' => __('Le champ :attribute est obligatoire', ['attribute' => __('Identifiant ou E-mail'), 'max' => 255]),
            'Identifiant_email.unique' => __('La valeur de :attribute est déjà utilisée', ['attribute' => __('Identifiant ou E-mail')]),
            'password.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Mot de passe')]),
            'password.confirmed' => __('Veuillez bien confirmer le champ :attribute', ['attribute' => __('Confirmation mot de pas')]),
            'SiegeID.required_unless' => __('Le champ :attribute est obligatoire pour les administrateurs simples.', ['attribute' => __('Siège')]),
            'SiegeID.exists' => __('Le siège n\'existe pas', ['attribute' => __('Siège')]),
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir les checkboxes en booléens
        $this->merge([
            'IsSuperAdmin' => $this->has('IsSuperAdmin') ? true : false,
            'Actived' => $this->has('Actived') ? true : false,
        ]);
    }
}