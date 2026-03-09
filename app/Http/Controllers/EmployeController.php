<?php
// app/Http/Controllers/EmployeController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
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
    
    public function __construct(
        EmployeRepository $repository,
        ExportService $exportService,
        HashService $hashService   
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
        $this->hashService  = $hashService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup',
            'sort_by', 'sort_order'
        ]);
        
        $employes = $this->repository->getFiltered($filters);
        $employes->appends($filters);

        $sieges = EntrepriseSiege::all();
        
        return view('employes.index', compact('employes', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        
        $admin_connected = Auth()->user() ;
        $siege_id = $admin_connected->SiegeID ?? null  ; 

        return view('employes.create', compact('sieges' , 'siege_id'));
    }
    
    public function store(EmployeRequest $request)
    {
        $data = $request->validated();
        
        $siegeId = $data['SiegeID'];

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

        if (!empty($data['Pin'])) {
            $hashedPin = $this->hashService->toHash($data['Pin']);
            if (Employe::where('SiegeID', $siegeId)->where('Pin', $hashedPin)->whereNotNull('Pin')->exists()) {
                return redirect()->back()
                    ->withErrors(['Pin' => 'Ce code PIN est déjà utilisé dans ce siège.'])
                    ->withInput();
            }
            $data['Pin'] = $hashedPin;
        }

        // Convertir et COMPRESSER la photo en Base64 si présente
        if ($request->hasFile('FaceEncodingFile')) {
            $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
            $data['HasFaceSetup'] = true;
        } else {
            $data['HasFaceSetup'] = false;
        }
        
        if (!auth()->user()->IsSuperAdmin) {
            $data['Actived'] = "0";
            $data['HasBiometricSetup'] = false;
        }

        if (empty($data['num_mat'])) {
            $data['num_mat'] = null;
        }

        $data['CreatedAt'] = now();
        
        unset($data['FaceEncodingFile']);
        
        $employe = $this->repository->create($data);
        
        return redirect()->back()->with('success', __('Employé créé avec succès'));
    }
    
    public function show($id)
    {
        $employe = $this->repository->findById($id);
        $pointages = $employe->pointages()->latest('timestamp_')->paginate(5);
        $sieges = EntrepriseSiege::all();
        
        return view('employes.show', compact('employe', 'pointages', 'sieges'));
    }
    
    public function edit($id)
    {
        $employe = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();

        $user = auth()->user();
        if ($user->isSeller()) 
        {
            $this->show( $id) ;
        }
        
        return view('employes.edit', compact('employe', 'sieges'));
    }

    public function resetCodePin($id)
    {
        $employe = $this->repository->findById($id) ;
        $employe->Pin = null ;
        $employe->save() ;
        
        return redirect()->back()->with('success', __('Code Pin réinitialisé avec succès'));
    }    
    
    public function update(EmployeRequest $request, $id)
    {
        // Récupérer les données validées
        $data = $request->validated();
        
        // Vérifier que l'employé existe
        $employe = Employe::findOrFail($id);
        
        // Si l'utilisateur n'est PAS SuperAdmin
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

            // Ne mettre à jour QUE le Nom, garder tout le reste intact
            $data = [
                        'Nom'               => $data['Nom'],
                        'num_mat'           => !empty($data['num_mat']) ? $data['num_mat'] : $employe->num_mat,
                        'Pin'               => $newPin, // valeur soumise, fallback sur l'existante
                        'SiegeID'           => $employe->SiegeID,
                        'BadgeID'           => $employe->BadgeID,
                        'HasBiometricSetup' => $employe->HasBiometricSetup,
                        'HasFaceSetup'      => $employe->HasFaceSetup,
                        'FaceEncodingPath'  => $employe->FaceEncodingPath,
                        'Actived'           => $employe->Actived,
                    ];
        } else {
            // SuperAdmin : peut tout modifier
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

            if (empty($data['num_mat'])) {
                $data['num_mat'] = $employe->num_mat;
            }
            
            // Convertir et COMPRESSER la nouvelle photo si présente
            if ($request->hasFile('FaceEncodingFile')) {
                $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
                $data['HasFaceSetup'] = true;
            } else {
                // Ne pas modifier la photo si aucun nouveau fichier
                unset($data['FaceEncodingPath']);
                unset($data['HasFaceSetup']); // Ne pas modifier HasFaceSetup
            }
        }

        // Supprimer FaceEncodingFile du tableau de données
        unset($data['FaceEncodingFile']);

        // Mise à jour
        $this->repository->update($id, $data);
        
        return redirect()->back()->with('success', __('Employé modifié avec succès'));
    }
    
    public function destroy($id)
    {
        // $this->repository->delete($id);

        $user = auth()->user();        
        if ( !$user->isTrueSuperAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $employe = $this->repository->findById($id) ;
        $employe->deleted = true ;
        $employe->Actived = false ;
        $employe->save() ;

        ActivityLogService::log(
            action: 'delete',
            modelType: 'Entreprise',
            modelId: (int) $id,
        );
        
        return redirect()->back()->with('success', __('Employé supprimé avec succès'));
    }

    public function reset($id)
    {
        // $this->repository->delete($id);
        
        $user = auth()->user();        
        if ( !$user->isTrueSuperAdmin() ) {
            return redirect()->back()->with('error', __('Vous n\'avez pas d\' accès à cette fonctionnalité'));
        }

        $employe = $this->repository->findById($id) ;
        $employe->deleted = false ;
        $employe->Actived = true ;
        $employe->save() ;

        
        ActivityLogService::log(
            action: 'reset',
            modelType: 'Entreprise',
            modelId: (int) $id,
        );
        
        return redirect()->back()->with('success', __('Employé supprimé avec succès'));
    }
    
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
    
    /**
     * Retourne la photo de visage d'un employé
     */
    public function getFaceEncoding($id)
    {
        $employe = Employe::findOrFail($id);
        
        if (!$employe->FaceEncodingPath) {
            $message = "Photo de visage non trouvée" ;
            return view( '404' , compact('message') );
        }
        
        // Décoder (et décompresser si nécessaire)
        $imageData = $this->decodeBase64($employe->FaceEncodingPath);
        
        // Déterminer le type MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);
        
        return response($imageData)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Retourne une miniature de la photo de visage
     */
    public function getFaceThumbnail($id)
    {
        $employe = Employe::findOrFail($id);
        
        if (!$employe->FaceEncodingPath) {
            $message = "Photo de visage non trouvée" ;
            return view( '404' , compact('message') );
        }
        
        // Décoder
        $imageData = $this->decodeBase64($employe->FaceEncodingPath);
        
        // Créer une miniature
        $thumbnail = $this->createThumbnail($imageData);
        
        return response($thumbnail)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * ⭐ NOUVELLE MÉTHODE : Optimise et convertit l'image en Base64
     * RÉDUIT LA TAILLE DE 70-90% !
     */
    protected function optimizeAndConvertToBase64($file)
    {
        try {
            // 1. Lire l'image
            $imageData = file_get_contents($file->getRealPath());
            $image = imagecreatefromstring($imageData);
            
            if ($image === false) {
                throw new \Exception('Impossible de créer l\'image');
            }
            
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            
            // 2. REDIMENSIONNER si trop grande (max 600x600 pour visages)
            $maxWidth = 600;
            $maxHeight = 600;
            
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight, 1);
            $newWidth = intval($originalWidth * $ratio);
            $newHeight = intval($originalHeight * $ratio);
            
            if ($ratio < 1) {
                // Créer l'image redimensionnée
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                
                // Préserver la transparence
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                
                // Redimensionner
                imagecopyresampled(
                    $resized, $image,
                    0, 0, 0, 0,
                    $newWidth, $newHeight,
                    $originalWidth, $originalHeight
                );
                
                imagedestroy($image);
                $image = $resized;
            }
            
            // 3. COMPRESSER en PNG avec compression maximale
            ob_start();
            imagepng($image, null, 9); // 9 = compression maximale
            $compressedData = ob_get_clean();
            imagedestroy($image);
            
            // 4. OPTION : Compresser le Base64 avec gzip (BONUS)
            $compressed = gzcompress($compressedData, 9);
            
            // 5. Convertir en Base64
            $base64 = base64_encode($compressed);
            
            // Log pour debug
            $originalSize = strlen($imageData);
            $compressedSize = strlen($base64);
            $reduction = round((1 - $compressedSize / $originalSize) * 100, 2);
            
            Log::info("Photo optimisée : {$originalSize} bytes → {$compressedSize} bytes (réduction : {$reduction}%)");
            
            return $base64;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'optimisation de la photo : ' . $e->getMessage());
            throw new \Exception('Impossible d\'optimiser la photo');
        }
    }
    
    /**
     * Décode le Base64 (et décompresse si nécessaire)
     */
    protected function decodeBase64($base64)
    {
        try {
            $decoded = base64_decode($base64);
            
            // Tenter de décompresser avec gzip
            $decompressed = @gzuncompress($decoded);
            
            // Si la décompression réussit, utiliser les données décompressées
            if ($decompressed !== false) {
                return $decompressed;
            }
            
            // Sinon, retourner les données décodées normalement
            return $decoded;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du décodage : ' . $e->getMessage());
            return base64_decode($base64);
        }
    }
    
    /**
     * Crée une miniature d'une image
     */
    protected function createThumbnail($imageData, $width = 150, $height = 150)
    {
        try {
            $image = imagecreatefromstring($imageData);
            
            if ($image === false) {
                throw new \Exception('Impossible de créer l\'image');
            }
            
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            
            // Calculer les dimensions proportionnelles
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = intval($originalWidth * $ratio);
            $newHeight = intval($originalHeight * $ratio);
            
            // Créer la miniature
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            
            // Préserver la transparence
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            
            imagecopyresampled(
                $thumbnail, 
                $image, 
                0, 0, 0, 0, 
                $newWidth, 
                $newHeight, 
                $originalWidth, 
                $originalHeight
            );
            
            // Capturer la sortie
            ob_start();
            imagepng($thumbnail, null, 9);
            $thumbnailData = ob_get_clean();
            
            // Libérer la mémoire
            imagedestroy($image);
            imagedestroy($thumbnail);
            
            return $thumbnailData;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la miniature: ' . $e->getMessage());
            throw new \Exception('Impossible de créer la miniature');
        }
    }
}