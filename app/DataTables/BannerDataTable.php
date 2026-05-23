<?php

namespace App\DataTables;

use App\Models\Banner;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class BannerDataTable extends DataTable
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
            ->addColumn('action', 'banner.banner-action')
            ->addColumn('image', function (Banner $banner) {
                return '<img src="' . $this->uploadService->getPublicUrl($banner->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Banner $model)
    {
        return $model->newQuery()->select('banners.*');
    }
}
