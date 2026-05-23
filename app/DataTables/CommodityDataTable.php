<?php

namespace App\DataTables;

use App\Models\Commodity;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class CommodityDataTable extends DataTable
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
            ->addColumn('action', 'commodity.commodity-action')
            ->addColumn('image', function (Commodity $commodity) {
                return '<img src="' . $this->uploadService->getPublicUrl($commodity->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Commodity $model)
    {
        return $model->newQuery()->select('commodity.*');
    }
}
