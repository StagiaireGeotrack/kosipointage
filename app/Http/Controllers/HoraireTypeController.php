<?php

namespace App\Http\Controllers;

use App\Models\HoraireType;
use App\Models\JobTitle;
use App\Models\Department;
use Illuminate\Http\Request;

class HoraireTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = HoraireType::with(['poste.department', 'createur']);

        if ($request->filled('service_id')) {
            $query->whereHas('poste.department', function($q) use ($request) {
                $q->where('id', $request->service_id);
            });
        }

        if ($request->filled('poste_id')) {
            $query->where('poste_id', $request->poste_id);
        }

        $horaires = $query->orderBy('poste_id')->paginate(15);

        $services = Department::all();
        $postes = JobTitle::all();

        return view('planning.horaires-types.index', compact('horaires', 'services', 'postes'));
    }

    public function create()
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $services = Department::where('site_id', $siegeId)->orderBy('name')->get();
        $postes = JobTitle::where('company_id', $siegeId)->orderBy('name')->get();

        // ✅ Liste des sites (Entreprises) accessibles
        if (auth()->user()->isTrueSuperAdmin()) {
            $sites = \App\Models\Entreprise::orderBy('Nom')->get();
        } else {
            $sites = \App\Models\Entreprise::where('SiegeID', $siegeId)->orderBy('Nom')->get();
        }

        return view('planning.horaires-types.create', compact('services', 'postes', 'sites'));
    }

    public function store(Request $request)
    {
        // ✅ Nettoyer les champs vides
        $data = $request->all();
        $timeFields = ['pause_debut', 'pause_fin', 'deuxieme_debut', 'deuxieme_fin'];
        foreach ($timeFields as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                unset($data[$field]);
            }
        }
        $request->replace($data);

        $request->validate([
            'service_id' => 'required|exists:departments,id',
            'poste_id' => 'required|exists:job_titles,id',
            'site_id' => 'nullable|exists:Entreprises,ID',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'pause_debut' => 'nullable|date_format:H:i',
            'pause_fin' => 'nullable|date_format:H:i|after:pause_debut',
            'deuxieme_debut' => 'nullable|date_format:H:i',
            'deuxieme_fin' => 'nullable|date_format:H:i|after:deuxieme_debut',
            'jours_travailles' => 'required|array|min:1',
            'jours_travailles.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'par_defaut' => 'nullable|boolean',
        ]);

        $validated = $request->all();
        $validated['par_defaut'] = $request->has('par_defaut');

        $poste = JobTitle::find($validated['poste_id']);
        if (!$poste || $poste->department_id != $validated['service_id']) {
            return redirect()->back()
                ->withErrors(['service_id' => 'Ce poste n\'appartient pas à ce service.'])
                ->withInput();
        }

        $joursTravailles = implode(',', $validated['jours_travailles']);

        HoraireType::create([
            'poste_id' => $validated['poste_id'],
            'site_id' => $validated['site_id'] ?? null,
            'jours_travailles' => $joursTravailles,
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
            'pause_debut' => $validated['pause_debut'] ?? null,
            'pause_fin' => $validated['pause_fin'] ?? null,
            'deuxieme_debut' => $validated['deuxieme_debut'] ?? null,
            'deuxieme_fin' => $validated['deuxieme_fin'] ?? null,
            'par_defaut' => $validated['par_defaut'],
            'cree_par' => auth()->user()->ID,
        ]);

        return redirect()->route('planning.horaires-types.index')
            ->with('success', 'Période de travail créée avec succès !');
    }

    public function edit($id)
    {
        $siegeId = session('admin_selected_siege_id') ?? auth()->user()->SiegeID;

        $horaire = HoraireType::with('poste.department', 'site')->findOrFail($id);
        $services = Department::where('site_id', $siegeId)->orderBy('name')->get();
        $postes = JobTitle::where('company_id', $siegeId)->orderBy('name')->get();

        if (auth()->user()->isTrueSuperAdmin()) {
            $sites = \App\Models\Entreprise::orderBy('Nom')->get();
        } else {
            $sites = \App\Models\Entreprise::where('SiegeID', $siegeId)->orderBy('Nom')->get();
        }

        return view('planning.horaires-types.edit', compact('horaire', 'services', 'postes', 'sites'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_id' => 'required|exists:departments,id',
            'poste_id' => 'required|exists:job_titles,id',
            'site_id' => 'nullable|exists:Entreprises,ID',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'pause_debut' => 'nullable|date_format:H:i',
            'pause_fin' => 'nullable|date_format:H:i|after:pause_debut',
            'deuxieme_debut' => 'nullable|date_format:H:i',
            'deuxieme_fin' => 'nullable|date_format:H:i|after:deuxieme_debut',
            'jours_travailles' => 'required|array|min:1',
            'jours_travailles.*' => 'in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
            'par_defaut' => 'nullable|boolean',
        ]);

        if (empty($request->heure_debut) || empty($request->heure_fin)) {
            return redirect()->back()
                ->withErrors(['heure_debut' => 'Les heures de début et de fin sont obligatoires.'])
                ->withInput();
        }

        $poste = JobTitle::find($request->poste_id);
        if (!$poste || $poste->department_id != $request->service_id) {
            return redirect()->back()
                ->withErrors(['service_id' => 'Ce poste n\'appartient pas à ce service.'])
                ->withInput();
        }

        $horaire = HoraireType::findOrFail($id);

        $joursTravailles = implode(',', $request->jours_travailles);

        // ✅ S'assurer que les champs vides sont convertis en null
        $pause_debut = $request->pause_debut ?: null;
        $pause_fin = $request->pause_fin ?: null;
        $deuxieme_debut = $request->deuxieme_debut ?: null;
        $deuxieme_fin = $request->deuxieme_fin ?: null;

        $horaire->update([
            'poste_id' => $request->poste_id,
            'site_id' => $request->site_id ?? null,
            'jours_travailles' => $joursTravailles,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'pause_debut' => $pause_debut,
            'pause_fin' => $pause_fin,
            'deuxieme_debut' => $deuxieme_debut,
            'deuxieme_fin' => $deuxieme_fin,
            'par_defaut' => $request->has('par_defaut'),
        ]);

        return redirect()->route('planning.horaires-types.index')
            ->with('success', 'Période de travail mise à jour avec succès !');
    }

    public function destroy($id)
    {
        $horaire = HoraireType::findOrFail($id);
        $horaire->delete();

        return redirect()->route('planning.horaires-types.index')
            ->with('success', 'Période de travail supprimée avec succès.');
    }
}
