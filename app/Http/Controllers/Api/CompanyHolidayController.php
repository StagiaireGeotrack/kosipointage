<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyHoliday;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyHolidayController extends Controller
{
    /**
     * Obtenir la liste des jours fériés (filtrés par company_id si présent)
     */
    public function index(Request $request): JsonResponse
    {
        $query = CompanyHoliday::query();

        if ($request->has('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('date', 'asc')->get(),
        ]);
    }

    /**
     * Enregistrer un nouveau jour férié
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id'   => 'required|integer',
            'name'         => 'required|string|max:100',
            'date'         => 'required|date',
            'is_recurring' => 'nullable|boolean',
        ]);

        $holiday = CompanyHoliday::create([
            'company_id'    => $validated['company_id'],
            'name'          => $validated['name'],
            'date'          => $validated['date'],
            'is_recurring'  => $request->boolean('is_recurring'),
            'is_half_day'   => false,
            'half_day_type' => null,
            'is_active'     => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jour férié créé avec succès',
            'data'    => $holiday,
        ], 201);
    }

    /**
     * Supprimer un jour férié
     */
    public function destroy(int $id): JsonResponse
    {
        $holiday = CompanyHoliday::find($id);

        if (!$holiday) {
            return response()->json([
                'success' => false,
                'message' => 'Jour férié introuvable',
            ], 404);
        }

        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jour férié supprimé avec succès',
        ]);
    }
}