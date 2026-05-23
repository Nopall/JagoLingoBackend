<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use OpenGraph;

use Imagick;

class SubscriptionService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createSubscription(string $teks, string $content)
    {

        $setting = Subscription::create([
            'teks' => $title,
            'content' => $url,
        ]);

        return $setting;
    }
    

    public function deleteSubscriptionById($id)
    {
        $setting = Subscription::where('id', $id);
        $setting->delete();
    }

    public function updateSubscription($id, $active)
    {
        $setting = Subscription::findOrFail($id);
        $setting->is_active = $teks;

        $setting->save();

        return $setting;
    }
}
