<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Models\Department;
use App\Models\JobTitle;
use App\Models\HierarchyLevel;
use App\Http\Requests\EmployeRequest;
use App\Repositories\EmployeRepository;
use App\Services\ExportService;
use App\Services\HashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogService;

class EmployeController extends Controller
{
    protected $repository;
    protected $exportService;
    protected $hashService;

    public function __construct(
        EmployeRepository $repository,
        ExportService $exportService,
        HashService $hashService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
        $this->hashService = $hashService;
    }

    /* =========================================================
       INDEX
       ========================================================= */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup',
            'sort_by', 'sort_order'
        ]);

        $employes = $this->repository->getFiltered($filters);
        $employes->load(['meta', 'department', 'jobTitle', 'hierarchyLevel', 'manager']);
        $employes->appends($filters);

        $sieges = EntrepriseSiege::all();

        return view('employes.index', compact('employes', 'sieges', 'filters'));
    }

    /* =========================================================
       CREATE
       ========================================================= */
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        $admin_connected = auth()->user();
        $siege_id = $admin_connected->SiegeID ?? null;

        // Référentiels organisationnels
        $jobTitles = JobTitle::orderBy('name')->get();
        $hierarchyLevels = HierarchyLevel::orderBy('rank', 'desc')->get();

        // Si l'admin a un siège fixe, on pré-charge les départements et managers
        if ($siege_id) {
            $departments = Department::where('site_id', $siege_id)->orderBy('name')->get();
            $managers = Employe::where('SiegeID', $siege_id)
                ->where('Actived', 1)
                ->where('deleted', 0)
                ->orderBy('Nom')
                ->get();
        } else {
            $departments = collect();
            $managers = collect();
        }

        return view('employes.create', compact(
            'sieges', 'siege_id', 'departments', 'jobTitles', 'hierarchyLevels', 'managers'
        ));
    }

    /* =========================================================
       STORE
       ========================================================= */
    public function store(EmployeRequest $request)
    {
        $data = $request->validated();
        $siegeId = $data['SiegeID'];

        // --- Badge ID ---
        if (empty($data['BadgeID'])) {
            $data['BadgeID'] = $this->hashService->toHash(strtoupper(uniqid('EMP-')));
        } else {
            $hashedBadge = $this->hashService->toHash($data['BadgeID']);
            if (Employe::where('SiegeID', $siegeId)->where('BadgeID', $hashedBadge)->exists()) {
                return redirect()->back()
                    ->withErrors(['BadgeID' => 'Ce Badge ID est déjà utilisé dans ce siège.'])
                    ->withInput();
            }
            $data['BadgeID'] = $hashedBadge;
        }

        // --- PIN ---
        if (!empty($data['Pin'])) {
            $hashedPin = $this->hashService->toHash($data['Pin']);
            if (Employe::where('SiegeID', $siegeId)->where('Pin', $hashedPin)->whereNotNull('Pin')->exists()) {
                return redirect()->back()
                    ->withErrors(['Pin' => 'Ce code PIN est déjà utilisé dans ce siège.'])
                    ->withInput();
            }
            $data['Pin'] = $hashedPin;
        }

        // --- Photo / Face Encoding ---
        if ($request->hasFile('FaceEncodingFile')) {
            $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
            $data['HasFaceSetup'] = true;
        } else {
            $data['HasFaceSetup'] = false;
        }

        // --- Restrictions non-SuperAdmin ---
        if (!auth()->user()->IsSuperAdmin) {
            $data['Actived'] = "0";
            $data['HasBiometricSetup'] = false;
        }

        if (empty($data['num_mat'])) {
            $data['num_mat'] = null;
        }

        $data['CreatedAt'] = now();
        unset($data['FaceEncodingFile']);

        // ============================================
        // CHAMPS ORGANISATIONNELS
        // ============================================
        $data['company_id'] = $siegeId;
        $data['site_id'] = $siegeId;
        $data['department_id'] = $request->input('department_id') ?: null;
        $data['job_title_id'] = $request->input('job_title_id') ?: null;
        $data['hierarchy_level_id'] = $request->input('hierarchy_level_id') ?: null;
        $data['manager_id'] = $request->input('manager_id') ?: null;
        $data['employment_status'] = $request->input('employment_status', 'actif');
        $data['hire_date'] = $request->input('hire_date') ?: null;

        // Créer l'employé
        $employe = $this->repository->create($data);

        // Synchroniser employee_meta (compatibilité temporaire)
        \App\Models\EmployeeMeta::updateOrCreate(
            ['employee_id' => $employe->ID],
            [
                'hire_date'         => $data['hire_date'],
                'department_name'   => optional($employe->department)->name,
                'job_title'         => optional($employe->jobTitle)->name,
                'employment_status' => $data['employment_status'] === 'actif' ? 'active' : $data['employment_status'],
                'company_id'        => $siegeId,
            ]
        );

        return redirect()->back()->with('success', __('Employé créé avec succès'));
    }

    /* =========================================================
       SHOW
       ========================================================= */
    public function show($id)
    {
        $employe = $this->repository->findById($id);
        $employe->load(['meta', 'department', 'jobTitle', 'hierarchyLevel', 'manager']);
        $pointages = $employe->pointages()->latest('timestamp_')->paginate(5);
        $sieges = EntrepriseSiege::all();

        return view('employes.show', compact('employe', 'pointages', 'sieges'));
    }

    /* =========================================================
       EDIT
       ========================================================= */
    public function edit($id)
    {
        $employe = $this->repository->findById($id);
        $employe->load(['meta', 'department', 'jobTitle', 'hierarchyLevel', 'manager']);

        $sieges = EntrepriseSiege::all();

        // Référentiels organisationnels pour le siège de l'employé
        $jobTitles = JobTitle::orderBy('name')->get();
        $hierarchyLevels = HierarchyLevel::orderBy('rank', 'desc')->get();
        $departments = Department::where('site_id', $employe->SiegeID)->orderBy('name')->get();
        $managers = Employe::where('SiegeID', $employe->SiegeID)
            ->where('ID', '!=', $employe->ID)
            ->where('Actived', 1)
            ->where('deleted', 0)
            ->orderBy('Nom')
            ->get();

        $user = auth()->user();
        if ($user->isSeller()) {
            return $this->show($id);
        }

        return view('employes.edit', compact(
            'employe', 'sieges', 'departments', 'jobTitles', 'hierarchyLevels', 'managers'
        ));
    }

    /* =========================================================
       UPDATE
       ========================================================= */
    public function update(EmployeRequest $request, $id)
    {
        // --- Récupérer les valeurs organisationnelles AVANT la réassignation de $data ---
        $orgData = [
            'department_id'      => $request->input('department_id') ?: null,
            'job_title_id'       => $request->input('job_title_id') ?: null,
            'hierarchy_level_id' => $request->input('hierarchy_level_id') ?: null,
            'manager_id'         => $request->input('manager_id') ?: null,
            'employment_status'  => $request->input('employment_status', 'actif'),
            'hire_date'          => $request->input('hire_date') ?: null,
        ];

        $data = $request->validated();
        $employe = Employe::findOrFail($id);

        // ==================================================
        // NON SUPER ADMIN : champs très limités
        // ==================================================
        if (!auth()->user()->IsSuperAdmin) {
            $newPin = $data['Pin'] ?? null;

            if (!empty($newPin)) {
                $hashedPin = $this->hashService->toHash($newPin);
                if (Employe::where('SiegeID', $employe->SiegeID)
                        ->where('Pin', $hashedPin)
                        ->where('ID', '!=', $id)
                        ->whereNotNull('Pin')
                        ->exists()) {
                    return redirect()->back()
                        ->withErrors(['Pin' => 'Ce code PIN est déjà utilisé dans ce siège.'])
                        ->withInput();
                }
                $newPin = $hashedPin;
            } else {
                $newPin = $employe->Pin;
            }

            $data = [
                'Nom'               => $data['Nom'],
                'num_mat'           => !empty($data['num_mat']) ? $data['num_mat'] : $employe->num_mat,
                'Pin'               => $newPin,
                'SiegeID'           => $employe->SiegeID,
                'BadgeID'           => $employe->BadgeID,
                'HasBiometricSetup' => $employe->HasBiometricSetup,
                'HasFaceSetup'      => $employe->HasFaceSetup,
                'FaceEncodingPath'  => $employe->FaceEncodingPath,
                'Actived'           => $employe->Actived,
            ];
        }
        // ==================================================
        // SUPER ADMIN : peut tout modifier
        // ==================================================
        else {
            // Badge
            if (empty($data['BadgeID'])) {
                $data['BadgeID'] = $employe->BadgeID;
            } else {
                $hashedBadge = $this->hashService->toHash($data['BadgeID']);
                if (Employe::where('SiegeID', $employe->SiegeID)
                        ->where('BadgeID', $hashedBadge)
                        ->where('ID', '!=', $id)
                        ->exists()) {
                    return redirect()->back()
                        ->withErrors(['BadgeID' => 'Ce Badge ID est déjà utilisé dans ce siège.'])
                        ->withInput();
                }
                $data['BadgeID'] = $hashedBadge;
            }

            // PIN
            if (!empty($data['Pin'])) {
                $hashedPin = $this->hashService->toHash($data['Pin']);
                if (Employe::where('SiegeID', $employe->SiegeID)
                        ->where('Pin', $hashedPin)
                        ->where('ID', '!=', $id)
                        ->whereNotNull('Pin')
                        ->exists()) {
                    return redirect()->back()
                        ->withErrors(['Pin' => 'Ce code PIN est déjà utilisé dans ce siège.'])
                        ->withInput();
                }
                $data['Pin'] = $hashedPin;
            } else {
                $data['Pin'] = $employe->Pin;
            }

            // Matricule
            if (empty($data['num_mat'])) {
                $data['num_mat'] = $employe->num_mat;
            }

            // Photo
            if ($request->hasFile('FaceEncodingFile')) {
                $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
                $data['HasFaceSetup'] = true;
            } else {
                unset($data['FaceEncodingPath'], $data['HasFaceSetup']);
            }
        }

        unset($data['FaceEncodingFile']);

        // ============================================
        // FUSIONNER les champs organisationnels (SuperAdmin uniquement)
        // ============================================
        if (auth()->user()->IsSuperAdmin) {
            $data = array_merge($data, $orgData);
            $data['company_id'] = $employe->SiegeID;
            $data['site_id'] = $employe->SiegeID;
        }

        $this->repository->update($id, $data);

        // Synchroniser employee_meta (compatibilité temporaire)
        \App\Models\EmployeeMeta::updateOrCreate(
            ['employee_id' => $employe->ID],
            [
                'hire_date'         => $orgData['hire_date'],
                'department_name'   => optional(Department::find($orgData['department_id']))->name,
                'job_title'         => optional(JobTitle::find($orgData['job_title_id']))->name,
                'employment_status' => $orgData['employment_status'] === 'actif' ? 'active' : $orgData['employment_status'],
                'company_id'        => $employe->SiegeID,
            ]
        );

        return redirect()->back()->with('success', __('Employé modifié avec succès'));
    }

    /* =========================================================
       DESTROY / RESET / AUTRES (inchangés)
       ========================================================= */
    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $employe = $this->repository->findById($id);
        $employe->deleted = true;
        $employe->Actived = false;
        $employe->save();

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Entreprise',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Employé supprimé avec succès'));
    }

    public function reset($id)
    {
        $user = auth()->user();
        if (!$user->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $employe = $this->repository->findById($id);
        $employe->deleted = false;
        $employe->Actived = true;
        $employe->save();

        ActivityLogService::log(
            action: 'reset',
            modelType: 'Entreprise',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Employé restauré avec succès'));
    }

    public function resetCodePin($id)
    {
        $employe = $this->repository->findById($id);
        $employe->Pin = null;
        $employe->save();

        return redirect()->back()->with('success', __('Code Pin réinitialisé avec succès'));
    }

    public function assignWebAccess(Request $request, $id)
    {
        $request->validate([
            'email'    => 'required|email|max:255|unique:Employes,email,' . $id . ',ID',
            'password' => 'nullable|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email'    => 'L\'adresse email doit être valide.',
            'email.unique'   => 'Cette adresse email est déjà utilisée par un autre employé.',
            'password.min'   => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        $employe = Employe::findOrFail($id);
        $employe->email = $request->email;

        if ($request->filled('password')) {
            $employe->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $employe->save();

        ActivityLogService::log(
            action: 'assign_web_access',
            modelType: 'Employe',
            modelId: (int) $id,
        );

        return redirect()->back()->with('success', __('Accès Web assigné avec succès à ' . $employe->Nom));
    }

    /* =========================================================
       EXPORT
       ========================================================= */
    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);

        $employes = $this->repository->getAllForExport($filters);

        ActivityLogService::log(action: 'export_excel', modelType: 'Employe');
        return $this->exportService->exportToExcel($employes, __('Employés'));
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);

        $employes = $this->repository->getAllForExport($filters);

        ActivityLogService::log(action: 'export_pdf', modelType: 'Employe');
        return $this->exportService->exportToPdf($employes, "Liste des employés", 'exports.generic');
    }

    /* =========================================================
       PHOTO / FACE ENCODING
       ========================================================= */
    public function getFaceEncoding($id)
    {
        $employe = Employe::findOrFail($id);

        if (!$employe->FaceEncodingPath) {
            $message = "Photo de visage non trouvée";
            return view('404', compact('message'));
        }

        $imageData = $this->decodeBase64($employe->FaceEncodingPath);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        return response($imageData)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function getFaceThumbnail($id)
    {
        $employe = Employe::findOrFail($id);

        if (!$employe->FaceEncodingPath) {
            $message = "Photo de visage non trouvée";
            return view('404', compact('message'));
        }

        // Si vous avez une logique de thumbnail, ajoutez-la ici
        return $this->getFaceEncoding($id);
    }

    /* =========================================================
       HELPERS PRIVÉS
       ========================================================= */
    private function optimizeAndConvertToBase64($file)
    {
        // Votre logique existante de compression + base64
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        if (!$image) {
            throw new \Exception("Image invalide");
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $maxDim = 400;

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        ob_start();
        imagejpeg($image, null, 75);
        $data = ob_get_clean();
        imagedestroy($image);

        return base64_encode($data);
    }

    private function decodeBase64($base64String)
    {
        if (str_contains($base64String, ',')) {
            $base64String = explode(',', $base64String)[1];
        }
        return base64_decode($base64String);
    }
}