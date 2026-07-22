<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Pointage;

class EmployePortalController extends Controller
{
    /**
     * Affiche le tableau de bord principal de l'employé
     */
    public function index()
    {
        return view('employe_dashboard.index');
    }

    /**
     * Affiche le formulaire de modification du profil
     */
    public function editProfile()
    {
        $employe = Auth::guard('employe')->user();
        return view('employe_dashboard.profile', compact('employe'));
    }

    /**
     * Met à jour le profil de l'employé
     */
    public function updateProfile(Request $request)
    {
        $employe = Auth::guard('employe')->user();

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('Employes')->ignore($employe->ID, 'ID'),
            ],
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'telephone.max' => 'Le numéro de téléphone est trop long.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $employe->email = $request->email;
        $employe->telephone = $request->telephone;

        if ($request->filled('password')) {
            $employe->password = Hash::make($request->password);
        }

        $employe->save();

        return redirect()->route('employe.profile')->with('success', __('Profil mis à jour avec succès.'));
    }

    /**
     * Helper pour parser les dates provenant des filtres
     */
    private function parseDate($dateString)
    {
        try {
            if (strpos($dateString, '/') !== false) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $dateString);
            }
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Affiche l'historique des pointages de l'employé
     */
    public function pointages(Request $request)
    {
        $employeId = Auth::guard('employe')->id();
        
        $query = Pointage::where('employee_id', $employeId);

        if ($request->filled('date_debut')) {
            if ($date = $this->parseDate($request->date_debut)) {
                $query->where('timestamp_', '>=', $date->startOfDay());
            }
        }
        
        if ($request->filled('date_fin')) {
            if ($date = $this->parseDate($request->date_fin)) {
                $query->where('timestamp_', '<=', $date->endOfDay());
            }
        }
        
        if ($request->filled('type_pointage')) {
            if ($request->type_pointage == 'entree') {
                $query->where(function($q) {
                    $q->where('type_', 'LIKE', '%entree%')
                      ->orWhere('type_', 'LIKE', '%entrée%')
                      ->orWhere('type_', 'LIKE', '%Entree%')
                      ->orWhere('type_', 'LIKE', '%Entrée%')
                      ->orWhere('type_', 'LIKE', '%entry%')
                      ->orWhere('type_', 'LIKE', '%Entry%');
                });
            } else if ($request->type_pointage == 'sortie') {
                $query->where(function($q) {
                    $q->where('type_', 'LIKE', '%sortie%')
                      ->orWhere('type_', 'LIKE', '%Sortie%')
                      ->orWhere('type_', 'LIKE', '%exit%')
                      ->orWhere('type_', 'LIKE', '%Exit%');
                });
            }
        }
            
        $pointages = $query->orderBy('timestamp_', 'desc')->paginate(5);
        $pointages->appends($request->only(['date_debut', 'date_fin', 'type_pointage']));
            
        return view('employe_dashboard.pointages', compact('pointages'));
    }

    /**
     * Affiche les rapports quotidiens de l'employé
     */
    public function rapports(Request $request)
    {
        $employeId = Auth::guard('employe')->id();
        
        $query = DB::table('rapports_details')->where('employee_id', $employeId);

        if ($request->filled('date_debut')) {
            if ($date = $this->parseDate($request->date_debut)) {
                $query->where('date_reel', '>=', $date->format('Y-m-d'));
            }
        }
        
        if ($request->filled('date_fin')) {
            if ($date = $this->parseDate($request->date_fin)) {
                $query->where('date_reel', '<=', $date->format('Y-m-d'));
            }
        }
            
        $rapports = $query->orderBy('date_reel', 'desc')->paginate(5);
        $rapports->appends($request->only(['date_debut', 'date_fin']));
            
        return view('employe_dashboard.rapports', compact('rapports'));
    }
}
