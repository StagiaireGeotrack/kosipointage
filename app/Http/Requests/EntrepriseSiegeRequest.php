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
        return [
            'Nom' => 'required|string|max:255',
            'Nom_Lieu_Ville' => 'nullable|string|max:255',
            'Actived' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'Nom.required' => __('validation.required', ['attribute' => __('app.office_name')]),
            'Nom.max' => __('validation.max.string', ['attribute' => __('app.office_name'), 'max' => 255]),
            'Nom_Lieu_Ville.max' => __('validation.max.string', ['attribute' => __('app.location'), 'max' => 255]),
        ];
    }
}