<?php

namespace App\Services;

use App\Models\Phase;
use App\Models\Lesson;
use Illuminate\Http\UploadedFile;

class PhaseService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function createPhase(string $phase_title, string $description, string $course)
    {

        $phase = Phase::create([
            'phase_title' => $phase_title,
            'description' => $description,
            'course_id' => $course
        ]);

        return $phase;
    }
    

    public function deletePhaseById($id)
    {
        $phase = Phase::where('id', $id);
        $phase->delete();
    }
    
    public function updatePhase($id, $phaseTitle, $description, $course)
    {
        $phase = Phase::findOrFail($id);
        $phase->phase_title = $phaseTitle;
        $phase->description = $description;
        $phase->course_id = $course;


        $phase->save();

        return $phase;
    }
    
    public function createPhaseLesson($id, $title, $type, UploadedFile $audioUrl, UploadedFile $imageUrl)
    {
        
        $audio = $this->uploadService->upload($audioUrl, 'lesson-audio');
        
        if($type == 'audio_image'){
            $audioImage = $this->uploadService->upload($imageUrl, 'lesson');
        }else{
            $audioImage = NULL;
        }

        $lesson = Lesson::create([
            'title' => $title,
            'type' => $type,
            'audio_url' => $audio,
            'image_url' => $audioImage,
            'phase_id' => $id,
        ]);

        return $lesson;
    }
    
    public function createPhaseLessonAudio($id, $title, $type, UploadedFile $audioUrl)
    {
        
        $audio = $this->uploadService->upload($audioUrl, 'lesson-audio');
        
        $audioImage = NULL;
    

        $lesson = Lesson::create([
            'title' => $title,
            'type' => $type,
            'audio_url' => $audio,
            'image_url' => $audioImage,
            'phase_id' => $id,
        ]);

        return $lesson;
    }
    
    public function updatePhaseLessonImage($id, $icon = null)
    {
        $product = Lesson::findOrFail($id);

        if ($icon instanceof UploadedFile) {
            $logoPath = $this->uploadService->upload($icon, 'product');
            $product->image = $logoPath;
        }

        $product->save();

        return $product;
    }
    
    public function deletePhaseLessonById($id)
    {
        $product = Lesson::where('id', $id);
        $product->delete();
    }
    
}
