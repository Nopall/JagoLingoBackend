<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Spatie\PdfToImage\Pdf;
use OpenGraph;

use Imagick;

class CourseService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createCourse(string $title, string $description, string $is_premium)
    {
        
        $course = Course::create([
            'title' => $title,
            'description' => $description,
            'is_premium' => $is_premium,
        ]);

        return $course;
    }
    

    public function deleteCourseById($id)
    {
        $course = Course::where('id', $id);
        $course->delete();
    }

    public function updateCourse($id, $title, $description, $is_premium)
    {
        $course = Course::findOrFail($id);
        $course->title = $title;
        $course->description = $description;
        $course->is_premium = $is_premium;

        $course->save();

        return $course;
    }
}
