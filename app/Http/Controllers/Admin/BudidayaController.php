<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BudidayaDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBudidayaRequest;
use App\Models\Budidaya;
use App\Services\BudidayaService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Imagick;

class BudidayaController extends Controller
{
    protected $budidayaService;
    protected $uploadService;

    public function __construct(BudidayaService $budidayaService, UploadService $uploadService)
    {
        $this->budidayaService = $budidayaService;
        $this->uploadService = $uploadService;
    }

    public function index(BudidayaDataTable $dataTable)
    {
        return $dataTable->render('budidaya.list');
        
         
    }

    public function formCreateBudidaya()
    {
        return view('budidaya.form');
    }

    public function formEditBudidaya(String $id)
    {
        $budidaya = Budidaya::find($id);

        if (!$budidaya) {
            return redirect()->route('budidaya.index')->with('error', 'Budidaya not found.');
        }

        $budidaya->image = $this->uploadService->getPublicUrl($budidaya->image);

        return view('budidaya.form', compact('budidaya'));
    }

    public function createBudidaya(CreateBudidayaRequest $request)
    {
        $data = $request->validated();

        $budidaya = $this->budidayaService->createBudidaya($data['name'], $data['description'], $data['pdf']);

        return response()->json([
            'status' => true,
            'message' => 'Budidaya created successfully.',
            'data' => $budidaya,
        ]);
    }

    public function deleteBudidayaById(String $id)
    {
        $this->budidayaService->deleteBudidayaById($id);

        return response()->json([
            'status' => true,
            'message' => 'Budidaya deleted successfully.',
        ]);
    }

    public function updateBudidaya(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('pdf')) {
            $icon = $request->file('pdf');
        } else {
            $icon = null;
        }

        $budidaya = $this->budidayaService->updateBudidaya($id, $data['name'], $data['description'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Budidaya updated successfully.',
            'data' => $budidaya,
        ]);
    }
}
