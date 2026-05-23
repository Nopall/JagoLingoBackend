<?php 
namespace App\DataTables;

use App\Models\Course;
use Yajra\DataTables\Services\DataTable;
use App\Services\UploadService;

class CourseDataTable extends DataTable
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
            ->addColumn('action', 'course.course-action')
            ->addColumn('is_premium', function (Course $course) {
                return $course->is_premium ? 'Premium' : 'Not Premium';
            })
            ->rawColumns(['action', 'image_url']);
    }

    public function query(Course $model)
    {
        return $model->newQuery()->select('courses.*');
    }
}
