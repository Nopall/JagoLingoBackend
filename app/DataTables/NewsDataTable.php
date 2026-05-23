<?php

namespace App\DataTables;

use App\Models\News;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class NewsDataTable extends DataTable
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
            ->addColumn('action', 'news.news-action')
            ->addColumn('image_url', function (News $news) {
                return '<img src="' . $news->image_url . '" width="100">';
            })
            ->rawColumns(['action', 'image_url']);
    }

    public function query(News $model)
    {
        return $model->newQuery()->select('news.*');
    }
}
