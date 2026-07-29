<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LeaveTypeController extends Controller
{
    /**
     * Obtenir la liste des types de congés (filtrés par company_id si présent)
     */
    public function index(Request $request): JsonResponse
    {
        $query = LeaveType::query();

        if ($request->has('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        return response()->json([
            'success' => true,
            'data' => $query->get(),
        ]);
    }

    /**
     * Enregistrer un nouveau type de congé
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id'             => 'required|integer',
            'code'                   => 'required|string|max:20',
            'name'                   => 'required|string|max:100',
            'unit'                   => 'required|in:days,hours',
            'deducts_balance'        => 'required|boolean',
            'allow_negative_balance' => 'nullable|boolean',
            'requires_attachment'    => 'required|in:never,always,from_duration',
            'color'                  => 'nullable|string|max:10',
        ]);

        // Définition des valeurs par défaut pour éviter les erreurs SQL NOT NULL
        $type = LeaveType::create([
            'company_id'             => $validated['company_id'],
            'code'                   => strtoupper($validated['code']),
            'name'                   => $validated['name'],
            'unit'                   => $validated['unit'],
            'deducts_balance'        => $validated['deducts_balance'],
            'allow_negative_balance' => $validated['allow_negative_balance'] ?? false,
            'negative_limit'         => 0,
            'requires_attachment'    => $validated['requires_attachment'],
            'attachment_threshold'   => 0,
            'approval_required'      => true,
            'color'                  => $validated['color'] ?? '#2196F3',
            'visibility_level'       => 'all',
            'is_active'              => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Type de congé créé avec succès',
            'data'    => $type,
        ], 201);
    }

    /**
     * Supprimer un type de congé
     */
    public function destroy(int $id): JsonResponse
    {
        $type = LeaveType::find($id);

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Type de congé introuvable',
            ], 404);
        }

        $type->delete();

        return response()->json([
            'success' => true,
            'message' => 'Type de congé supprimé avec succès',
        ]);
    }
}