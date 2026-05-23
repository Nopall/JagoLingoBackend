<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\LeafletDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeafletRequest;
use App\Models\Leaflet;
use App\Services\LeafletService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Imagick;

class LeafletController extends Controller
{
    protected $leafletService;
    protected $uploadService;

    public function __construct(LeafletService $leafletService, UploadService $uploadService)
    {
        $this->leafletService = $leafletService;
        $this->uploadService = $uploadService;
    }

    public function index(LeafletDataTable $dataTable)
    {
        return $dataTable->render('leaflet.list');
        
         
    }

    public function formCreateLeaflet()
    {
        return view('leaflet.form');
    }

    public function formEditLeaflet(String $id)
    {
        $leaflet = Leaflet::find($id);

        if (!$leaflet) {
            return redirect()->route('leaflet.index')->with('error', 'Leaflet not found.');
        }

        $leaflet->icon_url = $this->uploadService->getPublicUrl($leaflet->icon);

        return view('leaflet.form', compact('leaflet'));
    }

    public function createLeaflet(CreateLeafletRequest $request)
    {
        $data = $request->validated();

        $leaflet = $this->leafletService->createLeaflet($data['name'], $data['description'], $data['pdf']);

        return response()->json([
            'status' => true,
            'message' => 'Leaflet created successfully.',
            'data' => $leaflet,
        ]);
    }

    public function deleteLeafletById(String $id)
    {
        $this->leafletService->deleteLeafletById($id);

        return response()->json([
            'status' => true,
            'message' => 'Leaflet deleted successfully.',
        ]);
    }

    public function updateLeaflet(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('pdf')) {
            $icon = $request->file('pdf');
        } else {
            $icon = null;
        }

        $leaflet = $this->leafletService->updateLeaflet($id, $data['name'], $data['description'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Leaflet updated successfully.',
            'data' => $leaflet,
        ]);
    }
}
