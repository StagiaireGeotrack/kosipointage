<?php

namespace App\Http\Controllers;

use App\Models\Administration;
use App\Models\Department;
use App\Models\SupervisorService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    /**
     * Vérifie que l'utilisateur connecté est bien l'admin du siège (IsManager=1)
     * et retourne l'utilisateur.
     */
    private function ensureManager()
    {
        $user = auth()->user();

        if (
            !$user
            || !($user instanceof Administration)
            || $user->IsSuperAdmin == 1
            || $user->IsManager != 1
            || !$user->SiegeID
        ) {
            abort(403, 'Accès réservé au manager du siège.');
        }

        return $user;
    }

    /* =========================================================
       INDEX
       ========================================================= */
    public function index()
    {
        $manager = $this->ensureManager();

        // Tous les responsables du siège du manager
        $supervisors = Administration::where('IsSupervisor', 1)
            ->where('SiegeID', $manager->SiegeID)
            ->where('deleted', 0)
            ->orderBy('Identifiant_email')
            ->get();

        // Charger les services associés pour chaque responsable
        foreach ($supervisors as $sup) {
            $serviceIds = SupervisorService::where('admin_id', $sup->ID)->pluck('service_id');

            $sup->services = Department::withoutGlobalScope('site')
                ->whereIn('id', $serviceIds)
                ->orderBy('name')
                ->get();
        }

        return view('supervisors.index', compact('supervisors'));
    }

    /* =========================================================
       CREATE
       ========================================================= */
    public function create()
    {
        $manager = $this->ensureManager();

        // Services disponibles dans le siège du manager
        $services = Department::withoutGlobalScope('site')
            ->where('site_id', $manager->SiegeID)
            ->orderBy('name')
            ->get();

        return view('supervisors.create', compact('services'));
    }

    /* =========================================================
       STORE
       ========================================================= */
    public function store(Request $request)
    {
        $manager = $this->ensureManager();

        $request->validate([
            'Identifiant_email' => 'required|email|max:255|unique:administration,Identifiant_email',
            'password'          => 'required|min:6',
            'services'          => 'required|array|min:1',
            'services.*'        => 'integer|exists:departments,id',
        ], [
            'services.required' => 'Veuillez sélectionner au moins un service.',
            'services.min'      => 'Veuillez sélectionner au moins un service.',
        ]);

        // Vérifier que chaque service appartient bien au siège du manager
        foreach ($request->services as $serviceId) {
            $dept = Department::withoutGlobalScope('site')->find($serviceId);
            if (!$dept || (int) $dept->site_id !== (int) $manager->SiegeID) {
                return back()
                    ->withErrors(['services' => 'Un des services sélectionnés n\'appartient pas à votre siège.'])
                    ->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $supervisor = Administration::create([
                'Identifiant_email' => $request->Identifiant_email,
                'Password_'         => sha1($request->password), // SHA1 selon la convention existante
                'IsSuperAdmin'      => 0,
                'IsSeller'          => 0,
                'IsManager'         => 0,
                'IsSupervisor'      => 1,
                'SiegeID'           => $manager->SiegeID,
                'Actived'           => 1,
                'deleted'           => 0,
            ]);

            foreach ($request->services as $serviceId) {
                SupervisorService::create([
                    'admin_id'   => $supervisor->ID,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                ]);
            }

            ActivityLogService::log(
                action: 'create',
                modelType: 'Supervisor',
                modelId: (int) $supervisor->ID,
            );

            DB::commit();

            return redirect()
                ->route('supervisors.index')
                ->with('success', 'Responsable de service créé avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /* =========================================================
       EDIT
       ========================================================= */
    public function edit($id)
    {
        $manager = $this->ensureManager();

        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $manager->SiegeID)
            ->firstOrFail();

        $serviceIds = SupervisorService::where('admin_id', $supervisor->ID)
            ->pluck('service_id')
            ->toArray();

        $services = Department::withoutGlobalScope('site')
            ->where('site_id', $manager->SiegeID)
            ->orderBy('name')
            ->get();

        return view('supervisors.edit', compact('supervisor', 'services', 'serviceIds'));
    }

    /* =========================================================
       UPDATE
       ========================================================= */
    public function update(Request $request, $id)
    {
        $manager = $this->ensureManager();

        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $manager->SiegeID)
            ->firstOrFail();

        $request->validate([
            'Identifiant_email' => 'required|email|max:255|unique:administration,Identifiant_email,' . $id . ',ID',
            'password'          => 'nullable|min:6',
            'services'          => 'required|array|min:1',
            'services.*'        => 'integer|exists:departments,id',
        ]);

        // Vérification : service = même siège
        foreach ($request->services as $serviceId) {
            $dept = Department::withoutGlobalScope('site')->find($serviceId);
            if (!$dept || (int) $dept->site_id !== (int) $manager->SiegeID) {
                return back()
                    ->withErrors(['services' => 'Un des services sélectionnés n\'appartient pas à votre siège.'])
                    ->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $data = ['Identifiant_email' => $request->Identifiant_email];
            if ($request->filled('password')) {
                $data['Password_'] = sha1($request->password);
            }
            $supervisor->update($data);

            // Remplacer les affectations
            SupervisorService::where('admin_id', $supervisor->ID)->delete();

            foreach ($request->services as $serviceId) {
                SupervisorService::create([
                    'admin_id'   => $supervisor->ID,
                    'service_id' => $serviceId,
                    'created_at' => now(),
                ]);
            }

            ActivityLogService::log(
                action: 'update',
                modelType: 'Supervisor',
                modelId: (int) $supervisor->ID,
            );

            DB::commit();

            return redirect()
                ->route('supervisors.index')
                ->with('success', 'Responsable de service modifié avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /* =========================================================
       TOGGLE ACTIVE
       ========================================================= */
    public function toggleActive($id)
    {
        $manager = $this->ensureManager();

        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $manager->SiegeID)
            ->firstOrFail();

        $supervisor->Actived = $supervisor->Actived ? 0 : 1;
        $supervisor->save();

        ActivityLogService::log(
            action: 'toggle_active',
            modelType: 'Supervisor',
            modelId: (int) $supervisor->ID,
        );

        return redirect()
            ->route('supervisors.index')
            ->with('success', 'Statut du responsable modifié.');
    }

    /* =========================================================
       DESTROY (soft delete)
       ========================================================= */
    public function destroy($id)
    {
        $manager = $this->ensureManager();

        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $manager->SiegeID)
            ->firstOrFail();

        $supervisor->deleted = 1;
        $supervisor->Actived = 0;
        $supervisor->save();

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Supervisor',
            modelId: (int) $supervisor->ID,
        );

        return redirect()
            ->route('supervisors.index')
            ->with('success', 'Responsable de service désactivé.');
    }
}