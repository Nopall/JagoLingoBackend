<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PackageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePackageRequest;
use App\Http\Requests\CreatePhaseRequest;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Phase;

class PackageController extends Controller
{
    protected $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(PackageDataTable $dataTable)
    {
        return $dataTable->render('package.list');
    }

    public function formCreatePackage()
    {
        return view('package.form');
    }

    public function formEditPackage(String $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return redirect()->route('package.index')->with('error', 'Package not found.');
        }

        return view('package.form', compact('package'));
    }

    public function createPackage(CreatePackageRequest $request)
    {
        $data = $request->validated();

        $package = $this->packageService->createPackage($data['name'], $data['price'], $data['is_active']);

        return response()->json([
            'status' => true,
            'message' => 'Package created successfully.',
            'data' => $package,
        ]);
    }

    public function deletePackageById(String $id)
    {
        $this->packageService->deletePackageById($id);

        return response()->json([
            'status' => true,
            'message' => 'Package deleted successfully.',
        ]);
    }

    public function updatePackage(Request $request, $id)
    {
        $data = $request->all();
        
        $package = $this->packageService->updatePackage($id, $data['name'], $data['price'], $data['is_active']);

        return response()->json([
            'status' => true,
            'message' => 'Package updated successfully.',
            'data' => $package,
        ]);
    }
    
    public function detailPhase($id)
    {
        $package = Package::findOrFail($id);
        $phases = Phase::where('package_id', $id)->with('course')->get();
    
        return view('package.phase-detail', compact('package', 'phases'));
    }
    
    public function formCreatePhase($id)
    {
        $package = Package::findOrFail($id);
        $courses = Course::all();
    
        return view('package.phase-form', compact('package', 'courses'));
    }
    
    public function createPhase(CreatePhaseRequest $request)
    {
        $data = $request->validated();

        $phase = $this->packageService->createPhase($data['phase_title'], $data['description'], $data['course_id'], $data['package_id']);

        return response()->json([
            'status' => true,
            'message' => 'Phase created successfully.',
            'data' => $phase,
        ]);
    }


}
