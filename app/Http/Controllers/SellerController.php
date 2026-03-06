<?php
// app/Http/Controllers/Admin/SellerController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;
use App\Models\Administration;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use App\Services\ActivityLogService;
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
                "Statut" => $revender->Actived ? __('Activé') : __('Désactivé') 
            ] ;
        } ) ;

        return $data;
    }

    public function exportExcel(Request $request)
    {
        $data = $this->prepareExportData($request);
        ActivityLogService::log(action: 'export_excel', modelType: 'Seller');
        return $this->exportService->exportToExcel($data, "Liste des revendeurs");
    }

    public function exportPdf(Request $request)
    {
        $data = $this->prepareExportData($request);
        ActivityLogService::log(action: 'export_pdf', modelType: 'Seller');
        return $this->exportService->exportToPdf($data, "Liste des revendeurs", 'exports.generic');
    }

    // Afficher le formulaire de création d'un revendeur
    public function create()
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs';
            return view('403', compact('message'));
        }

        // Récupérer uniquement les sièges qui ne sont PAS déjà associés à un vendeur
        $sieges = EntrepriseSiege::whereNotIn('ID', function($query) {
            $query->select('SiegeID')
                  ->from('seller_sieges');
        })
        ->orderBy('Nom')
        ->get();

        return view('admin.sellers.create', compact('sieges'));
    }

    // Enregistrer un nouveau revendeur
    public function store(Request $request)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            abort(403, 'Accès réservé aux Administrateurs');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:administration,Identifiant_email',
            'password' => 'required|min:8|confirmed',
            'sieges' => 'required|array|min:1',
            'sieges.*' => 'exists:Entreprises_sieges,ID',
        ], [
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'L\'adresse email doit être valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'sieges.required' => 'Vous devez sélectionner au moins un siège',
            'sieges.min' => 'Vous devez sélectionner au moins un siège',
        ]);

        // Vérifier que les sièges sélectionnés ne sont pas déjà associés à un autre vendeur
        $alreadyAssigned = DB::table('seller_sieges')
            ->whereIn('SiegeID', $validated['sieges'])
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withErrors(['sieges' => 'Un ou plusieurs sièges sont déjà associés à un autre vendeur'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Créer le vendeur
            $seller = Administration::create([
                'Identifiant_email' => $validated['email'],
                'Password_' => sha1($validated['password']),
                'IsSuperAdmin' => 1,
                'IsSeller' => 1,
                'Actived' => 1,
            ]);

            // Associer les sièges
            foreach ($validated['sieges'] as $siegeId) {
                DB::table('seller_sieges')->insert([
                    'SellerID' => $seller->ID,
                    'SiegeID' => $siegeId,
                ]);
            }

            DB::commit();

            ActivityLogService::log(
                action: 'create',
                modelType: 'Administration',
                modelId: $seller->ID,
                modelLabel: $seller->Identifiant_email,
            );

            return redirect()->back()->with('success', 'Revendeur créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de la création du revendeur'])
                ->withInput();
        }
    }

    // Afficher les détails d'un revendeur
    public function show($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::with('sellerSieges')->findOrFail($id);

        // Vérifier que c'est bien un revendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un revendeur');
        }

        return view('admin.sellers.show', compact('seller'));
    }

    // Afficher le formulaire d'édition d'un revendeur
    public function edit($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs';
            return view('403', compact('message'));
        }

        $seller = Administration::where('ID', $id)
            ->where('IsSeller', 1)
            ->where('IsSuperAdmin', 1)
            ->with('sellerSieges')
            ->firstOrFail();

        // Sièges actuellement associés au vendeur
        $currentSiegeIds = $seller->sellerSieges->pluck('ID')->toArray();

        // Sièges disponibles : 
        // - Soit pas encore associés à aucun vendeur
        // - Soit déjà associés au vendeur actuel
        $sieges = EntrepriseSiege::where(function($query) use ($id) {
            // Sièges non associés
            $query->whereNotIn('ID', function($subQuery) {
                $subQuery->select('SiegeID')
                         ->from('seller_sieges');
            })
            // OU sièges associés au vendeur actuel
            ->orWhereIn('ID', function($subQuery) use ($id) {
                $subQuery->select('SiegeID')
                         ->from('seller_sieges')
                         ->where('SellerID', $id);
            });
        })
        ->orderBy('Nom')
        ->get();

        return view('admin.sellers.edit', compact('seller', 'sieges', 'currentSiegeIds'));
    }

    // Mettre à jour un revendeur
    public function update(Request $request, $id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::where('ID', $id)
            ->where('IsSeller', 1)
            ->where('IsSuperAdmin', 1)
            ->firstOrFail();

        $validated = $request->validate([
            'email' => 'required|email|unique:administration,Identifiant_email,' . $id . ',ID',
            'password' => 'nullable|min:8|confirmed',
            'sieges' => 'required|array|min:1',
            'sieges.*' => 'exists:Entreprises_sieges,ID',
        ], [
            'email.required' => 'L\'adresse email est obligatoire',
            'email.email' => 'L\'adresse email doit être valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'sieges.required' => 'Vous devez sélectionner au moins un siège',
            'sieges.min' => 'Vous devez sélectionner au moins un siège',
        ]);

        // Vérifier que les sièges sélectionnés ne sont pas déjà associés à un AUTRE vendeur
        $alreadyAssigned = DB::table('seller_sieges')
            ->whereIn('SiegeID', $validated['sieges'])
            ->where('SellerID', '!=', $id)
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withErrors(['sieges' => 'Un ou plusieurs sièges sont déjà associés à un autre vendeur'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Mettre à jour l'email
            $seller->Identifiant_email = $validated['email'];

            // Mettre à jour le mot de passe si fourni
            if (!empty($validated['password'])) {
                $seller->Password_ = sha1($validated['password']);
            }

            $seller->save();

            // Supprimer toutes les anciennes associations
            DB::table('seller_sieges')->where('SellerID', $id)->delete();

            // Créer les nouvelles associations
            foreach ($validated['sieges'] as $siegeId) {
                DB::table('seller_sieges')->insert([
                    'SellerID' => $seller->ID,
                    'SiegeID' => $siegeId,
                ]);
            }

            DB::commit();

            ActivityLogService::log(
                action: 'update',
                modelType: 'Administration',
                modelId: $seller->ID,
                modelLabel: $seller->Identifiant_email,
            );

            return redirect()->back()->with('success', 'Revendeur modifié avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de la modification du revendeur'])
                ->withInput();
        }
    }


    // Supprimer un revendeur
    public function destroy($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {            
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::findOrFail($id);

        // Vérifier que c'est bien un revendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un revendeur');
        }

        DB::beginTransaction();

        try {
            ActivityLogService::log(
                action: 'delete',
                modelType: 'Administration',
                modelId: $seller->ID,
                modelLabel: $seller->Identifiant_email,
            );

            // La suppression des sièges associés se fera automatiquement grâce à ON DELETE CASCADE
            Administration::where("ID" , $id)->update(["deleted" => true , "Actived" => false ]);
            // $seller->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Revendeur supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression du revendeur : ' . $e->getMessage());
        }
    }


    public function reset($id)
    {
        if (!auth()->user()->isTrueSuperAdmin()) {            
            $message = 'Accès réservé aux Administrateurs' ;
            return view( '403' , compact('message') );
        }

        $seller = Administration::findOrFail($id);

        // Vérifier que c'est bien un revendeur
        if (!$seller->isSeller()) {
            return redirect()
                ->route('sellers.index')
                ->with('error', 'Cet utilisateur n\'est pas un revendeur');
        }

        DB::beginTransaction();

        try {
            ActivityLogService::log(
                action: 'delete',
                modelType: 'Administration',
                modelId: $seller->ID,
                modelLabel: $seller->Identifiant_email,
            );

            // La suppression des sièges associés se fera automatiquement grâce à ON DELETE CASCADE
            Administration::where("ID" , $id)->update(["deleted" => false , "Actived" => true ]);
            // $seller->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Revendeur supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression du revendeur : ' . $e->getMessage());
        }
    }

    // Activer/Désactiver un revendeur
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
                ->with('error', 'Cet utilisateur n\'est pas un revendeur');
        }

        $seller->update([
            'Actived' => !$seller->Actived
        ]);

        ActivityLogService::log(
            action: 'toggle_active',
            modelType: 'Administration',
            modelId: $seller->ID,
            modelLabel: $seller->Identifiant_email,
        );

        $status = $seller->Actived ? 'activé' : 'désactivé';

        return redirect()->back()->with('success', "Revendeur {$status} avec succès");
    }

    public function show_employee($id)
    {
        $employe = Employe::with('siege')->findOrFail($id);
        
        return view('employes.details', compact('employe'));
    }
}