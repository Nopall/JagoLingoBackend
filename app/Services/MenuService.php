<?php

namespace App\Services;

namespace App\Services;

use App\Models\Menu;
use Illuminate\Http\UploadedFile;

class MenuService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createMenu(string $name, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'car_brands');

        $menu = Menu::create([
            'name' => $name,
            'icon' => $logoPath,
        ]);

        return $menu;
    }

    public function deleteMenuById($id)
    {
        $menu = Menu::where('id', $id);
        $menu->delete();
    }

    public function updateMenu($id, $name, $icon = null)
    {
        $menu = Menu::findOrFail($id);
        $menu->name = $name;

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'menu');
            $menu->icon = $logoPath;
        }

        $menu->save();

        return $menu;
    }
}
