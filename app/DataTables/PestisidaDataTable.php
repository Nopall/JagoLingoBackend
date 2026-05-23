<?php

namespace App\DataTables;

use App\Models\Pestisida;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class PestisidaDataTable extends DataTable
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
            ->addColumn('action', 'pestisida.pestisida-action')
            ->addColumn('photo', function (Pestisida $pestisida) {
                return '<img src="' . $this->uploadService->getPublicUrl($pestisida->photo) . '" width="100">';
            })
            ->rawColumns(['action', 'photo']);
    }

    public function query(Pestisida $model)
    {
        return $model->newQuery()->select('pestisida.*');
    }
}
