<?php
// app/Http/Requests/EmployeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class EmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('access-siege', $this->input('SiegeID'));
    }

    public function rules(): array
    {
        $siegeId = $this->input('SiegeID');
        $isUpdate = $this->method() === 'PUT' || $this->method() === 'PATCH';
        $employeId = $isUpdate ? $this->route('employe') : null;

        $rules = [
            'Nom' => 'required|string|max:255',
            'HasBiometricSetup' => 'nullable|boolean',
            'HasFaceSetup' => 'nullable|boolean',
            'Actived' => 'nullable|boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'FaceEncodingFile' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
        ];

        // Règle d'unicité pour BadgeID dans le siège
        if ($isUpdate) {
            $rules['BadgeID'] = [
                'required',
                'string',
                'max:25',
                Rule::unique('Employes', 'BadgeID')
                    ->where('SiegeID', $siegeId)
                    ->ignore($employeId, 'ID')
            ];
        } else {
            $rules['BadgeID'] = [
                'required',
                'string',
                'max:25',
                Rule::unique('Employes', 'BadgeID')
                    ->where('SiegeID', $siegeId)
            ];
        }

        // Règle d'unicité pour Pin dans le siège
        if ($isUpdate) {
            $rules['Pin'] = [
                'nullable',
                'string',
                'size:6',
                'regex:/^[0-9]{6}$/',
                Rule::unique('Employes', 'Pin')
                    ->where('SiegeID', $siegeId)
                    ->ignore($employeId, 'ID')
                    ->whereNotNull('Pin') // Ignore les NULL pour permettre plusieurs employés sans PIN
            ];
        } else {
            $rules['Pin'] = [
                'nullable',
                'string',
                'size:6',
                'regex:/^[0-9]{6}$/',
                Rule::unique('Employes', 'Pin')
                    ->where('SiegeID', $siegeId)
                    ->whereNotNull('Pin') // Ignore les NULL pour permettre plusieurs employés sans PIN
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Nom.required' => 'Le nom est obligatoire.',
            'Nom.string' => 'Le nom doit être une chaîne de caractères.',
            'Nom.max' => 'Le nom ne peut pas dépasser :max caractères.',
            
            'BadgeID.required' => 'Le Badge ID est obligatoire.',
            'BadgeID.string' => 'Le Badge ID doit être une chaîne de caractères.',
            'BadgeID.max' => 'Le Badge ID ne peut pas dépasser :max caractères.',
            'BadgeID.unique' => 'Ce Badge ID est déjà utilisé dans ce siège.',
            
            'HasBiometricSetup.boolean' => 'La configuration biométrique doit être vrai ou faux.',
            'HasFaceSetup.boolean' => 'La configuration faciale doit être vrai ou faux.',
            
            'Pin.size' => 'Le code PIN doit contenir exactement :size chiffres.',
            'Pin.regex' => 'Le code PIN doit contenir uniquement des chiffres.',
            'Pin.unique' => 'Ce code PIN est déjà utilisé dans ce siège.',
            'Pin.string' => 'Le code PIN doit être une chaîne de caractères.',
            
            'Actived.boolean' => 'Le statut actif doit être vrai ou faux.',
            
            'SiegeID.required' => 'Le siège est obligatoire.',
            'SiegeID.exists' => 'Le siège sélectionné n\'existe pas.',
            
            'FaceEncodingFile.image' => 'Le fichier doit être une image.',
            'FaceEncodingFile.mimes' => 'L\'image doit être au format : :values.',
            'FaceEncodingFile.max' => 'L\'image ne peut pas dépasser :max Ko (5 Mo).',
        ];
    }

    /**
     * Prépare les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir les checkboxes en booléens
        $this->merge([
            'HasBiometricSetup' => $this->has('HasBiometricSetup') ? true : false,
            'HasFaceSetup' => $this->has('HasFaceSetup') ? true : false,
            'Actived' => $this->has('Actived') ? true : false,
        ]);
        
        // Convertir Pin vide en null pour éviter les problèmes de validation
        if ($this->input('Pin') === '') {
            $this->merge(['Pin' => null]);
        }
    }
}