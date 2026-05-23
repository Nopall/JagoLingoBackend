<?php

namespace App\DataTables;

use App\Models\UserDevice;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class UserDeviceDataTable extends DataTable
{
    protected $uploadService;
    protected $id; // Tambahkan properti $id

    public function __construct()
    {
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'user-device.action')
            ->rawColumns(['action']);
    }

    public function query(UserDevice $model)
    {
        return $model->newQuery()->where('user_id', $this->id)->select('user_devices.*');
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
}
