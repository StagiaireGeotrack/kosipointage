<?php
// app/Http/Controllers/EmployeController.php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\EntrepriseSiege;
use App\Http\Requests\EmployeRequest;
use App\Repositories\EmployeRepository;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeController extends Controller
{
    protected $repository;
    protected $exportService;
    
    public function __construct(
        EmployeRepository $repository,
        ExportService $exportService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup',
            'sort_by', 'sort_order'
        ]);
        
        $employes = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all();
        
        return view('employes.index', compact('employes', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        return view('employes.create', compact('sieges'));
    }
    
    public function store(EmployeRequest $request)
    {
        $data = $request->validated();
        
        // Convertir et COMPRESSER la photo en Base64 si présente
        if ($request->hasFile('FaceEncodingFile')) {
            $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
            $data['HasFaceSetup'] = true;
        }
        
        // Ajouter la date de création
        $data['CreatedAt'] = now();
        
        // Supprimer FaceEncodingFile car ce n'est pas une colonne
        unset($data['FaceEncodingFile']);
        
        $employe = $this->repository->create($data);
        
        return redirect()->route('employes.index')
            ->with('success', __('Employé créé avec succès'));
    }
    
    public function show($id)
    {
        $employe = $this->repository->findById($id);
        $pointages = $employe->pointages()->latest('timestamp_')->paginate(10);
        $sieges = EntrepriseSiege::all();
        
        return view('employes.show', compact('employe', 'pointages', 'sieges'));
    }
    
    public function edit($id)
    {
        $employe = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        
        return view('employes.edit', compact('employe', 'sieges'));
    }
    
    public function update(EmployeRequest $request, $id)
    {
        $data = $request->validated();
        
        // Convertir et COMPRESSER la nouvelle photo si présente
        if ($request->hasFile('FaceEncodingFile')) {
            $data['FaceEncodingPath'] = $this->optimizeAndConvertToBase64($request->file('FaceEncodingFile'));
            $data['HasFaceSetup'] = true;
        } else {
            // Ne pas modifier la photo si aucun nouveau fichier
            unset($data['FaceEncodingPath']);
        }
        
        // Supprimer FaceEncodingFile
        unset($data['FaceEncodingFile']);
        
        $employe = $this->repository->update($id, $data);
        
        return redirect()->route('employes.index')
            ->with('success', __('Employé modifié avec succès'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        
        return redirect()->route('employes.index')
            ->with('success', __('Employé supprimé avec succès'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);
        
        $employes = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($employes, __('Employés'));
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only([
            'search', 'SiegeID', 'Actived', 'HasBiometricSetup', 'HasFaceSetup'
        ]);
        
        $employes = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($employes, __('Employés'), 'exports.employes');
    }
    
    /**
     * Retourne la photo de visage d'un employé
     */
    public function getFaceEncoding($id)
    {
        $employe = Employe::findOrFail($id);
        
        if (!$employe->FaceEncodingPath) {
            abort(404, __('Photo de visage non trouvée'));
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
            abort(404, __('Photo de visage non trouvée'));
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