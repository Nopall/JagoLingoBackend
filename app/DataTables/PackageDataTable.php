<?php

namespace App\DataTables;

use App\Models\Package;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class PackageDataTable extends DataTable
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
            ->addColumn('action', function ($row) {
                return view('package.package-action', ['id' => $row->id, 'isActive' => $row->is_active])->render();
            })
            ->addColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-label-success">Active</span>'
                    : '<span class="badge bg-label-secondary">Inactive</span>';
            })
            ->rawColumns(['is_active', 'action']);
    }

    public function query(Package $model)
    {
        return $model->newQuery()->select('package.*');
    }
}
