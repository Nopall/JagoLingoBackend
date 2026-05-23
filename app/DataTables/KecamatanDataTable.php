<?php

namespace App\DataTables;

use App\Models\Kecamatan;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class KecamatanDataTable extends DataTable
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
            ->addColumn('action', 'kecamatan.kecamatan-action')
            ->rawColumns(['action']);
    }

    public function query(Kecamatan $model)
    {
        return $model->newQuery()->select('subdistrict.*');
    }
}
