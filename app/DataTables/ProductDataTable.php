<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class ProductDataTable extends DataTable
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
            ->addColumn('action', 'product.product-action')
            ->addColumn('commodity', function ($product) {
                return $product->commodity->name;
            })
            ->rawColumns(['action']);
    }

    public function query(Product $model)
    {
        return $model::with('commodity');
    }
}
