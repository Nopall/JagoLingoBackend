<?php

namespace App\DataTables;

use App\Models\Menu;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class MenuDataTable extends DataTable
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
            ->addColumn('action', 'menu.menu-action')
            ->addColumn('icon', function (Menu $menu) {
                return '<img src="' . $this->uploadService->getPublicUrl($menu->icon) . '" width="100">';
            })
            ->rawColumns(['action', 'icon']);
    }

    public function query(Menu $model)
    {
        return $model->newQuery()->select('note_types.*');
    }
}
