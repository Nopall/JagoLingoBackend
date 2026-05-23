<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CourseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Imagick;

class CourseController extends Controller
{
    protected $courseService;
    protected $uploadService;

    public function __construct(CourseService $courseService, UploadService $uploadService)
    {
        $this->courseService = $courseService;
        $this->uploadService = $uploadService;
    }

    public function index(CourseDataTable $dataTable)
    {
        return $dataTable->render('course.list');
    }

    public function formCreateCourse()
    {
        return view('course.form');
    }

    public function formEditCourse(String $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return redirect()->route('course.index')->with('error', 'Course not found.');
        }

        return view('course.form', compact('course'));
    }

    public function createCourse(CreateCourseRequest $request)
    {
        $data = $request->validated();

        $course = $this->courseService->createCourse($data['title'], $data['description'], $data['is_premium']);

        return response()->json([
            'status' => true,
            'message' => 'Course created successfully.',
            'data' => $course,
        ]);
    }

    public function deleteCourseById(String $id)
    {
        $this->courseService->deleteCourseById($id);

        return response()->json([
            'status' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }

    public function updateCourse(Request $request, $id)
    {
        $data = $request->all();

        $course = $this->courseService->updateCourse($id, $data['title'], $data['description'], $data['is_premium']);

        return response()->json([
            'status' => true,
            'message' => 'Course updated successfully.',
            'data' => $course,
        ]);
    }
}
