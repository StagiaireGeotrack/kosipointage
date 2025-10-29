<?php
// app/Http/Controllers/EntrepriseController.php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\EntrepriseSiege;
use App\Http\Requests\EntrepriseRequest;
use App\Repositories\EntrepriseRepository;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        // Convertir le logo en Base64 si présent
        if ($request->hasFile('Logo')) {
            $data['Logo'] = $this->convertImageToBase64($request->file('Logo'));
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
        
        // Convertir le nouveau logo en Base64 si présent
        if ($request->hasFile('Logo')) {
            $data['Logo'] = $this->convertImageToBase64($request->file('Logo'));
        } else {
            // Ne pas modifier le logo s'il n'y a pas de nouveau fichier
            unset($data['Logo']);
        }
        
        $entreprise = $this->repository->update($id, $data);
        
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
        
        return $this->exportService->exportToPdf($entreprises, 'Entreprises', 'exports.entreprises');
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
        
        // Décoder le Base64
        $logoData = base64_decode($entreprise->Logo);
        
        // Déterminer le type MIME à partir des premiers octets
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($logoData);
        
        return response($logoData)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=86400'); // Cache 24h
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
        
        // Décoder le Base64
        $logoData = base64_decode($entreprise->Logo);
        
        // Créer une miniature
        $thumbnail = $this->createThumbnail($logoData, $width, $height);
        
        return response($thumbnail)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache 24h
    }
    
    /**
     * Convertit une image uploadée en Base64
     */
    protected function convertImageToBase64($file)
    {
        try {
            $imageData = file_get_contents($file->getRealPath());
            return base64_encode($imageData);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la conversion de l\'image en Base64: ' . $e->getMessage());
            throw new \Exception('Impossible de convertir l\'image en Base64');
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
            
            // Préserver la transparence pour les PNG
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
            imagepng($thumbnail);
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