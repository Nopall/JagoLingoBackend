<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use OpenGraph;

use Imagick;

class SettingService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createSetting(string $teks, string $content)
    {

        $setting = Setting::create([
            'teks' => $title,
            'content' => $url,
        ]);

        return $setting;
    }
    

    public function deleteSettingById($id)
    {
        $setting = Setting::where('id', $id);
        $setting->delete();
    }

    public function updateSetting($id, $teks, $content)
    {
        $setting = Setting::findOrFail($id);
        $setting->title = $teks;
        $setting->url = $content;

        $setting->save();

        return $setting;
    }
}
