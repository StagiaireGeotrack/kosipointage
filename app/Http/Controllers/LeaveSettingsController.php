<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
// Importez vos modèles LeaveType et CompanyHoliday (ajustez le namespace si nécessaire)
use App\Models\LeaveType; 
use App\Models\CompanyHoliday; 

class LeaveSettingsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tous les sièges
        $sieges = EntrepriseSiege::all();

        // 2. Siège sélectionné
        $selectedSiegeId = $request->get('SiegeID', $sieges->first()->id ?? null);

        // 3. Récupération des types de congés pour ce siège (ou tous si globaux)
        // Remarque : adaptez la requête selon votre relation (ex: where('siege_id', $selectedSiegeId))
        $leaveTypes = LeaveType::when($selectedSiegeId, function ($query) use ($selectedSiegeId) {
            return $query->where('siege_id', $selectedSiegeId);
        })->get();

        // 4. Récupération des jours fériés
        $holidays = CompanyHoliday::when($selectedSiegeId, function ($query) use ($selectedSiegeId) {
            return $query->where('siege_id', $selectedSiegeId);
        })->get();

        // 5. Transmission de toutes les variables nécessaires à la vue
        return view('conges.settings', compact('sieges', 'selectedSiegeId', 'leaveTypes', 'holidays'));
    }
}