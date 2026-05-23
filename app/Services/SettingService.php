<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use OpenGraph;

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
            'teks' => $teks,
            'content' => $content,
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
        $setting->teks = $teks;
        $setting->content = $content;

        $setting->save();

        return $setting;
    }
}
