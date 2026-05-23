<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBannerRequest;
use App\Models\Banner;
use App\Services\BannerService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected $bannerService;
    protected $uploadService;

    public function __construct(BannerService $bannerService, UploadService $uploadService)
    {
        $this->bannerService = $bannerService;
        $this->uploadService = $uploadService;
    }

    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('banner.list');
    }

    public function formCreateBanner()
    {
        return view('banner.form');
    }

    public function formEditBanner(String $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return redirect()->route('banner.index')->with('error', 'Banner not found.');
        }

        $banner->image = $this->uploadService->getPublicUrl($banner->image);

        return view('banner.form', compact('banner'));
    }

    public function createBanner(CreateBannerRequest $request)
    {
        $data = $request->validated();

        $banner = $this->bannerService->createBanner($data['name'], $data['description'], $data['image']);

        return response()->json([
            'status' => true,
            'message' => 'Banner created successfully.',
            'data' => $banner,
        ]);
    }

    public function deleteBannerById(String $id)
    {
        $this->bannerService->deleteBannerById($id);

        return response()->json([
            'status' => true,
            'message' => 'Banner deleted successfully.',
        ]);
    }

    public function updateBanner(Request $request, $id)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $icon = $request->file('image');
        } else {
            $icon = null;
        }

        $banner = $this->bannerService->updateBanner($id, $data['name'], $data['description'], $icon);

        return response()->json([
            'status' => true,
            'message' => 'Banner updated successfully.',
            'data' => $banner,
        ]);
    }
}
