<?php
// app/Services/FileStorageService.php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class FileStorageService
{
    public function storeInDatabase(UploadedFile $file, string $fieldName, $model, string $idField)
    {
        // Lire le contenu du fichier
        $content = $file->get();
        
        // Stocker le contenu dans la base de données
        DB::table($model->getTable())
            ->where($model->getKeyName(), $model->getKey())
            ->update([$fieldName => $content]);
            
        return true;
    }
    
    public function retrieveFromDatabase($model, string $fieldName)
    {
        $file = DB::table($model->getTable())
            ->where($model->getKeyName(), $model->getKey())
            ->value($fieldName);
            
        return $file;
    }
    
    public function createThumbnail($imageData, $width = 100, $height = 100)
    {
        $img = Image::make($imageData);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        return $img->encode()->getEncoded();
    }
}