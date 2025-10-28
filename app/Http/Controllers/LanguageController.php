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
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = config('app.locale');
        }
        
        Session::put('locale', $locale); 
        return redirect()->back();
    }
}