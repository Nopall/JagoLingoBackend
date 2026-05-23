<?php

namespace App\Services;

use App\Models\SurfaceArea;
use App\Models\SurfaceAreaImage;
use Illuminate\Http\UploadedFile;

class SurfaceAreaService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createSurfaceArea(string $commodity_item_id, string $subdistrict_id, string $plant, string $harvest, string $production, string $productivity, string $date)
    {

        $surfacearea = SurfaceArea::create([
            'commodity_item_id' => $commodity_item_id,
            'subdistrict_id' => $subdistrict_id,
            'plant' => $plant,
            'harvest' => $harvest,
            'production' => $production,
            'productivity' => $productivity,
            'date' => $date,
        ]);

        return $surfacearea;
    }

    public function deleteSurfaceAreaById($id)
    {
        $surfacearea = SurfaceArea::where('id', $id);
        $surfacearea->delete();
    }
    
    public function deleteSurfaceAreaImageById($id)
    {
        $surfacearea = SurfaceAreaImage::where('id', $id);
        $surfacearea->delete();
    }

    public function updateSurfaceArea($id, $commodity_item_id, $subdistrict_id, $plant, $harvest, $production, $productivity, $date)
    {
        $surfacearea = SurfaceArea::findOrFail($id);
        $surfacearea->commodity_item_id = $commodity_item_id;
        $surfacearea->subdistrict_id = $subdistrict_id;
        $surfacearea->plant = $plant;
        $surfacearea->harvest = $harvest;
        $surfacearea->production = $production;
        $surfacearea->productivity = $productivity;
        $surfacearea->date = $date;


        $surfacearea->save();

        return $surfacearea;
    }
}
