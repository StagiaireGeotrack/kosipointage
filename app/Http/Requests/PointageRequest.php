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
            'auth_method' => 'required|in:badge,face,pin,admin',
            'timestamp_' => 'required|date',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'synced' => 'boolean',
            'SiegeID' => 'required|exists:Entreprises_sieges,ID',
            'photo' => 'nullable|image|max:5120', // Pour l'upload de photo
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => __('validation.required', ['attribute' => __('app.employee')]),
            'employee_id.exists' => __('validation.exists', ['attribute' => __('app.employee')]),
            'type_.required' => __('validation.required', ['attribute' => __('app.type')]),
            'type_.in' => __('validation.in', ['attribute' => __('app.type')]),
            'auth_method.required' => __('validation.required', ['attribute' => __('app.auth_method')]),
            'auth_method.in' => __('validation.in', ['attribute' => __('app.auth_method')]),
            'timestamp_.required' => __('validation.required', ['attribute' => __('app.timestamp')]),
            'timestamp_.date' => __('validation.date', ['attribute' => __('app.timestamp')]),
            'latitude.required' => __('validation.required', ['attribute' => __('app.latitude')]),
            'latitude.between' => __('validation.between.numeric', ['attribute' => __('app.latitude'), 'min' => -90, 'max' => 90]),
            'longitude.required' => __('validation.required', ['attribute' => __('app.longitude')]),
            'longitude.between' => __('validation.between.numeric', ['attribute' => __('app.longitude'), 'min' => -180, 'max' => 180]),
            'SiegeID.required' => __('validation.required', ['attribute' => __('app.office')]),
            'SiegeID.exists' => __('validation.exists', ['attribute' => __('app.office')]),
            'photo.image' => __('validation.image', ['attribute' => __('app.photo')]),
            'photo.max' => __('validation.max.file', ['attribute' => __('app.photo'), 'max' => 5120]),
        ];
    }
}