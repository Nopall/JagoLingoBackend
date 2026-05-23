<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Phase;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use OpenGraph;

class PackageService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createPackage(string $name, string $price, string $is_active)
    {

        $setting = Package::create([
            'name' => $name,
            'price' => $price,
            'is_active' => $is_active
        ]);

        return $setting;
    }
    
    public function createPhase(string $phase_title, string $description, string $course, string $package)
    {

        $phase = Phase::create([
            'phase_title' => $phase_title,
            'description' => $description,
            'course_id' => $course,
            'package_id' => $package
        ]);

        return $phase;
    }

    public function deleteSubscriptionById($id)
    {
        $setting = Subscription::where('id', $id);
        $setting->delete();
    }

    public function updateSubscription($id, $price, $active)
    {
        $setting = Subscription::findOrFail($id);
        $setting->price = $price;
        $setting->is_active = $is_active;

        $setting->save();

        return $setting;
    }
}
