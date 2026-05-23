<?php

namespace App\DataTables;

use App\Models\CarBrand;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class CarBrandDataTable extends DataTable
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'car.car-brand-action')
            ->addColumn('logo', function (CarBrand $carBrand) {
                return '<img src="' . $this->uploadService->getPublicUrl($carBrand->logo) . '" width="100">';
            })
            ->rawColumns(['action', 'logo']);
    }

    public function query(CarBrand $model)
    {
        return $model->newQuery()->select('car_brands.*');
    }
}
