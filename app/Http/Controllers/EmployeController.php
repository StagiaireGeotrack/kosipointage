<?php
// app/Http/Controllers/EmployeController.php

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
use App\Traits\EmployeeAccessTrait;

class EmployeController extends Controller
{
    use EmployeeAccessTrait;

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

        // Récupérer les IDs accessibles pour l'utilisateur connecté
        $user = auth()->user();
        $accessibleIds = $this->getAccessibleEmployeeIds($user);

        // Passer ces IDs au repository
        $employes = $this->repository->getFiltered($filters, 5, $accessibleIds);
        $employes->load(['department', 'jobTitle', 'hierarchyLevel', 'manager', 'siege']);
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

        $jobTitles = JobTitle::orderBy('name')->get();
        $hierarchyLevels = HierarchyLevel::orderBy('rank', 'desc')->get();

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
            $data['Actived'] = 0;
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

        return redirect()->route('employes.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /* =========================================================
       SHOW
       ========================================================= */
    public function show($id)
    {
        $this->ensureAccessible($id);
        $employe = $this->repository->findById($id);
        $employe->load(['department', 'jobTitle', 'hierarchyLevel', 'manager', 'siege']);
        $pointages = $employe->pointages()->latest('timestamp_')->paginate(5);
        $sieges = EntrepriseSiege::all();

        return view('employes.show', compact('employe', 'pointages', 'sieges'));
    }

    /* =========================================================
       EDIT
       ========================================================= */
    public function edit($id)
    {
        $this->ensureAccessible($id);
        $employe = $this->repository->findById($id);
        $employe->load(['department', 'jobTitle', 'hierarchyLevel', 'manager', 'siege']);

        $sieges = EntrepriseSiege::all();

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

    public function getJobTitlesByDepartment(Request $request)
    {
        $departmentId = $request->input('department_id');
        $user = auth()->user();

        $query = JobTitle::query();

        if ($departmentId) {
            $department = Department::find($departmentId);
            if ($department && ($user->IsSuperAdmin || $department->site_id == $user->SiegeID)) {
                $query->where('department_id', $departmentId);
            } else {
                return response()->json([]);
            }
        } else {
            if (!$user->IsSuperAdmin && $user->SiegeID) {
                $query->whereHas('department', function($q) use ($user) {
                    $q->where('site_id', $user->SiegeID);
                })->orWhereNull('department_id');
            }
        }

        $jobTitles = $query->orderBy('name')->get(['id', 'name']);
        return response()->json($jobTitles);
    }

    /* =========================================================
       UPDATE - CORRIGÉ
       ========================================================= */
    public function update(EmployeRequest $request, $id)
    {
        $this->ensureAccessible($id);
        $data = $request->validated();
        $employe = Employe::findOrFail($id);

        // ==================================================
        // NON SUPER ADMIN : champs limités mais MANAGER MODIFIABLE
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
                'Nom' => $data['Nom'],
                'num_mat' => !empty($data['num_mat']) ? $data['num_mat'] : $employe->num_mat,
                'Pin' => $newPin,
                'SiegeID' => $employe->SiegeID,
                'BadgeID' => $employe->BadgeID,
                'HasBiometricSetup' => $employe->HasBiometricSetup,
                'HasFaceSetup' => $employe->HasFaceSetup,
                'FaceEncodingPath' => $employe->FaceEncodingPath,
                'Actived' => $employe->Actived,
                // Organisation
                'department_id' => $request->input('department_id') ?: null,
                'job_title_id' => $request->input('job_title_id') ?: null,
                'hierarchy_level_id' => $request->input('hierarchy_level_id') ?: null,
                'manager_id' => $request->input('manager_id') ?: null,
                'employment_status' => $request->input('employment_status', 'actif'),
                'hire_date' => $request->input('hire_date') ?: null,
                'company_id' => $employe->company_id,
                'site_id' => $employe->site_id,
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

            // Organisation
            $data['department_id'] = $request->input('department_id') ?: null;
            $data['job_title_id'] = $request->input('job_title_id') ?: null;
            $data['hierarchy_level_id'] = $request->input('hierarchy_level_id') ?: null;
            $data['manager_id'] = $request->input('manager_id') ?: null;
            $data['employment_status'] = $request->input('employment_status', 'actif');
            $data['hire_date'] = $request->input('hire_date') ?: null;
            $data['company_id'] = $employe->SiegeID;
            $data['site_id'] = $employe->SiegeID;
        }

        unset($data['FaceEncodingFile']);

        $this->repository->update($id, $data);

        return redirect()->route('employes.index')
            ->with('success', 'Employé modifié avec succès.');
    }

    /* =========================================================
       DESTROY
       ========================================================= */
    public function destroy($id)
    {
        $this->ensureAccessible($id);
        $user = auth()->user();
        if (!$user->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette fonctionnalité.');
        }

        $employe = $this->repository->findById($id);
        $employe->deleted = true;
        $employe->Actived = false;
        $employe->save();

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Employe',
            modelId: (int) $id,
        );

        return redirect()->route('employes.index')
            ->with('success', 'Employé supprimé avec succès.');
    }

    /* =========================================================
       RESET
       ========================================================= */
    public function reset($id)
    {
        $this->ensureAccessible($id);
        $user = auth()->user();
        if (!$user->isTrueSuperAdmin()) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à cette fonctionnalité.');
        }

        $employe = $this->repository->findById($id);
        $employe->deleted = false;
        $employe->Actived = true;
        $employe->save();

        ActivityLogService::log(
            action: 'reset',
            modelType: 'Employe',
            modelId: (int) $id,
        );

        return redirect()->route('employes.index')
            ->with('success', 'Employé restauré avec succès.');
    }

    /* =========================================================
       RESET PIN
       ========================================================= */
    public function resetCodePin($id)
    {
        $this->ensureAccessible($id);
        $employe = $this->repository->findById($id);
        $employe->Pin = null;
        $employe->save();

        return redirect()->back()->with('success', 'Code PIN réinitialisé avec succès.');
    }

    /* =========================================================
       ASSIGN WEB ACCESS
       ========================================================= */
    public function assignWebAccess(Request $request, $id)
    {
        $this->ensureAccessible($id);
        $request->validate([
            'email' => 'required|email|max:255|unique:Employes,email,' . $id . ',ID',
            'password' => 'nullable|min:6',
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

        return redirect()->back()->with('success', 'Accès Web assigné avec succès à ' . $employe->Nom);
    }

    /* =========================================================
       EXPORT
       ========================================================= */
    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);

        $user = auth()->user();
        $accessibleIds = $this->getAccessibleEmployeeIds($user);
        $employes = $this->repository->getAllForExport($filters, $accessibleIds);

        ActivityLogService::log(action: 'export_excel', modelType: 'Employe');
        return $this->exportService->exportToExcel($employes, 'Employés');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);

        $user = auth()->user();
        $accessibleIds = $this->getAccessibleEmployeeIds($user);
        $employes = $this->repository->getAllForExport($filters, $accessibleIds);

        ActivityLogService::log(action: 'export_pdf', modelType: 'Employe');
        return $this->exportService->exportToPdf($employes, 'Liste des employés', 'exports.generic');
    }

    /* =========================================================
       PHOTO / FACE ENCODING
       ========================================================= */
    public function getFaceEncoding($id)
    {
        $this->ensureAccessible($id);
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
        $this->ensureAccessible($id);
        $employe = Employe::findOrFail($id);

        if (!$employe->FaceEncodingPath) {
            $message = "Photo de visage non trouvée";
            return view('404', compact('message'));
        }

        return $this->getFaceEncoding($id);
    }

    /* =========================================================
       HELPERS PRIVÉS
       ========================================================= */

    /**
     * Vérifie que l'employé donné est accessible par l'utilisateur connecté.
     * Retourne l'employé ou lance une exception 403.
     */
    private function ensureAccessible($id)
    {
        $user = auth()->user();
        $accessibleIds = $this->getAccessibleEmployeeIds($user);
        if (!in_array($id, $accessibleIds)) {
            abort(403, 'Vous n\'avez pas accès à cet employé.');
        }
    }

    private function optimizeAndConvertToBase64($file)
    {
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