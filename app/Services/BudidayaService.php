<?php

namespace App\Services;

use App\Models\Budidaya;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use Imagick;

class BudidayaService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createBudidaya(string $name, string $description, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'budidaya');
        
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
        
        $pathImagePdf = public_path('uploads/budidaya/' . $nameFile);
        
        $pathPdf = 'budidaya/' . $nameFile;
        
        // Simpan gambar sebagai file JPEG di direktori 'public/uploads'
        $imgExt->setImageFormat('jpeg');
        $imgExt->writeImage($pathImagePdf);   

        $budidaya = Budidaya::create([
            'name' => $name,
            'description' => $description,
            'pdf' => $logoPath,
            'image' => $pathPdf,
        ]);

        return $budidaya;
    }

    public function deleteBudidayaById($id)
    {
        $budidaya = Budidaya::where('id', $id);
        $budidaya->delete();
    }

    public function updateBudidaya($id, $name, $description, $icon = null)
    {
        $budidaya = Budidaya::findOrFail($id);
        $budidaya->name = $name;
        $budidaya->description = $description;

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'budidaya');
            $budidaya->pdf = $logoPath;

            // Periksa apakah file yang diunggah adalah PDF
            if ($icon->getClientOriginalExtension() == 'pdf') {
                // Konversi PDF ke thumbnail gambar
                $fullPath = $this->uploadService->getPublicUrl($logoPath);
    
                $imgExt = new Imagick();
                $imgExt->setResolution(300, 300); // Atur resolusi gambar sesuai kebutuhan Anda
                $imgExt->readImage($fullPath); // Baca file PDF
                $imgExt->setIteratorIndex(0); // Set hanya mengambil halaman pertama
    
                // Tentukan path untuk menyimpan gambar di direktori 'public/uploads'
                $thumbnailPath = public_path('uploads/budidaya/thumbnail_' . time() . '.jpg');
    
                // Simpan gambar sebagai file JPEG di direktori 'public/uploads'
                $imgExt->setImageFormat('jpeg');
                $imgExt->writeImage($thumbnailPath);
    
                // Perbarui path thumbnail gambar di database
                $budidaya->image = 'budidaya/thumbnail_' . time() . '.jpg';
            }
        }

        $budidaya->save();

        return $budidaya;
    }
}
