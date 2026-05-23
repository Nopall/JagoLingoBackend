<?php

namespace App\DataTables;

use App\Models\Pembiayaan;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class PembiayaanDataTable extends DataTable
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
            ->addColumn('action', 'pembiayaan.pembiayaan-action')
            ->addColumn('image', function (Pembiayaan $pembiayaan) {
                return '<img src="' . $this->uploadService->getPublicUrl($pembiayaan->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Pembiayaan $model)
    {
        return $model->newQuery()->select('pembiayaan.*');
    }
}
