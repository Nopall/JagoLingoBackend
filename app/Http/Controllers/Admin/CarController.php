<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CarBrandDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCarBrandRequest;
use App\Models\CarBrand;
use App\Services\CarService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected $carService;
    protected $uploadService;

    public function __construct(CarService $carService, UploadService $uploadService)
    {
        $this->carService = $carService;
        $this->uploadService = $uploadService;
    }

    public function index(CarBrandDataTable $dataTable)
    {
        return $dataTable->render('car.list');
    }

    public function formCreateBrand()
    {
        return view('car.form');
    }

    public function formEditBrand(String $id)
    {
        $carBrand = CarBrand::find($id);

        if (!$carBrand) {
            return redirect()->route('car.index')->with('error', 'Car brand not found.');
        }

        $carBrand->logo_url = $this->uploadService->getPublicUrl($carBrand->logo);

        return view('car.form', compact('carBrand'));
    }

    public function createCarBrand(CreateCarBrandRequest $request)
    {
        $data = $request->validated();

        $carBrand = $this->carService->createCarBrand($data['name'], $data['logo']);

        return response()->json([
            'status' => true,
            'message' => 'Car brand created successfully.',
            'data' => $carBrand,
        ]);
    }

    public function deleteCarBrandById(String $id)
    {
        $this->carService->deleteCarBrandById($id);

        return response()->json([
            'status' => true,
            'message' => 'Car brand deleted successfully.',
        ]);
    }

    public function updateCarBrand(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
        } else {
            $logo = null;
        }

        $carBrand = $this->carService->updateCarBrand($id, $data['name'], $logo);

        return response()->json([
            'status' => true,
            'message' => 'Car brand updated successfully.',
            'data' => $carBrand,
        ]);
    }
}
