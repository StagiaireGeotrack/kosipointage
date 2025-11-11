<?php
// app/Http/Controllers/Admin/SellerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Administration;
use App\Services\ExportService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SellerController extends Controller
{
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    // Afficher la liste des vendeurs
    public function index(Request $request)
    {
        // Vérifier que l'utilisateur connecté est un vrai Super Admin
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs';
            return view('403', compact('message'));
        }

        // Construction de la requête de base
        $query = Administration::where('IsSeller', 1)
            ->where('IsSuperAdmin', 1)
            ->with('sellerSieges');

        // Filtre par recherche (email)
        if ($request->filled('search')) {
            $query->where('Identifiant_email', 'like', '%' . $request->search . '%');
        }

        // Filtre par statut
        if ($request->has('status') && $request->status !== '') {
            $query->where('Actived', $request->status);
        }

        // Récupérer les vendeurs avec pagination
        $sellers = $query->orderBy('Identifiant_email')
            ->paginate(5)
            ->appends($request->except('page')); // Maintenir les paramètres dans la pagination

        return view('admin.sellers.index', compact('sellers'));
    }

    public function prepareExportData(Request $request) 
    {        
        $query = Administration::where('IsSeller', 1)
            ->where('IsSuperAdmin', 1)
            ->with('sellerSieges');

        if ($request->filled('search')) {
            $query->where('Identifiant_email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('Actived', $request->status);
        }

        $data = $query->orderBy('Identifiant_email')->get()->map( function ($revender) {
            return [
                "ID" => $revender->ID ,
                "Email" => $revender->Identifiant_email ,
                "Nombre de Sièges" => $revender->sellerSieges->count() ,
                "Statut" => $revender->Actived ? __('Oui') : __('Non') 
            ] ;
        } ) ;

        return $data;
    }

    public function exportExcel(Request $request)
    {
        $data = $this->prepareExportData($request);
        return $this->exportService->exportToExcel($data, "Liste des revendeurs");
    }

    public function exportPdf(Request $request)
    {
        $data = $this->prepareExportData($request);
        return $this->exportService->exportToPdf($data, "Liste des revendeurs", 'exports.generic');
    }

    // Afficher le formulaire de création d'un vendeur
    public function create()
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        // Récupérer tous les sièges actifs
        $sieges = DB::table('Entreprises_sieges')
            ->where('Actived', 1)
            ->orderBy('Nom')
            ->get();

        return view('admin.sellers.create', compact('sieges'));
    }

    // Enregistrer un nouveau vendeur
    public function store(Request $request)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('administration', 'Identifiant_email')
            ],
            'password' => 'required|string|min:6',
            'siege_ids' => 'required|array|min:1',
            'siege_ids.*' => 'exists:Entreprises_sieges,ID',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères',
            'siege_ids.required' => 'Vous devez sélectionner au moins un siège',
            'siege_ids.min' => 'Vous devez sélectionner au moins un siège',
        ]);

        DB::beginTransaction();

        try {
            // Créer le vendeur
            $seller = Administration::create([
                'Identifiant_email' => $request->email,
                'Password_' => sha1($request->password), // Utilisez Hash::make() si vous préférez bcrypt
                'IsSuperAdmin' => 1,
                'IsSeller' => 1,
                'SiegeID' => null, // Les vendeurs n'ont pas de siège principal
                'Actived' => 1,
            ]);

            // Assigner les sièges au vendeur
            $seller->sellerSieges()->attach($request->siege_ids);

            DB::commit();

            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendeur créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du vendeur : ' . $e->getMessage());
        }
    }

    // Afficher les détails d'un vendeur
    public function show($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::with('sellerSieges')->findOrFail($id);

        // Vérifier que c'est bien un vendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        return view('admin.sellers.show', compact('seller'));
    }

    // Afficher le formulaire d'édition d'un vendeur
    public function edit($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::with('sellerSieges')->findOrFail($id);

        // Vérifier que c'est bien un vendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        // Récupérer tous les sièges actifs
        $sieges = DB::table('Entreprises_sieges')
            ->where('Actived', 1)
            ->orderBy('Nom')
            ->get();

        return view('admin.sellers.edit', compact('seller', 'sieges'));
    }

    // Mettre à jour un vendeur
    public function update(Request $request, $id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::findOrFail($id);

        // Vérifier que c'est bien un vendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('administration', 'Identifiant_email')->ignore($id, 'ID')
            ],
            'password' => 'nullable|string|min:6',
            'siege_ids' => 'required|array|min:1',
            'siege_ids.*' => 'exists:Entreprises_sieges,ID',
            'actived' => 'boolean',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'L\'email doit être valide',
            'email.unique' => 'Cet email est déjà utilisé',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères',
            'siege_ids.required' => 'Vous devez sélectionner au moins un siège',
            'siege_ids.min' => 'Vous devez sélectionner au moins un siège',
        ]);

        DB::beginTransaction();

        try {
            // Mettre à jour les informations du vendeur
            $dataToUpdate = [
                'Identifiant_email' => $request->email,
                'Actived' => $request->has('actived') ? 1 : 0,
            ];

            // Si un nouveau mot de passe est fourni
            if ($request->filled('password')) {
                $dataToUpdate['Password_'] = sha1($request->password);
            }

            $seller->update($dataToUpdate);

            // Synchroniser les sièges (supprimer les anciens et ajouter les nouveaux)
            $seller->sellerSieges()->sync($request->siege_ids);

            DB::commit();

            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendeur mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du vendeur : ' . $e->getMessage());
        }
    }

    // Supprimer un vendeur
    public function destroy($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {            
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::findOrFail($id);

        // Vérifier que c'est bien un vendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        DB::beginTransaction();

        try {
            // La suppression des sièges associés se fera automatiquement grâce à ON DELETE CASCADE
            $seller->delete();

            DB::commit();

            return redirect()
                ->route('sellers.index')
                ->with('success', 'Vendeur supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression du vendeur : ' . $e->getMessage());
        }
    }

    // Activer/Désactiver un vendeur
    public function toggleActive($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {            
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::findOrFail($id);

        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un vendeur');
        }

        $seller->update([
            'Actived' => !$seller->Actived
        ]);

        $status = $seller->Actived ? 'activé' : 'désactivé';

        return redirect()
            ->back()
            ->with('success', "Vendeur {$status} avec succès");
    }
}