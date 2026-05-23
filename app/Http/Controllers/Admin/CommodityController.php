<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CommodityDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommodityRequest;
use App\Models\Commodity;
use App\Services\CommodityService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class CommodityController extends Controller
{
    protected $commodityService;
    protected $uploadService;

    public function __construct(CommodityService $commodityService, UploadService $uploadService)
    {
        $this->commodityService = $commodityService;
        $this->uploadService = $uploadService;
    }

    public function index(CommodityDataTable $dataTable)
    {
        return $dataTable->render('commodity.list');
    }

    public function formCreateCommodity()
    {
        return view('commodity.form');
    }

    public function formEditCommodity(String $id)
    {
        $commodity = Commodity::find($id);

        if (!$commodity) {
            return redirect()->route('commodity.index')->with('error', 'Commodity not found.');
        }

        $commodity->image = $this->uploadService->getPublicUrl($commodity->image);

        return view('commodity.form', compact('commodity'));
    }

    public function createCommodity(CreateCommodityRequest $request)
    {
        $data = $request->validated();

        $commodity = $this->commodityService->createCommodity($data['name'], $data['image']);

        return response()->json([
            'status' => true,
            'message' => 'Commodity created successfully.',
            'data' => $commodity,
        ]);
    }

    public function deleteCommodityById(String $id)
    {
        $this->commodityService->deleteCommodityById($id);

        return response()->json([
            'status' => true,
            'message' => 'Commodity deleted successfully.',
        ]);
    }

    public function updateCommodity(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $icon = $request->file('image');
        } else {
            $icon = null;
        }

        $commodity = $this->commodityService->updateCommodity($id, $data['name'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Commodity updated successfully.',
            'data' => $commodity,
        ]);
    }
}
