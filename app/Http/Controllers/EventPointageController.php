<?php
// app/Http/Controllers/EventPointageController.php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\EntrepriseSiege;
use App\Models\PointageEventException;
use App\Services\EventDetectionService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class EventPointageController extends Controller
{
    public function __construct(private EventDetectionService $detector) {}

    public function index(Request $request)
    {
        $user = auth()->user();

        // Super Admin → lecture seule, tous les sièges avec filtre optionnel
        if ($user->isTrueSuperAdmin()) {
            $sieges  = EntrepriseSiege::all();
            $siegeId = $request->input('SiegeID') ? (int) $request->input('SiegeID') : null;

            if ($siegeId) {
                $erreurs = $this->detector->detect($siegeId);
            } else {
                $erreurs = collect();
                foreach ($sieges as $siege) {
                    $erreurs = $erreurs->merge($this->detector->detect($siege->ID));
                }
            }

            return view('evenements.index', [
                'erreurs'    => $erreurs,
                'sieges'     => $sieges,
                'siegeId'    => $siegeId,
                'readOnly'   => true,
                'totalCount' => $erreurs->count(),
                'filterType' => $request->input('filter_type'),
                'sites'      => collect(),
            ]);
        }

        // Simple Admin → correction possible, son siège uniquement
        if ($user->isSimpleAdmin()) {
            $erreurs = $this->detector->detect($user->SiegeID);
            $sites   = Entreprise::where('SiegeID', $user->SiegeID)->where('Actived', 1)->get();

            return view('evenements.index', [
                'erreurs'    => $erreurs,
                'sieges'     => collect(),
                'siegeId'    => $user->SiegeID,
                'readOnly'   => false,
                'totalCount' => $erreurs->count(),
                'filterType' => $request->input('filter_type'),
                'sites'      => $sites,
            ]);
        }

        abort(403, 'Accès non autorisé');
    }

    public function acknowledge(Request $request)
    {
        $user = auth()->user();

        if (!$user->isSimpleAdmin()) {
            abort(403, 'Réservé aux administrateurs simples');
        }

        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:Employes,ID',
            'date'        => 'required|date',
            'error_type'  => 'required|in:doublon_entree,doublon_sortie,manque_sortie,manque_entree,pointage_jour_ferie,pointage_weekend',
            'note'        => 'nullable|string|max:500',
        ]);

        PointageEventException::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date'        => $validated['date'],
                'error_type'  => $validated['error_type'],
            ],
            [
                'SiegeID'         => $user->SiegeID,
                'acknowledged_by' => $user->ID,
                'acknowledged_at' => now(),
                'note'            => $validated['note'] ?? null,
            ]
        );

        ActivityLogService::log(
            action: 'acknowledge',
            modelType: 'PointageEvent',
            modelLabel: "{$validated['error_type']} - employé #{$validated['employee_id']} - {$validated['date']}",
        );

        return redirect()->back()->with('success', __('Événement marqué comme intentionnel.'));
    }

    public function removeAcknowledge(int $id)
    {
        $user = auth()->user();

        if (!$user->isSimpleAdmin()) {
            abort(403, 'Réservé aux administrateurs simples');
        }

        $exception = PointageEventException::where('id', $id)
            ->where('SiegeID', $user->SiegeID)
            ->firstOrFail();

        ActivityLogService::log(
            action: 'remove_acknowledge',
            modelType: 'PointageEvent',
            modelId: $exception->id,
            modelLabel: "{$exception->error_type} - employé #{$exception->employee_id} - {$exception->date}",
        );

        $exception->delete();

        return redirect()->back()->with('success', __('Acknowledgment supprimé. L\'événement est de nouveau visible.'));
    }
}
