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
            'Nom.required' => __('validation.required', ['attribute' => __('app.company_name')]),
            'Nom.max' => __('validation.max.string', ['attribute' => __('app.company_name'), 'max' => 255]),
            'Latitude.required' => __('validation.required', ['attribute' => __('app.latitude')]),
            'Latitude.between' => __('validation.between.numeric', ['attribute' => __('app.latitude'), 'min' => -90, 'max' => 90]),
            'Longitude.required' => __('validation.required', ['attribute' => __('app.longitude')]),
            'Longitude.between' => __('validation.between.numeric', ['attribute' => __('app.longitude'), 'min' => -180, 'max' => 180]),
            'RadiusInMeters.min' => __('validation.min.numeric', ['attribute' => __('app.radius'), 'min' => 1]),
            'SiegeID.required' => __('validation.required', ['attribute' => __('app.office')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('app.office')]),
            'Logo.image' => __('validation.image', ['attribute' => __('app.logo')]),
            'Logo.max' => __('validation.max.file', ['attribute' => __('app.logo'), 'max' => 2048]),
        ];
    }
}