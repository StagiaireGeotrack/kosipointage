<?php
// app/Http/Requests/ProfileUpdateRequest.php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Le nom est obligatoire.'),
            'name.string' => __('Le nom doit être une chaîne de caractères.'),
            'name.max' => __('Le nom ne peut pas dépasser :max caractères.'),
            
            'email.required' => __('L\'adresse e-mail est obligatoire.'),
            'email.string' => __('L\'adresse e-mail doit être une chaîne de caractères.'),
            'email.lowercase' => __('L\'adresse e-mail doit être en minuscules.'),
            'email.email' => __('L\'adresse e-mail doit être valide.'),
            'email.max' => __('L\'adresse e-mail ne peut pas dépasser :max caractères.'),
            'email.unique' => __('Cette adresse e-mail est déjà utilisée.'),
        ];
    }
}