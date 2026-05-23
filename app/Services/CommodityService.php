<?php

namespace App\Services;

use App\Models\Commodity;
use Illuminate\Http\UploadedFile;

class CommodityService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createCommodity(string $name, string $description, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'commodity');

        $commodity = Commodity::create([
            'name' => $name,
            'image' => $logoPath,
        ]);

        return $commodity;
    }

    public function deleteCommodityById($id)
    {
        $commodity = Commodity::where('id', $id);
        $commodity->delete();
    }

    public function updateCommodity($id, $name, $description, $icon = null)
    {
        $commodity = Commodity::findOrFail($id);
        $commodity->name = $name;

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'commodity');
            $commodity->image = $logoPath;
        }

        $commodity->save();

        return $commodity;
    }
}
