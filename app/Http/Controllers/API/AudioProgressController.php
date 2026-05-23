<?php

namespace App\Http\Controllers\API;

use App\Models\UserAudioProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AudioProgressController extends BaseController
{
    // Update audio progress
    public function updateProgress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'total_listened_time' => 'required|integer|min:0',
        ]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
    
        // Cek apakah progres sudah ada dan lakukan update atau create
        $progress = UserAudioProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $request->lesson_id,
            ],
            [
                'total_listened_time' => $request->total_listened_time,
            ]
        );
    
        return $this->sendResponse($progress, 'Progress updated successfully.');
    }


    // Mark lesson as complete
    public function markAsComplete(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $lessonId = $request->lesson_id;
    
        $progress = UserAudioProgress::where('user_id', $userId)
                    ->where('lesson_id', $lessonId)
                    ->first();
    
        if ($progress) {
            $progress->is_completed = true;
            $progress->total_listened_time = $request->total_listened_time;
            $progress->save();
    
            return $this->sendResponse(null, 'Lesson marked as complete.');
        }
    
        return $this->sendError('Lesson progress not found.', 404);
    }



    // Analytics function
    public function getAnalytics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:daily,weekly,monthly',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $userId = Auth::id();
        $type = $request->type;

        switch ($type) {
            case 'daily':
                $data = $this->getDailyAnalytics($userId);
                break;

            case 'weekly':
                $data = $this->getWeeklyAnalytics($userId);
                break;

            case 'monthly':
                $data = $this->getMonthlyAnalytics($userId);
                break;

            default:
                return $this->sendError('Invalid type', [], 400);
        }

        return $this->sendResponse($data, 'Analytics data retrieved successfully.');
    }

    // Get daily analytics for the last 7 days
    private function getDailyAnalytics($userId)
    {
        $data = UserAudioProgress::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_listened_time) as total_time')
            )
            ->where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        return $data;
    }

    // Get weekly analytics for the last 4 weeks
    private function getWeeklyAnalytics($userId)
    {
        $data = UserAudioProgress::select(
                DB::raw('WEEK(created_at) as week'),
                DB::raw('SUM(total_listened_time) as total_time')
            )
            ->where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subWeeks(4))
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->orderBy('week', 'asc')
            ->get();

        return $data;
    }

    // Get monthly analytics for the last 6 months
    private function getMonthlyAnalytics($userId)
    {
        $data = UserAudioProgress::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_listened_time) as total_time')
            )
            ->where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        return $data;
    }
}
