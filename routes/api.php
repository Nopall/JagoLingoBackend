<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\SubscriptionController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AudioProgressController;
use App\Http\Controllers\Auth\PasswordResetController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function(){

    Route::post('register', 'register');
    Route::post('login', 'login');

});


Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('phase/list', [CourseController::class, 'listPhase'])->name('phase.list');
    Route::get('package/list', [CourseController::class, 'listPackage'])->name('package.list');
    
    Route::get('lesson/audio/list', [CourseController::class, 'listLessonAudio'])->name('lesson.audio.list');
    
    Route::get('lesson/image/list', [CourseController::class, 'listLessonAudioImage'])->name('lesson.image.list');
    
    Route::get('admin', [UserController::class, 'listAdmin'])->name('admin.list');
    
    Route::post('user/device', [RegisterController::class, 'loginUserDevice']);
    
    // Route untuk Subscription
    Route::get('subscription/status', [SubscriptionController::class, 'status'])->name('subscription.status');
    Route::post('subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    
    // Route untuk memulai pembayaran
    Route::post('payment/start', [PaymentController::class, 'startPayment_'])->name('payment.start_');
    
     Route::post('payment/start_', [PaymentController::class, 'startPayment'])->name('payment.start');
    
    Route::post('audio-progress/update', [AudioProgressController::class, 'updateProgress']);
    Route::post('audio-progress/complete', [AudioProgressController::class, 'markAsComplete']);
    
    Route::get('dashboard', [AudioProgressController::class, 'getAnalytics']);
    
    Route::post('edit-profile', [RegisterController::class, 'editProfile']);
    Route::post('edit-password', [RegisterController::class, 'editPassword']);


});

// Route publik untuk callback payment dari iPaymu
Route::post('payment/callback', [PaymentController::class, 'paymentCallback'])->name('ipaymu.callback');

Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');

Route::get('/setting', [RegisterController::class, 'getSettingContent'])->name('setting.content');
