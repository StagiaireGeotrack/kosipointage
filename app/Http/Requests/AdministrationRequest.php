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
            'IsSuperAdmin' => 'boolean',
            'SiegeID' => 'required_if:IsSuperAdmin,0|exists:Entreprises_sieges,ID',
        ];
        
        // Pour la création, le mot de passe est obligatoire
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ];
        } else {
            // Pour la mise à jour, le mot de passe est optionnel
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
            ];
            
            // Permettre de conserver le même email pour l'administrateur actuel
            $rules['Identifiant_email'] = 'required|email|max:255|unique:administration,Identifiant_email,'.$this->route('administrateur').',ID';
        }
        
        return $rules;
    }

    public function messages(): array
    {
        return [
            'Identifiant_email.required' => __('validation.required', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.email' => __('validation.email', ['attribute' => __('Identifiant ou E-mail')]),
            'Identifiant_email.max' => __('validation.max.string', ['attribute' => __('Identifiant ou E-mail'), 'max' => 255]),
            'Identifiant_email.unique' => __('validation.unique', ['attribute' => __('Identifiant ou E-mail')]),
            'password.required' => __('validation.required', ['attribute' => __('Mot de passe')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('Mot de passe')]),
            'SiegeID.required_if' => __('validation.required_if', ['attribute' => __('Siège'), 'other' => __('Super Administrateur'), 'value' => __('Non')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('Siège')]),
        ];
    }
}