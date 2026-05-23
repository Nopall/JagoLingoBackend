<?php

namespace App\DataTables;

use App\Models\Phase;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class PhaseDataTable extends DataTable
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
            ->addColumn('action', 'phase.phase-action')
            ->addColumn('course', function ($phase) {
                return $phase->course->title;
            })
            ->rawColumns(['action']);
    }

    public function query(Phase $model)
    {
        return $model::with('course');
    }
}
