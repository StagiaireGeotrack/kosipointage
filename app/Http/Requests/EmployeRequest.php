<?php
// app/Http/Requests/EmployeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('access-siege', $this->input('SiegeID'));
    }

    public function rules(): array
    {
        $rules = [
            'Nom' => 'required|string|max:255',
            'BadgeID' => 'required|string|max:25|unique:Employes,BadgeID',
            'HasBiometricSetup' => 'nullable|boolean',
            'HasFaceSetup' => 'nullable|boolean',
            'Pin' => 'nullable|string|size:6|regex:/^[0-9]{6}$/',
            'Actived' => 'nullable|boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'FaceEncodingFile' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120',
        ];

        // En cas de mise à jour, permettre de conserver le même BadgeID
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $employeId = $this->route('employe');
            $rules['BadgeID'] = 'required|string|max:25|unique:Employes,BadgeID,' . $employeId . ',ID';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Nom.required' => 'Le nom est obligatoire.',
            'Nom.max' => 'Le nom ne peut pas dépasser :max caractères.',
            
            'BadgeID.required' => 'Le Badge ID est obligatoire.',
            'BadgeID.max' => 'Le Badge ID ne peut pas dépasser :max caractères.',
            'BadgeID.unique' => 'Ce Badge ID est déjà utilisé.',
            
            'Pin.size' => 'Le code PIN doit contenir exactement :size chiffres.',
            'Pin.regex' => 'Le code PIN doit contenir uniquement des chiffres.',
            
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
    protected function prepareForValidation()
    {
        // Convertir les checkboxes en booléens
        $data = [];
        
        if ($this->has('HasBiometricSetup')) {
            $data['HasBiometricSetup'] = $this->input('HasBiometricSetup') ? true : false;
        } else {
            $data['HasBiometricSetup'] = false;
        }
        
        if ($this->has('Actived')) {
            $data['Actived'] = $this->input('Actived') ? true : false;
        } else {
            $data['Actived'] = false;
        }
        
        $this->merge($data);
    }
}