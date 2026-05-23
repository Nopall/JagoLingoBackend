<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PhaseDataTable;
use App\DataTables\PhaseLessonDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePhaseRequest;
use App\Http\Requests\CreatePhaseLessonRequest;
use App\Models\Phase;
use App\Models\Course;
use App\Services\PhaseService;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Imagick;

class PhaseController extends Controller
{
    protected $phaseService;

    public function __construct(PhaseService $phaseService, UploadService $uploadService)
    {
        $this->phaseService = $phaseService;
        $this->uploadService = $uploadService;
    }

    public function index(PhaseDataTable $dataTable)
    {
        return $dataTable->render('phase.list');
    }

    public function formCreatePhase()
    {
        $courses = Course::all();

        return view('phase.form', compact('courses'));
    }

    public function formEditPhase(String $id)
    {
        $phase = Phase::find($id);
        $courses = Course::all();


        if (!$phase) {
            return redirect()->route('phase.index')->with('error', 'Phase not found.');
        }

        return view('phase.form', compact('phase', 'courses'));
    }

    public function createPhase(CreatePhaseRequest $request)
    {
        $data = $request->validated();

        $phase = $this->phaseService->createPhase($data['phase_title'], $data['description'], $data['course_id']);

        return response()->json([
            'status' => true,
            'message' => 'Phase created successfully.',
            'data' => $phase,
        ]);
    }

    public function deletePhaseById(String $id)
    {
        $this->phaseService->deletePhaseById($id);

        return response()->json([
            'status' => true,
            'message' => 'Phase deleted successfully.',
        ]);
    }
    

    public function updatePhase(Request $request, $id)
    {
        $data = $request->all();

        $phase = $this->phaseService->updatePhase($id, $data['phase_title'], $data['description'], $data['course_id']);

        return response()->json([
            'status' => true,
            'message' => 'Phase updated successfully.',
            'data' => $phase,
        ]);
    }
    
    public function createPhaseLesson(Request $request, $id)
    {
        $data = $request->all();
        
        $type = $data['type'];
        
        if($type == 'audio'){
            $phase = $this->phaseService->createPhaseLessonAudio($id, $data['title'], $type, $data['audio']);

        }else{
            $phase = $this->phaseService->createPhaseLesson($id, $data['title'], $type, $data['audio'], $data['image']);

        }


        return response()->json([
            'status' => true,
            'message' => 'Phase Lesson created successfully.',
            'data' => $phase,
        ]);
    }
    
    public function phaseEditLesson(String $id, PhaseLessonDataTable $dataTable)
    {
        $product = Phase::with('lessons')->where('id',  $id)->first();

        if (!$product) {
            return redirect()->route('master.phase.list')->with('error', 'Phase not found.');
        }
        
        $dataTable->setId($id);

        return $dataTable->render('phase.lesson', compact('id'));
    }
    
    public function formCreatePhaseLesson(String $id)
    {

        return view('phase.phaselessonform', compact('id'));
    }
    
    public function deletePhaseLessonById(String $id)
    {
        $this->phaseService->deletePhaseLessonById($id);

        return response()->json([
            'status' => true,
            'message' => 'Phase Lesson deleted successfully.',
        ]);
    }
    
    public function formEditPhaseLesson(String $id, String $id_image)
    {
        $phaseimage = Lesson::find($id_image);

        if (!$phaseimage) {
            return redirect()->route('master.phase.list')->with('error', 'Product not found.');
        }
        
        $phaseimage->image = $this->uploadService->getPublicUrl($phaseimage->image);


        return view('phase.phaseimageform', compact('phaseimage', 'id'));
    }
    
    public function updatePhaseLesson(Request $request, $id)
    {
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $icon = $request->file('image');
        } else {
            $icon = null;
        }

        $phase = $this->phaseService->updatePhaseLesson($id, $icon);

        return response()->json([
            'status' => true,
            'message' => 'Phase Lesson updated successfully.',
            'data' => $phase,
        ]);
    }
}
