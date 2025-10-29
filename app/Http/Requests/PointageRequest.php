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
            'synced' => 'boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'photo' => 'nullable|image|max:5120', 
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('validation.required', ['attribute' => __('Employé')]),
            'employee_id.exists' => __('validation.exists', ['attribute' => __('Employé')]),
            'type_.required' => __('validation.required', ['attribute' => __('Type')]),
            'type_.in' => __('validation.in', ['attribute' => __('Type')]),
            'auth_method.required' => __('validation.required', ['attribute' => __('Méthode')]),
            'auth_method.in' => __('validation.in', ['attribute' => __('Méthode')]),
            'timestamp_.required' => __('validation.required', ['attribute' => __('Date et heure')]),
            'timestamp_.date' => __('validation.date', ['attribute' => __('Date et heure')]),
            'latitude.required' => __('validation.required', ['attribute' => __('Latitude')]),
            'latitude.between' => __('validation.between.numeric', ['attribute' => __('Latitude'), 'min' => -90, 'max' => 90]),
            'longitude.required' => __('validation.required', ['attribute' => __('Longitude')]),
            'longitude.between' => __('validation.between.numeric', ['attribute' => __('Longitude'), 'min' => -180, 'max' => 180]),
            'SiegeID.required' => __('validation.required', ['attribute' => __('Siège')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('Siège')]),
            'photo.image' => __('validation.image', ['attribute' => __('Face image')]),
            'photo.max' => __('validation.max.file', ['attribute' => __('Face image'), 'max' => 5120]),
        ];
    }
}