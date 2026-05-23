<?php

namespace App\DataTables;

use App\Models\ProductImage;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class ProductImageDataTable extends DataTable
{
    protected $uploadService;
    protected $id; // Tambahkan properti $id

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'product.productimage-action')
            ->addColumn('image', function (ProductImage $banner) {
                return '<img src="' . $this->uploadService->getPublicUrl($banner->image) . '" width="100">';
            })
            ->rawColumns(['action', 'image']);
    }

    public function query(ProductImage $model)
    {
        return $model->newQuery()->where('product_id', $this->id)->select('product_image.*');
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
}
