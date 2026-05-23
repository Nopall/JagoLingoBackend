<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'user.action')
            ->rawColumns(['action']);
    }

    public function query(User $model)
    {
        return $model->newQuery()->select('users.*')->where('type', 1);
    }
}
