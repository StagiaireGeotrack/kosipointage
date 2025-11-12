<?php
// app/Http/Requests/EntrepriseSiegeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EntrepriseSiegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('superadmin');
    }

    public function rules(): array
    {
        $rules = [
            'Nom' => 'required|string|max:255|unique:Entreprises_sieges,Nom',
            'Nom_Lieu_Ville' => 'nullable|string|max:255',
            'Pays' => 'nullable|string|max:255',
            'Actived' => 'nullable|boolean',
        ];

        // En cas de mise à jour, permettre de conserver le même nom
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $siegeId = $this->route('siege') ?? $this->route('entreprises_siege');
            $rules['Nom'] = 'required|string|max:255|unique:Entreprises_sieges,Nom,' . $siegeId . ',ID';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Nom.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Nom du siège')]),
            'Nom.string' => __('Le champ :attribute doit être une chaîne de caractères', ['attribute' => __('Nom du siège')]),
            'Nom.max' => __('Le champ :attribute ne doit pas dépasser :max caractères', ['attribute' => __('Nom du siège'), 'max' => 255]),
            'Nom.unique' => __('Ce nom de siège est déjà utilisé'),
            
            'Nom_Lieu_Ville.string' => __('Le champ :attribute doit être une chaîne de caractères', ['attribute' => __('Adresse ou ville')]),
            'Nom_Lieu_Ville.max' => __('Le champ :attribute ne doit pas dépasser :max caractères', ['attribute' => __('Adresse ou ville'), 'max' => 255]),

            'Pays.string' => __('Le champ :attribute doit être une chaîne de caractères', ['attribute' => __('Pays')]),
            'Pays.max' => __('Le champ :attribute ne doit pas dépasser :max caractères', ['attribute' => __('Pays'), 'max' => 255]),
            
            'Actived.boolean' => __('Le statut actif doit être vrai ou faux'),
        ];
    }

    /**
     * Prépare les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir la checkbox Actived en booléen
        $this->merge([
            'Actived' => $this->has('Actived') ? true : false,
        ]);
    }
}