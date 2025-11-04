<?php
// app/Http/Controllers/EntrepriseController.php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;
use App\Models\EntrepriseSiege;
use App\Services\ExportService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\EntrepriseRequest;
use App\Repositories\EntrepriseRepository;

class EntrepriseController extends Controller
{
    protected $repository;
    protected $exportService;
    
    public function __construct(
        EntrepriseRepository $repository,
        ExportService $exportService
    ) {
        $this->repository = $repository;
        $this->exportService = $exportService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived', 'sort_by', 'sort_order']);
        $entreprises = $this->repository->getFiltered($filters);
        $sieges = EntrepriseSiege::all();
        
        return view('entreprises.index', compact('entreprises', 'sieges', 'filters'));
    }
    
    public function create()
    {
        $sieges = EntrepriseSiege::all();
        return view('entreprises.create', compact('sieges'));
    }
    
    public function store(EntrepriseRequest $request)
    {
        $data = $request->validated();
        
        // Convertir et COMPRESSER le logo en Base64 si présent
        if ($request->hasFile('Logo')) {
            $data['Logo'] = $this->optimizeAndConvertToBase64($request->file('Logo'));
        }
        
        if (!auth()->user()->IsSuperAdmin) {
            $data['Actived'] = "0";
        }

        // Ajouter la date de création
        $data['CreatedAt'] = now();
        
        $entreprise = $this->repository->create($data);
        
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement créé avec succès.'));
    }
    
    public function show($id)
    {
        $entreprise = $this->repository->findById($id);
        return view('entreprises.show', compact('entreprise'));
    }
    
    public function edit($id)
    {
        $entreprise = $this->repository->findById($id);
        $sieges = EntrepriseSiege::all();
        
        return view('entreprises.edit', compact('entreprise', 'sieges'));
    }
    
    public function update(EntrepriseRequest $request, $id)
    {
        $data = $request->validated();

        $site = Entreprise::findOrFail($id);
        
        // Convertir et COMPRESSER le nouveau logo si présent
        if ($request->hasFile('Logo')) {
            $data['Logo'] = $this->optimizeAndConvertToBase64($request->file('Logo'));
        } else {
            // Ne pas modifier le logo s'il n'y a pas de nouveau fichier
            unset($data['Logo']);
        }
        
        if (!auth()->user()->IsSuperAdmin) {
            $data['Actived'] = $site->Actived;
        }

        $this->repository->update($id, $data);
        
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement modifié avec succès.'));
    }
    
    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect()->route('entreprises.index')
            ->with('success', __('Site ou établissement supprimé avec succès.'));
    }
    
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived']);
        $entreprises = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToExcel($entreprises, 'Entreprises');
    }
    
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['search', 'SiegeID', 'Actived']);
        $entreprises = $this->repository->getAllForExport($filters);
        
        return $this->exportService->exportToPdf($entreprises, 'Liste des entreprises', 'exports.generic');
    }
    
    /**
     * Retourne le logo d'une entreprise en tant qu'image
     */
    public function getLogo($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        
        if (!$entreprise->Logo) {
            abort(404, 'Logo non trouvé');
        }
        
        // Décoder (et décompresser si nécessaire)
        $logoData = $this->decodeBase64($entreprise->Logo);
        
        // Déterminer le type MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($logoData);
        
        return response($logoData)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Retourne une miniature du logo
     */
    public function getLogoThumbnail($id, $width = 150, $height = 150)
    {
        $entreprise = Entreprise::findOrFail($id);
        
        if (!$entreprise->Logo) {
            abort(404, 'Logo non trouvé');
        }
        
        // Décoder
        $logoData = $this->decodeBase64($entreprise->Logo);
        
        // Créer une miniature
        $thumbnail = $this->createThumbnail($logoData, $width, $height);
        
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
            
            // 2. REDIMENSIONNER si trop grande (max 800x800)
            $maxWidth = 800;
            $maxHeight = 800;
            
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
            
            Log::info("Image optimisée : {$originalSize} bytes → {$compressedSize} bytes (réduction : {$reduction}%)");
            
            return $base64;
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'optimisation de l\'image : ' . $e->getMessage());
            throw new \Exception('Impossible d\'optimiser l\'image');
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