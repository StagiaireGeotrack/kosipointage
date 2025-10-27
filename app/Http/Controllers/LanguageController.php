<?php
// app/Http/Controllers/LanguageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change la langue de l'application
     *
     * @param string $locale Code de langue (fr, en)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage(string $locale)
    {
        // Vérifier si la langue demandée est supportée
        if (!in_array($locale, ['fr', 'en'])) {
            // Par défaut, utiliser la langue définie dans config
            $locale = config('app.locale');
        }
        
        // Stocker la préférence de langue dans la session
        Session::put('locale', $locale);
        
        // Optionnel: Si l'utilisateur est connecté, stocker également sa préférence dans la base de données
        if (auth()->check()) {
            // Si vous souhaitez ajouter un champ 'locale' à votre modèle Administration
            // auth()->user()->update(['locale' => $locale]);
        }
        
        // Rediriger vers la page précédente
        return redirect()->back();
    }
}