<?php

namespace App\Http\Controllers;

use App\Models\PlanningDetail;
use App\Models\EvenementPlanning;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployePlanningController extends Controller
{
    public function index()
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;

        $planningDetails = PlanningDetail::with(['planning', 'planning.service', 'planning.poste'])
            ->where('employe_id', $employeId)
            ->whereBetween('date', [
                Carbon::now()->startOfWeek()->format('Y-m-d'),
                Carbon::now()->endOfWeek()->format('Y-m-d')
            ])
            ->orderBy('date')
            ->get();

        return view('planning.employe', compact('planningDetails', 'employe'));
    }

    public function getEvents(Request $request)
    {
        $employe = auth()->guard('employe')->user();
        $employeId = $employe->ID;
        $start = $request->input('start');
        $end = $request->input('end');

        $events = [];

        // Plannings de l'employé
        $planningDetails = PlanningDetail::with(['planning', 'employe'])
            ->where('employe_id', $employeId)
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($planningDetails as $detail) {
            $events[] = [
                'id' => 'detail_' . $detail->id,
                'title' => 'Travail - ' . $detail->heure_debut . '-' . $detail->heure_fin,
                'start' => $detail->date . 'T' . $detail->heure_debut,
                'end' => $detail->date . 'T' . $detail->heure_fin,
                'backgroundColor' => $detail->couleur_statut,
                'borderColor' => $detail->couleur_statut,
                'extendedProps' => [
                    'type' => 'planning',
                    'statut' => $detail->statut,
                    'commentaire' => $detail->commentaire,
                ]
            ];
        }

        // Événements concernant l'employé
        $evenements = EvenementPlanning::whereHas('employes', function ($q) use ($employeId) {
            $q->where('employe_id', $employeId);
        })
        ->whereBetween('debut', [$start, $end])
        ->get();

        foreach ($evenements as $evenement) {
            $events[] = [
                'id' => 'event_' . $evenement->id,
                'title' => '📌 ' . $evenement->titre,
                'start' => $evenement->debut->format('Y-m-d\TH:i:s'),
                'end' => $evenement->fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $evenement->couleur_evenement,
                'borderColor' => $evenement->couleur_evenement,
                'extendedProps' => [
                    'type' => 'event',
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'type_event' => $evenement->type,
                ]
            ];
        }

        return response()->json($events);
    }
}
