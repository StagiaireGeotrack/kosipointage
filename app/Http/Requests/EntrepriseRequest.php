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
            'Nom_Lieu_Ville' => 'nullable|string',
            'Latitude' => 'required|numeric|between:-90,90',
            'Longitude' => 'required|numeric|between:-180,180',
            'RadiusInMeters' => 'nullable|numeric|min:1',
            'Actived' => 'boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'Logo' => 'nullable|image|max:2048', // Logo comme fichier uploadé
        ];
    }

    public function messages(): array
    {
        return [
            'Nom.required' => __('validation.required', ['attribute' => __('Nom')]),
            'Nom.max' => __('validation.max.string', ['attribute' => __('Nom'), 'max' => 255]),
            'Latitude.required' => __('validation.required', ['attribute' => __('Latitude')]),
            'Latitude.between' => __('validation.between.numeric', ['attribute' => __('Latitude'), 'min' => -90, 'max' => 90]),
            'Longitude.required' => __('validation.required', ['attribute' => __('Longitude')]),
            'Longitude.between' => __('validation.between.numeric', ['attribute' => __('Longitude'), 'min' => -180, 'max' => 180]),
            'RadiusInMeters.min' => __('validation.min.numeric', ['attribute' => __('Rayon'), 'min' => 1]),
            'SiegeID.required' => __('validation.required', ['attribute' => __('Siège')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('Siège')]),
            'Logo.image' => __('validation.image', ['attribute' => __('Logo')]),
            'Logo.max' => __('validation.max.file', ['attribute' => __('Logo'), 'max' => 2048]),
        ];
    }
}