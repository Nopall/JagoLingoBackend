<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SurfaceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSurfaceRequest;
use App\Models\Surface;
use App\Services\SurfaceService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SurfaceAreaController extends Controller
{
    protected $surfaceService;
    protected $uploadService;

    public function __construct(SurfaceAreaService $surfaceService, UploadService $uploadService)
    {
        $this->surfaceService = $surfaceService;
        $this->uploadService = $uploadService;
    }

    public function index(SurfaceDataTable $dataTable)
    {
        return $dataTable->render('surface.list');
    }

    public function formCreateSurface()
    {
        return view('surface.form');
    }

    public function formEditSurface(String $id)
    {
        $surface = Surface::find($id);

        if (!$surface) {
            return redirect()->route('surface.index')->with('error', 'Surface not found.');
        }

        $surface->image = $this->uploadService->getPublicUrl($surface->image);

        return view('surface.form', compact('surface'));
    }

    public function createSurface(CreateSurfaceRequest $request)
    {
        $data = $request->validated();

        $surface = $this->surfaceService->createSurface($data['name'], $data['seller'], $data['location'], $data['contact_no'], $data['photo']);

        return response()->json([
            'status' => true,
            'message' => 'Surface created successfully.',
            'data' => $surface,
        ]);
    }

    public function deleteSurfaceById(String $id)
    {
        $this->surfaceService->deleteSurfaceById($id);

        return response()->json([
            'status' => true,
            'message' => 'Surface deleted successfully.',
        ]);
    }

    public function updateSurface(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $icon = $request->file('photo');
        } else {
            $icon = null;
        }

        $surface = $this->surfaceService->updateSurface($id, $data['name'], $data['seller'], $data['location'], $data['contact_no'] , $icon);

        return response()->json([
            'status' => true,
            'message' => 'Surface updated successfully.',
            'data' => $surface,
        ]);
    }
}
