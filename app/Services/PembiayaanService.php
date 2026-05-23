<?php

namespace App\Services;

use App\Models\Pembiayaan;
use Illuminate\Http\UploadedFile;

class PembiayaanService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createPembiayaan(string $name, string $cp_1, string $wa_1, string $cp_2, string $wa_2, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'pembiayaan');

        $pembiayaan = Pembiayaan::create([
            'name' => $name,
            'cp_1' => $cp_1,
            'cp_2' => $cp_2,
            'wa_1' => $wa_1,
            'wa_2' => $wa_2,
            'image' => $logoPath,
        ]);

        return $pembiayaan;
    }

    public function deletePembiayaanById($id)
    {
        $pembiayaan = Pembiayaan::where('id', $id);
        $pembiayaan->delete();
    }

    public function updatePembiayaan($id, $name, $cp_1, $wa_1, $cp_2, $wa_2, $icon = null)
    {
        $pembiayaan = Pembiayaan::findOrFail($id);
        $pembiayaan->name = $name;
        $pembiayaan->cp_1 = $cp_1;
        $pembiayaan->cp_2 = $cp_2;
        $pembiayaan->wa_1 = $wa_1;
        $pembiayaan->wa_2 = $wa_2;
        

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'pembiayaan');
            $pembiayaan->image = $logoPath;
        }

        $pembiayaan->save();

        return $pembiayaan;
    }
}
