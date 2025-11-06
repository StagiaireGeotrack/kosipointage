<?php
// app/Http/Requests/PointageRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PointageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('access-siege', $this->input('SiegeID'));
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:Employes,ID',
            'type_' => 'required|in:entry,exit',
            'auth_method' => 'required|in:rfid,face,pin',
            'timestamp_' => 'required|date',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'synced' => 'nullable|boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120', 
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Employé')]),
            'employee_id.exists' => __('L\'employé sélectionné n\'existe pas'),
            
            'type_.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Type de pointage')]),
            'type_.in' => __('Le type de pointage doit être "entrée" ou "sortie"'),
            
            'auth_method.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Méthode d\'authentification')]),
            'auth_method.in' => __('La méthode d\'authentification doit être "rfid", "face" ou "pin"'),
            
            'timestamp_.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Date et heure')]),
            'timestamp_.date' => __('Le champ :attribute doit être une date valide', ['attribute' => __('Date et heure')]),
            
            'latitude.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Latitude')]),
            'latitude.numeric' => __('La latitude doit être un nombre'),
            'latitude.between' => __('La latitude doit être comprise entre :min et :max', ['min' => -90, 'max' => 90]),
            
            'longitude.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Longitude')]),
            'longitude.numeric' => __('La longitude doit être un nombre'),
            'longitude.between' => __('La longitude doit être comprise entre :min et :max', ['min' => -180, 'max' => 180]),
            
            'synced.boolean' => __('Le statut de synchronisation doit être vrai ou faux'),
            
            'SiegeID.required' => __('Le champ :attribute est obligatoire', ['attribute' => __('Siège')]),
            'SiegeID.exists' => __('Le siège sélectionné n\'existe pas'),
            
            'photo.image' => __('Le fichier doit être une image'),
            'photo.mimes' => __('L\'image doit être au format : :values'),
            'photo.max' => __('L\'image ne peut pas dépasser :max Ko (5 Mo)', ['max' => 5120]),
        ];
    }

    /**
     * Prépare les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir synced en booléen si présent
        if ($this->has('synced')) {
            $this->merge([
                'synced' => $this->input('synced') ? true : false,
            ]);
        }
    }
}