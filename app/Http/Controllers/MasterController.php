<?php
// app/Http/Controllers/MasterController.php

namespace App\Http\Controllers;

use App\Models\Administration;
use App\Http\Requests\MasterRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    /**
     * Afficher le Master actuel (ou null)
     */
    public function show()
    {
        $user = auth()->user();

        $master = Administration::where('IsMaster', 1)
            ->where('IsManager', 1)
            ->where('IsSuperAdmin', 0)
            ->where('IsSeller', 0)
            ->where('IsSupervisor', 0)
            ->where('SiegeID', $user->SiegeID)
            ->first();

        return view('master.show', compact('master'));
    }

    /**
     * Formulaire de création du Master
     * Bloqué si un Master existe déjà
     */
    public function create()
    {
        $user = auth()->user();

        // Vérifier qu'aucun Master n'existe déjà pour ce siège
        $existingMaster = Administration::where('IsMaster', 1)
            ->where('SiegeID', $user->SiegeID)
            ->where('deleted', 0)
            ->exists();

        if ($existingMaster) {
            return redirect()->route('master.show')
                ->with('error', __('Un Master existe déjà pour votre entreprise.'));
        }

        return view('master.create');
    }

    /**
     * Enregistrer le Master
     */
    public function store(MasterRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        // Double vérification : aucun Master existant
        $existingMaster = Administration::where('IsMaster', 1)
            ->where('SiegeID', $user->SiegeID)
            ->where('deleted', 0)
            ->exists();

        if ($existingMaster) {
            return back()->with('error', __('Un Master existe déjà pour votre entreprise.'));
        }

        $master = Administration::create([
            'Identifiant_email' => $data['Identifiant_email'],
            'Password_'         => sha1($data['password']),
            'IsSuperAdmin'      => 0,
            'IsSeller'          => 0,
            'IsManager'         => 1,
            'IsSupervisor'      => 0,
            'IsMaster'          => 1,
            'SiegeID'           => $user->SiegeID,
            'Actived'           => $data['Actived'] ?? 1,
            'deleted'           => 0,
        ]);

        ActivityLogService::log(
            action: 'master.create',
            modelType: 'Administration',
            modelId: $master->ID,
            modelLabel: $master->Identifiant_email,
        );

        return redirect()->route('master.show')
            ->with('success', __('Master créé avec succès.'));
    }

    /**
     * Formulaire de modification
     */
    public function edit()
    {
        $user = auth()->user();

        $master = Administration::where('IsMaster', 1)
            ->where('IsManager', 1)
            ->where('IsSuperAdmin', 0)
            ->where('IsSeller', 0)
            ->where('IsSupervisor', 0)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        return view('master.edit', compact('master'));
    }

    /**
     * Mettre à jour le Master
     */
    public function update(MasterRequest $request)
    {
        $user = auth()->user();

        $master = Administration::where('IsMaster', 1)
            ->where('IsManager', 1)
            ->where('IsSuperAdmin', 0)
            ->where('IsSeller', 0)
            ->where('IsSupervisor', 0)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $data = $request->validated();

        $updateData = [
            'Identifiant_email' => $data['Identifiant_email'],
            'Actived'           => $data['Actived'] ?? 1,
        ];

        if (!empty($data['password'])) {
            $updateData['Password_'] = sha1($data['password']);
        }

        $master->update($updateData);

        ActivityLogService::log(
            action: 'master.update',
            modelType: 'Administration',
            modelId: $master->ID,
            modelLabel: $master->Identifiant_email,
        );

        return redirect()->route('master.show')
            ->with('success', __('Master mis à jour avec succès.'));
    }

    /**
     * Désactiver le Master
     */
    public function destroy()
    {
        $user = auth()->user();

        $master = Administration::where('IsMaster', 1)
            ->where('IsManager', 1)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $master->update(['deleted' => 1, 'Actived' => 0]);

        ActivityLogService::log(
            action: 'master.deactivate',
            modelType: 'Administration',
            modelId: $master->ID,
            modelLabel: $master->Identifiant_email,
        );

        return redirect()->route('master.show')
            ->with('success', __('Master désactivé.'));
    }

    /**
     * Réactiver le Master
     */
    public function reset()
    {
        $user = auth()->user();

        $master = Administration::where('IsMaster', 1)
            ->where('IsManager', 1)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $master->update(['deleted' => 0, 'Actived' => 1]);

        ActivityLogService::log(
            action: 'master.reset',
            modelType: 'Administration',
            modelId: $master->ID,
            modelLabel: $master->Identifiant_email,
        );

        return redirect()->route('master.show')
            ->with('success', __('Master réactivé.'));
    }
}
