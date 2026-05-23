<?php

namespace App\DataTables;

use App\Models\Setting;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class SettingDataTable extends DataTable
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
            ->addColumn('action', 'setting.setting-action')
            ->rawColumns(['action']);
    }

    public function query(Setting $model)
    {
        return $model->newQuery()->select('settings.*');
    }
}
