<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class UploadService
{
    public function upload(UploadedFile $file, $directory = '', $filename = null)
    {
        if (!$file->isValid()) {
            return false;
        }
        
        $filename = $filename ?: $this->generateUniqueFilename($file);
        
        // Hapus spasi dari nama file
        $filename = str_replace(' ', '_', $filename);
        
        $path = $file->storeAs($directory, $filename, 'local');
        
        return $path;

    }

    public function getPublicUrl($path)
    {
        return asset("uploads/{$path}");
    }

    protected function generateUniqueFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $uniqueId = uniqid();
        $newFilename = "{$filename}_{$uniqueId}.{$extension}";

        return $newFilename;
    }
}
