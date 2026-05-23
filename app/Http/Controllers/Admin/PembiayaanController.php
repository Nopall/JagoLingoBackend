<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PembiayaanDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePembiayaanRequest;
use App\Models\Pembiayaan;
use App\Services\PembiayaanService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class PembiayaanController extends Controller
{
    protected $pembiayaanService;
    protected $uploadService;

    public function __construct(PembiayaanService $pembiayaanService, UploadService $uploadService)
    {
        $this->pembiayaanService = $pembiayaanService;
        $this->uploadService = $uploadService;
    }

    public function index(PembiayaanDataTable $dataTable)
    {
        return $dataTable->render('pembiayaan.list');
    }

    public function formCreatePembiayaan()
    {
        return view('pembiayaan.form');
    }

    public function formEditPembiayaan(String $id)
    {
        $pembiayaan = Pembiayaan::find($id);

        if (!$pembiayaan) {
            return redirect()->route('pembiayaan.index')->with('error', 'Pembiayaan not found.');
        }

        $pembiayaan->image = $this->uploadService->getPublicUrl($pembiayaan->image);

        return view('pembiayaan.form', compact('pembiayaan'));
    }

    public function createPembiayaan(CreatePembiayaanRequest $request)
    {
        $data = $request->validated();

        $pembiayaan = $this->pembiayaanService->createPembiayaan($data['name'], $data['cp_1'], $data['wa_1'], $data['cp_2'], $data['wa_2'], $data['image']);

        return response()->json([
            'status' => true,
            'message' => 'Pembiayaan created successfully.',
            'data' => $pembiayaan,
        ]);
    }

    public function deletePembiayaanById(String $id)
    {
        $this->pembiayaanService->deletePembiayaanById($id);

        return response()->json([
            'status' => true,
            'message' => 'Pembiayaan deleted successfully.',
        ]);
    }

    public function updatePembiayaan(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $icon = $request->file('image');
        } else {
            $icon = null;
        }

        $pembiayaan = $this->pembiayaanService->updatePembiayaan($id, $data['name'], $data['cp_1'], $data['wa_1'], $data['cp_2'],  $data['wa_2'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Pembiayaan updated successfully.',
            'data' => $pembiayaan,
        ]);
    }
}
