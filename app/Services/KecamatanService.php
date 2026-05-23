<?php

namespace App\Services;

namespace App\Services;

use App\Models\Kecamatan;
use Illuminate\Http\UploadedFile;

class KecamatanService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createKecamatan(string $name)
    {

        $banner = Kecamatan::create([
            'name' => $name
        ]);

        return $banner;
    }

    public function deleteKecamatanById($id)
    {
        $banner = Kecamatan::where('id', $id);
        $banner->delete();
    }

    public function updateKecamatan($id, $name)
    {
        $banner = Kecamatan::findOrFail($id);
        $banner->name = $name;

        $banner->save();

        return $banner;
    }
}
