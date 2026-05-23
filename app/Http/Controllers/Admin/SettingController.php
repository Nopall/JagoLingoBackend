<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SettingDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSettingRequest;
use App\Models\Setting;
use App\Services\SettingService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;
    protected $uploadService;

    public function __construct(SettingService $settingService, UploadService $uploadService)
    {
        $this->settingService = $settingService;
        $this->uploadService = $uploadService;
    }

    public function index(SettingDataTable $dataTable)
    {
        return $dataTable->render('setting.list');
    }

    public function formCreateSetting()
    {
        return view('setting.form');
    }

    public function formEditSetting(String $id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return redirect()->route('setting.index')->with('error', 'Setting not found.');
        }
        
        return view('setting.form', compact('setting'));
    }

    public function createSetting(CreateSettingRequest $request)
    {
        $data = $request->validated();

        $setting = $this->settingService->createSetting($data['teks'], $data['content']);

        return response()->json([
            'status' => true,
            'message' => 'Setting created successfully.',
            'data' => $setting,
        ]);
    }

    public function deleteSettingById(String $id)
    {
        $this->settingService->deleteSettingById($id);

        return response()->json([
            'status' => true,
            'message' => 'Setting deleted successfully.',
        ]);
    }

    public function updateSetting(Request $request, $id)
    {
        $data = $request->all();
        
        $setting = $this->settingService->updateSetting($id, $data['teks'], $data['content']);

        return response()->json([
            'status' => true,
            'message' => 'Setting updated successfully.',
            'data' => $setting,
        ]);
    }
}
