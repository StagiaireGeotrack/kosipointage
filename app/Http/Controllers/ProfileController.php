<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'Identifiant_email' => Auth::user()->Identifiant_email
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update_Identifiant_email(Request $request)
    {
        $id = Auth::user()->ID ;

        $request->validate([
            'Identifiant_email' => [
                'required',
                'email',
                Rule::unique('administration')->ignore($id),
            ],
        ],[
            'Identifiant_email.required' => "Le champ Identifiantou E-mail est obligatoire" ,
            'Identifiant_email.email' => "Le champ Identifiantou E-mail doit être un adresse E-mail" ,
            'Identifiant_email.unique' => "L'Identifiantou E-mail est déjà utilisé" ,
        ]);

        $administrateur = Auth::user() ;
        
        $administrateur->update([
            "Identifiant_email" => $request->Identifiant_email
        ]);

        return back()->with('success', "L'Identifiant ou E-mail a été mise à jour avec succès !");
    }


    public function update_Password(Request $request)
    {
        $administrateur = Auth::user();

        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($administrateur) {
                    if (sha1($value) !== $administrateur->Password_) {
                        $fail('Le mot de passe actuel est incorrect.');
                    }
                },
            ],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le champ mot de passe actuel est obligatoire.',
            'password.required' => 'Le champ nouveau mot de passe est obligatoire.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);
        
        $administrateur->update([
            'Password_' => sha1($request->password)
        ]);

        return back()->with('success', 'Le mot de passe a été modifié avec succès !');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
