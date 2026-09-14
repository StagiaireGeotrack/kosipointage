<?php
// app/Http/Requests/MasterRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class MasterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        // Seul le Simple Admin PUR peut créer/modifier un Master
        return $user && $user->isSimpleAdminPure();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $masterId = $isUpdate ? $this->route('master') : null;

        $rules = [
            'Identifiant_email' => $isUpdate
                ? 'required|email|max:255|unique:administration,Identifiant_email,'.$masterId.',ID'
                : 'required|email|max:255|unique:administration,Identifiant_email',
            'Actived' => 'nullable|boolean',
        ];

        if ($isUpdate) {
            $rules['password'] = ['nullable', 'confirmed', Password::min(8)];
        } else {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Identifiant_email.required' => __('L\'email est obligatoire.'),
            'Identifiant_email.email'    => __('L\'email doit être valide.'),
            'Identifiant_email.unique'   => __('Cet email est déjà utilisé.'),
            'password.required'          => __('Le mot de passe est obligatoire.'),
            'password.confirmed'         => __('Les mots de passe ne correspondent pas.'),
            'password.min'               => __('Le mot de passe doit contenir au moins :min caractères.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'Actived' => $this->has('Actived') ? 1 : 0,
        ]);
    }
}
