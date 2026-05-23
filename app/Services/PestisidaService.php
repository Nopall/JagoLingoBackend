<?php

namespace App\Services;

namespace App\Services;

use App\Models\Pestisida;
use Illuminate\Http\UploadedFile;

class PestisidaService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createPestisida(string $name, string $seller, string $location, string $contact_no, UploadedFile $icon)
    {
        $logoPath = $this->uploadService->upload($icon, 'pestisida');

        $pestisida = Pestisida::create([
            'name' => $name,
            'seller' => $seller,
            'location' => $location,
            'contact_no' => $contact_no,
            'photo' => $logoPath,
        ]);

        return $pestisida;
    }

    public function deletePestisidaById($id)
    {
        $pestisida = Pestisida::where('id', $id);
        $pestisida->delete();
    }

    public function updatePestisida($id, $name, $seller, $location, $contact_no, $icon = null)
    {
        $pestisida = Pestisida::findOrFail($id);
        $pestisida->name = $name;
        $pestisida->seller = $seller;
        $pestisida->location = $location;
        $pestisida->contact_no = $contact_no;
        

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'pestisida');
            $pestisida->photo = $logoPath;
        }

        $pestisida->save();

        return $pestisida;
    }
}
