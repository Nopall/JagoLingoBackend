<?php

namespace App\DataTables;

use App\Models\Budidaya;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class BudidayaDataTable extends DataTable
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
            ->addColumn('action', 'budidaya.budidaya-action')
            ->addColumn('image', function (Budidaya $budidaya) {
                return '<img src="' . $this->uploadService->getPublicUrl($budidaya->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(Budidaya $model)
    {
        return $model->newQuery()->select('budidaya_horti.*');
    }
}
