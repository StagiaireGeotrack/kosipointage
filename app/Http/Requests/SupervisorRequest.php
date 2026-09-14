<?php
// app/Http/Requests/SupervisorRequest.php

namespace App\Http\Requests;

use App\Services\AccessScopeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        // Seul le Simple Admin (IsManager=1) peut gérer les Supervisors
        return $user && $user->isSimpleAdminStrict();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $supervisorId = $isUpdate ? $this->route('supervisor') : null;

        $rules = [
            'Identifiant_email' => $isUpdate
                ? 'required|email|max:255|unique:administration,Identifiant_email,'.$supervisorId.',ID'
                : 'required|email|max:255|unique:administration,Identifiant_email',
            'service_ids'   => 'required|array|min:1',
            'service_ids.*' => 'exists:departments,id',
            'Actived'       => 'nullable|boolean',
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
            'Identifiant_email.required'   => __('L\'email est obligatoire.'),
            'Identifiant_email.email'      => __('L\'email doit être valide.'),
            'Identifiant_email.unique'     => __('Cet email est déjà utilisé.'),
            'password.required'            => __('Le mot de passe est obligatoire.'),
            'password.confirmed'           => __('Les mots de passe ne correspondent pas.'),
            'password.min'                 => __('Le mot de passe doit contenir au moins :min caractères.'),
            'service_ids.required'         => __('Vous devez sélectionner au moins un service.'),
            'service_ids.min'              => __('Vous devez sélectionner au moins un service.'),
            'service_ids.*.exists'         => __('Un des services sélectionnés n\'existe pas.'),
        ];
    }

    /**
     * Vérification métier : les services doivent appartenir au siège du créateur
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            $serviceIds = $this->input('service_ids', []);

            if (!empty($serviceIds) && $user && $user->SiegeID) {
                if (!AccessScopeService::allServicesBelongToSiege($serviceIds, $user->SiegeID)) {
                    $validator->errors()->add(
                        'service_ids',
                        __('Tous les services doivent appartenir à votre entreprise.')
                    );
                }
            }
        });
    }

    /**
     * Convertir la checkbox Actived en boolean
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'Actived' => $this->has('Actived') ? 1 : 0,
        ]);
    }
}
