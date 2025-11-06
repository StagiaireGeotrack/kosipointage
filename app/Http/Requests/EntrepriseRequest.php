<?php
// app/Http/Requests/EntrepriseRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Vérifie si l'utilisateur peut accéder au siège
        return Gate::allows('access-siege', $this->input('SiegeID'));
    }

    public function rules(): array
    {
        return [
            'Nom' => 'required|string|max:255',
            'Nom_Lieu_Ville' => 'nullable|string|max:255',
            'Latitude' => 'required|numeric|between:-90,90',
            'Longitude' => 'required|numeric|between:-180,180',
            'RadiusInMeters' => 'nullable|numeric|min:1',
            'Actived' => 'nullable|boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'Logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'Nom.required' => 'Le nom est obligatoire.',
            'Nom.string' => 'Le nom doit être une chaîne de caractères.',
            'Nom.max' => 'Le nom ne peut pas dépasser :max caractères.',
            
            'Nom_Lieu_Ville.string' => 'Le nom du lieu/ville doit être une chaîne de caractères.',
            'Nom_Lieu_Ville.max' => 'Le nom du lieu/ville ne peut pas dépasser :max caractères.',
            
            'Latitude.required' => 'La latitude est obligatoire.',
            'Latitude.numeric' => 'La latitude doit être un nombre.',
            'Latitude.between' => 'La latitude doit être comprise entre :min et :max.',
            
            'Longitude.required' => 'La longitude est obligatoire.',
            'Longitude.numeric' => 'La longitude doit être un nombre.',
            'Longitude.between' => 'La longitude doit être comprise entre :min et :max.',
            
            'RadiusInMeters.numeric' => 'Le rayon doit être un nombre.',
            'RadiusInMeters.min' => 'Le rayon doit être au moins :min mètre.',
            
            'Actived.boolean' => 'Le statut actif doit être vrai ou faux.',
            
            'SiegeID.required' => 'Le siège est obligatoire.',
            'SiegeID.exists' => 'Le siège sélectionné n\'existe pas.',
            
            'Logo.image' => 'Le logo doit être une image.',
            'Logo.mimes' => 'Le logo doit être au format : :values.',
            'Logo.max' => 'Le logo ne peut pas dépasser :max Ko (2 Mo).',
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