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
            'HasBiometricSetup' => 'boolean',
            'HasFaceSetup' => 'boolean',
            'Pin' => 'nullable|string',
            'Actived' => 'boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'FaceEncodingFile' => 'nullable|image|max:5120', // Pour l'upload de photo
        ];

        // En cas de mise à jour, permettre de conserver le même BadgeID
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules['BadgeID'] = 'required|string|max:25|unique:Employes,BadgeID,'.$this->route('employe').',ID';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'Nom.required' => __('validation.required', ['attribute' => __('app.employee_name')]),
            'Nom.max' => __('validation.max.string', ['attribute' => __('app.employee_name'), 'max' => 255]),
            'BadgeID.required' => __('validation.required', ['attribute' => __('app.badge_id')]),
            'BadgeID.max' => __('validation.max.string', ['attribute' => __('app.badge_id'), 'max' => 25]),
            'BadgeID.unique' => __('validation.unique', ['attribute' => __('app.badge_id')]),
            'SiegeID.required' => __('validation.required', ['attribute' => __('app.office')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('app.office')]),
            'FaceEncodingFile.image' => __('validation.image', ['attribute' => __('app.face_encoding')]),
            'FaceEncodingFile.max' => __('validation.max.file', ['attribute' => __('app.face_encoding'), 'max' => 5120]),
        ];
    }
}