<?php

namespace App\DataTables;

use App\Models\Leaflet;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class LeafletDataTable extends DataTable
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
            ->addColumn('action', 'leaflet.leaflet-action')
            ->addColumn('image', function (Leaflet $leaflet) {
                return '<img src="' . $this->uploadService->getPublicUrl($leaflet->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Leaflet $model)
    {
        return $model->newQuery()->select('leaflet.*');
    }
}
