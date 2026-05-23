<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\DataTables\ProductImageDataTable;
use App\DataTables\UserDeviceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\CreateProductImageRequest;
use App\Models\User;
use App\Models\Commodity;
use App\Models\ProductImage;
use App\Models\UserDevice;
use App\Services\UserDeviceService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    protected $userService;
    protected $uploadService;

    public function __construct(UserDeviceService $userService)
    {
        $this->userService = $userService;
    }
    
    public function formCreateUserDevice(String $id)
    {
        return view('user-device.form', compact('id'));
    }
    
    
    public function index(String $id, UserDeviceDataTable $dataTable)
    {
        $product = User::where('id',  $id)->first();
        
        if (!$product) {
            return redirect()->route('user.list')->with('error', 'User Device not found.');
        }
        
        $dataTable->setId($id);

        return $dataTable->render('user-device.index', compact('id'));
    }
    
    public function createUserDevice(CreateUserDeviceRequest $request, $id)
    {
        $data = $request->validated();

        $product = $this->userService->createUserDevice($id, $data['device_id'], $data['device_type'], $data['device_brand'], $data['device_model'], $data['os_version'], $data['device_build_number']);

        return response()->json([
            'status' => true,
            'message' => 'User Device created successfully.',
            'data' => $product,
        ]);
    }
    
    public function formEditUserDevice(String $id, String $id_image)
    {
        $productimage = UserDevice::find($id_user_device);

        if (!$productimage) {
            return redirect()->route('master.user.list')->with('error', 'User Device not found.');
        }
    

        return view('user-device.form', compact('id'));
    }
    
    public function updateUserDevice(Request $request, $id)
    {
        $data = $request->all();
        
        $userDevice = $this->userService->updateUserDevice($id, $data['device_id'], $data['device_type'], $data['device_brand'], $data['device_model'], $data['os_version'], $data['device_build_number']);

        return response()->json([
            'status' => true,
            'message' => 'User Device updated successfully.',
            'data' => $product,
        ]);
    }
    
    public function deleteUserDeviceById(String $id)
    {
        $this->userService->deleteUserDeviceById($id);

        return response()->json([
            'status' => true,
            'message' => 'User Device deleted successfully.',
        ]);
    }
}
