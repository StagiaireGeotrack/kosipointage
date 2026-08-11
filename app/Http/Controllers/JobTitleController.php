<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    public function index()
    {
        $jobTitles = JobTitle::orderBy('name')->paginate(20);
        return view('job_titles.index', compact('jobTitles'));
    }

    public function create()
    {
        return view('job_titles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
        ]);

        JobTitle::create($validated + ['company_id' => 0]);

        return redirect()->route('job-titles.index')->with('success', 'Poste créé.');
    }

    public function edit(JobTitle $jobTitle)
    {
        return view('job_titles.edit', compact('jobTitle'));
    }

    public function update(Request $request, JobTitle $jobTitle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20',
        ]);

        $jobTitle->update($validated);

        return redirect()->route('job-titles.index')->with('success', 'Poste mis à jour.');
    }

    public function destroy(JobTitle $jobTitle)
    {
        if ($jobTitle->employes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des employés ont ce poste.');
        }

        $jobTitle->delete();
        return redirect()->route('job-titles.index')->with('success', 'Poste supprimé.');
    }
}