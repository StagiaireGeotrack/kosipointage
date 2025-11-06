<?php
// app/Http/Requests/JourNonTravailleRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class JourNonTravailleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $siegeId = $this->input('SiegeID');
        
        // ✅ Si SiegeID est null (jour national), tous les admins peuvent gérer
        if ($siegeId === null || $siegeId === '') {
            return auth()->check(); // Tous les admins authentifiés
        }
        
        // ✅ Sinon, vérifier l'accès au siège spécifique
        return Gate::allows('access-siege', $siegeId);
    }

    public function rules(): array
    {
        return [
            'Date' => 'required|date',
            'Nom' => 'required|string|max:255',
            'Type' => 'required|in:ferie,fermeture,autre',
            'SiegeID' => 'nullable|exists:Entreprises_sieges,ID',
            'Recurrent' => 'nullable|boolean',
            'Description' => 'nullable|string|max:1000',
            'Actived' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'Date.required' => __('La date est obligatoire.'),
            'Date.date' => __('La date doit être une date valide.'),
            
            'Nom.required' => __('Le nom est obligatoire.'),
            'Nom.string' => __('Le nom doit être une chaîne de caractères.'),
            'Nom.max' => __('Le nom ne peut pas dépasser :max caractères.'),
            
            'Type.required' => __('Le type est obligatoire.'),
            'Type.in' => __('Le type doit être : férié, fermeture ou autre.'),
            
            'SiegeID.exists' => __('Le siège sélectionné n\'existe pas.'),
            
            'Recurrent.boolean' => __('Le champ récurrent doit être vrai ou faux.'),
            
            'Description.string' => __('La description doit être une chaîne de caractères.'),
            'Description.max' => __('La description ne peut pas dépasser :max caractères.'),
            
            'Actived.boolean' => __('Le statut actif doit être vrai ou faux.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        Log::info('=== DEBUT prepareForValidation JourNonTravailleRequest ===');
        Log::info('Données AVANT: ', $this->all());
        
        $this->merge([
            'Recurrent' => $this->has('Recurrent') ? true : false,
            'Actived' => $this->has('Actived') ? true : false,
        ]);
        
        if ($this->input('SiegeID') === '' || $this->input('SiegeID') === null) {
            $this->merge(['SiegeID' => null]);
        }
        
        Log::info('Données APRÈS: ', $this->all());
        Log::info('=== FIN prepareForValidation ===');
    }
}