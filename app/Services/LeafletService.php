<?php

namespace App\Services;

use App\Models\Leaflet;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use Imagick;

class LeafletService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createLeaflet(string $name, string $description, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'leaflet');
        
        $fullPath = $this->uploadService->getPublicUrl($logoPath);
        
        // Konversi PDF ke thumbnail gambar
        
        $imgExt = new Imagick();
        $imgExt->setResolution(300, 300); // Atur resolusi gambar sesuai kebutuhan Anda
        
        // Baca file PDF menggunakan readImage
        $imgExt->readImage($fullPath); // Baca file PDF
        
        // Set hanya mengambil halaman pertama
        $imgExt->setIteratorIndex(0);
        
        // Baca halaman pertama

        // Tentukan path untuk menyimpan gambar di direktori 'public/uploads'
        $uploadPath = public_path('uploads');
        
        // Pastikan direktori 'public/uploads' ada, jika belum, buat direktori tersebut
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        $nameFile = 'thumbnail_' . time() . '.jpg';
        
        $pathImagePdf = public_path('uploads/leaflet/' . $nameFile);
        
        $pathPdf = 'leaflet/' . $nameFile;
        
        // Simpan gambar sebagai file JPEG di direktori 'public/uploads'
        $imgExt->setImageFormat('jpeg');
        $imgExt->writeImage($pathImagePdf);   

        $leaflet = Leaflet::create([
            'name' => $name,
            'description' => $description,
            'pdf' => $logoPath,
            'image' => $pathPdf,
        ]);

        return $leaflet;
    }

    public function deleteLeafletById($id)
    {
        $leaflet = Leaflet::where('id', $id);
        $leaflet->delete();
    }

    public function updateLeaflet($id, $name, $description, $icon = null)
    {
        $leaflet = Leaflet::findOrFail($id);
        $leaflet->name = $name;
        $leaflet->description = $description;

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'leaflet');
            $leaflet->pdf = $logoPath;

            // Periksa apakah file yang diunggah adalah PDF
            if ($icon->getClientOriginalExtension() == 'pdf') {
                // Konversi PDF ke thumbnail gambar
                $fullPath = $this->uploadService->getPublicUrl($logoPath);
    
                $imgExt = new Imagick();
                $imgExt->setResolution(300, 300); // Atur resolusi gambar sesuai kebutuhan Anda
                $imgExt->readImage($fullPath); // Baca file PDF
                $imgExt->setIteratorIndex(0); // Set hanya mengambil halaman pertama
    
                // Tentukan path untuk menyimpan gambar di direktori 'public/uploads'
                $thumbnailPath = public_path('uploads/leaflet/thumbnail_' . time() . '.jpg');
    
                // Simpan gambar sebagai file JPEG di direktori 'public/uploads'
                $imgExt->setImageFormat('jpeg');
                $imgExt->writeImage($thumbnailPath);
    
                // Perbarui path thumbnail gambar di database
                $leaflet->image = 'leaflet/thumbnail_' . time() . '.jpg';
            }
        }

        $leaflet->save();

        return $leaflet;
    }
}
