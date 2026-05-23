<?php

namespace App\Services;

namespace App\Services;

use App\Models\Banner;
use Illuminate\Http\UploadedFile;

class BannerService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createBanner(string $name, string $description, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'banner');

        $banner = Banner::create([
            'name' => $name,
            'description' => $description,
            'image' => $logoPath,
        ]);

        return $banner;
    }

    public function deleteBannerById($id)
    {
        $banner = Banner::where('id', $id);
        $banner->delete();
    }

    public function updateBanner($id, $name, $description, $icon = null)
    {
        $banner = Banner::findOrFail($id);
        $banner->name = $name;
        $banner->description = $description;

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'banner');
            $banner->image = $logoPath;
        }

        $banner->save();

        return $banner;
    }
}
