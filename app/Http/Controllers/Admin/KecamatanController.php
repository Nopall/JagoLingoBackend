<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\KecamatanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKecamatanRequest;
use App\Models\Kecamatan;
use App\Services\KecamatanService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    protected $kecamatanService;
    protected $uploadService;

    public function __construct(KecamatanService $kecamatanService, UploadService $uploadService)
    {
        $this->kecamatanService = $kecamatanService;
        $this->uploadService = $uploadService;
    }

    public function index(KecamatanDataTable $dataTable)
    {
        return $dataTable->render('kecamatan.list');
    }

    public function formCreateKecamatan()
    {
        return view('kecamatan.form');
    }

    public function formEditKecamatan(String $id)
    {
        $kecamatan = Kecamatan::find($id);

        if (!$kecamatan) {
            return redirect()->route('kecamatan.index')->with('error', 'Kecamatan not found.');
        }
        
        return view('kecamatan.form', compact('kecamatan'));
    }

    public function createKecamatan(CreateKecamatanRequest $request)
    {
        $data = $request->validated();

        $kecamatan = $this->kecamatanService->createKecamatan($data['name']);

        return response()->json([
            'status' => true,
            'message' => 'Kecamatan created successfully.',
            'data' => $kecamatan,
        ]);
    }

    public function deleteKecamatanById(String $id)
    {
        $this->kecamatanService->deleteKecamatanById($id);

        return response()->json([
            'status' => true,
            'message' => 'Kecamatan deleted successfully.',
        ]);
    }

    public function updateKecamatan(Request $request, $id)
    {
        $data = $request->all();
        
        $kecamatan = $this->kecamatanService->updateKecamatan($id, $data['name']);

        return response()->json([
            'status' => true,
            'message' => 'Kecamatan updated successfully.',
            'data' => $kecamatan,
        ]);
    }
}
