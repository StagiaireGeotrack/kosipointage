<?php
// app/Http/Controllers/SupervisorController.php

namespace App\Http\Controllers;

use App\Models\Administration;
use App\Models\Department;
use App\Models\SupervisorService;
use App\Http\Requests\SupervisorRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $supervisors = Administration::where('IsSupervisor', 1)
            ->where('IsSuperAdmin', 0)
            ->where('IsSeller', 0)
            ->where('IsManager', 0)
            ->where('SiegeID', $user->SiegeID)
            ->with('supervisorServices')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('supervisors.index', compact('supervisors'));
    }

    public function create()
    {
        $user = auth()->user();
        $services = Department::where('site_id', $user->SiegeID)
            ->orderBy('name')
            ->get();

        return view('supervisors.create', compact('services'));
    }

    public function store(SupervisorRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $supervisor = Administration::create([
                'Identifiant_email' => $data['Identifiant_email'],
                'Password_'         => sha1($data['password']),
                'IsSuperAdmin'      => 0,
                'IsSeller'          => 0,
                'IsManager'         => 0,
                'IsSupervisor'      => 1,
                'IsMaster'          => 0,
                'SiegeID'           => $user->SiegeID,
                'Actived'           => $data['Actived'] ?? 1,
                'deleted'           => 0,
            ]);

            foreach ($data['service_ids'] as $serviceId) {
                SupervisorService::create([
                    'admin_id'   => $supervisor->ID,
                    'service_id' => $serviceId,
                    'created_by' => $user->ID,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            ActivityLogService::log(
                action: 'supervisor.create',
                modelType: 'Administration',
                modelId: $supervisor->ID,
                modelLabel: $supervisor->Identifiant_email,
            );

            return redirect()->route('supervisors.index')
                ->with('success', __('Responsable de service créé avec succès.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Erreur : ') . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $user->SiegeID)
            ->with('supervisorServices')
            ->firstOrFail();

        return view('supervisors.show', compact('supervisor'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $user->SiegeID)
            ->with('supervisorServices')
            ->firstOrFail();

        $services = Department::where('site_id', $user->SiegeID)
            ->orderBy('name')
            ->get();

        $assignedServiceIds = $supervisor->supervisorServices->pluck('id')->toArray();

        return view('supervisors.edit', compact('supervisor', 'services', 'assignedServiceIds'));
    }

    public function update(SupervisorRequest $request, $id)
    {
        $user = auth()->user();
        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $data = $request->validated();

        DB::beginTransaction();
        try {
            $updateData = [
                'Identifiant_email' => $data['Identifiant_email'],
                'Actived'           => $data['Actived'] ?? 1,
            ];

            if (!empty($data['password'])) {
                $updateData['Password_'] = sha1($data['password']);
            }

            $supervisor->update($updateData);

            SupervisorService::where('admin_id', $supervisor->ID)->delete();

            foreach ($data['service_ids'] as $serviceId) {
                SupervisorService::create([
                    'admin_id'   => $supervisor->ID,
                    'service_id' => $serviceId,
                    'created_by' => $user->ID,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            ActivityLogService::log(
                action: 'supervisor.services.update',
                modelType: 'Administration',
                modelId: $supervisor->ID,
                modelLabel: $supervisor->Identifiant_email,
            );

            return redirect()->route('supervisors.index')
                ->with('success', __('Responsable de service mis à jour.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Erreur : ') . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $supervisor->update(['deleted' => 1, 'Actived' => 0]);

        ActivityLogService::log(
            action: 'supervisor.deactivate',
            modelType: 'Administration',
            modelId: $supervisor->ID,
            modelLabel: $supervisor->Identifiant_email,
        );

        return redirect()->back()->with('success', __('Responsable de service désactivé.'));
    }

    public function reset($id)
    {
        $user = auth()->user();
        $supervisor = Administration::where('ID', $id)
            ->where('IsSupervisor', 1)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        $supervisor->update(['deleted' => 0, 'Actived' => 1]);

        ActivityLogService::log(
            action: 'supervisor.reset',
            modelType: 'Administration',
            modelId: $supervisor->ID,
            modelLabel: $supervisor->Identifiant_email,
        );

        return redirect()->back()->with('success', __('Responsable de service réactivé.'));
    }
}