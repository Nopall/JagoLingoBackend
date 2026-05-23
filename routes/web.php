<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\LeafletController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BudidayaController;
use App\Http\Controllers\Admin\PestisidaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CommodityController;
use App\Http\Controllers\Admin\PembiayaanController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PlanPlantController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\PhaseController;
use App\Http\Controllers\Admin\UserDeviceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PackageController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('auth.login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::group(['prefix' => 'master'], function () {
        Route::get('/car', [CarController::class, 'index'])->name('car.list');
        Route::get('/car/form', [CarController::class, 'formCreateBrand'])->name('car.form-create');
        Route::get('/car/form/{id}', [CarController::class, 'formEditBrand'])->name('car.form-edit');

        Route::post('/car/brand', [CarController::class, 'createCarBrand'])->name('car.create-brand');
        Route::delete('/car/brand/{id}', [CarController::class, 'deleteCarBrandById'])->name('car.delete-brand');
        Route::post('/car/brand/{id}', [CarController::class, 'updateCarBrand'])->name('car.update-brand');
        
        // Menu
        Route::get('/menu', [MenuController::class, 'index'])->name('menu.list');
        Route::get('/menu/form', [MenuController::class, 'formCreateMenu'])->name('menu.form-create');
        Route::get('/menu/form/{id}', [MenuController::class, 'formEditMenu'])->name('menu.form-edit');

        Route::post('/menu', [MenuController::class, 'createMenu'])->name('menu.create');
        Route::delete('/menu/{id}', [MenuController::class, 'deleteMenuById'])->name('menu.delete');
        Route::post('/menu/{id}', [MenuController::class, 'updateMenu'])->name('menu.update');
        
        // Commodity
        Route::get('/commodity', [CommodityController::class, 'index'])->name('master.commodity.list');
        Route::get('/commodity/form', [CommodityController::class, 'formCreateCommodity'])->name('commodity.form-create');
        Route::get('/commodity/form/{id}', [CommodityController::class, 'formEditCommodity'])->name('commodity.form-edit');

        Route::post('/commodity', [CommodityController::class, 'createCommodity'])->name('commodity.create');
        Route::delete('/commodity/{id}', [CommodityController::class, 'deleteCommodityById'])->name('commodity.delete');
        Route::post('/commodity/{id}', [CommodityController::class, 'updateCommodity'])->name('commodity.update');
        
        // Product
        Route::get('/product', [ProductController::class, 'index'])->name('master.product.list');
        Route::get('/product/form', [ProductController::class, 'formCreateProduct'])->name('product.form-create');
        Route::get('/product/form/{id}', [ProductController::class, 'formEditProduct'])->name('product.form-edit');
        Route::get('/product/image/{id}', [ProductController::class, 'imageEditProduct'])->name('product.image');
        Route::post('/product', [ProductController::class, 'createProduct'])->name('product.create');
        Route::delete('/product/{id}', [ProductController::class, 'deleteProductById'])->name('product.delete');
        Route::post('/product/{id}', [ProductController::class, 'updateProduct'])->name('product.update');
        
        Route::get('/product/image/{id}/form', [ProductController::class, 'formCreateProductImage'])->name('productimage.form-create');
        Route::post('/product/image/{id}/create', [ProductController::class, 'createProductImage'])->name('productimage.create');
        Route::get('/product/image/{id}/form/{id_image}', [ProductController::class, 'formEditProductImage'])->name('productimage.form-edit');
        Route::post('/product/image/{id}', [ProductController::class, 'updateProductImage'])->name('productimage.update');
        
        Route::delete('/product/image/{id}', [ProductController::class, 'deleteProductImageById'])->name('productimage.delete');
        
        // Leaflet
        Route::get('/leaflet', [LeafletController::class, 'index'])->name('master.leaflet.list');
        Route::get('/leaflet/form', [LeafletController::class, 'formCreateLeaflet'])->name('leaflet.form-create');
        Route::get('/leaflet/form/{id}', [LeafletController::class, 'formEditLeaflet'])->name('leaflet.form-edit');

        Route::post('/leaflet', [LeafletController::class, 'createLeaflet'])->name('leaflet.create');
        Route::delete('/leaflet/{id}', [LeafletController::class, 'deleteLeafletById'])->name('leaflet.delete');
        Route::post('/leaflet/{id}', [LeafletController::class, 'updateLeaflet'])->name('leaflet.update');
        
        // Budidaya
        Route::get('/budidaya', [BudidayaController::class, 'index'])->name('master.budidaya.list');
        Route::get('/budidaya/form', [BudidayaController::class, 'formCreateBudidaya'])->name('budidaya.form-create');
        Route::get('/budidaya/form/{id}', [BudidayaController::class, 'formEditBudidaya'])->name('budidaya.form-edit');

        Route::post('/budidaya', [BudidayaController::class, 'createBudidaya'])->name('budidaya.create');
        Route::delete('/budidaya/{id}', [BudidayaController::class, 'deleteBudidayaById'])->name('budidaya.delete');
        Route::post('/budidaya/{id}', [BudidayaController::class, 'updateBudidaya'])->name('budidaya.update');
        
        // Pestisida
        Route::get('/pestisida', [PestisidaController::class, 'index'])->name('master.pestisida.list');
        Route::get('/pestisida/form', [PestisidaController::class, 'formCreatePestisida'])->name('pestisida.form-create');
        Route::get('/pestisida/form/{id}', [PestisidaController::class, 'formEditPestisida'])->name('pestisida.form-edit');

        Route::post('/pestisida', [PestisidaController::class, 'createPestisida'])->name('pestisida.create');
        Route::delete('/pestisida/{id}', [PestisidaController::class, 'deletePestisidaById'])->name('pestisida.delete');
        Route::post('/pestisida/{id}', [PestisidaController::class, 'updatePestisida'])->name('pestisida.update');
        
        // Banner
        Route::get('/banner', [BannerController::class, 'index'])->name('master.banner.list');
        Route::get('/banner/form', [BannerController::class, 'formCreateBanner'])->name('banner.form-create');
        Route::get('/banner/form/{id}', [BannerController::class, 'formEditBanner'])->name('banner.form-edit');

        Route::post('/banner', [BannerController::class, 'createBanner'])->name('banner.create');
        Route::delete('/banner/{id}', [BannerController::class, 'deleteBannerById'])->name('banner.delete');
        Route::post('/banner/{id}', [BannerController::class, 'updateBanner'])->name('banner.update');
        
        // Pembiayaan
        Route::get('/pembiayaan', [PembiayaanController::class, 'index'])->name('master.pembiayaan.list');
        Route::get('/pembiayaan/form', [PembiayaanController::class, 'formCreatePembiayaan'])->name('pembiayaan.form-create');
        Route::get('/pembiayaan/form/{id}', [PembiayaanController::class, 'formEditPembiayaan'])->name('pembiayaan.form-edit');

        Route::post('/pembiayaan', [PembiayaanController::class, 'createPembiayaan'])->name('pembiayaan.create');
        Route::delete('/pembiayaan/{id}', [PembiayaanController::class, 'deletePembiayaanById'])->name('pembiayaan.delete');
        Route::post('/pembiayaan/{id}', [PembiayaanController::class, 'updatePembiayaan'])->name('pembiayaan.update');
        
        // Kecamatan
        Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('master.kecamatan.list');
        Route::get('/kecamatan/form', [KecamatanController::class, 'formCreateKecamatan'])->name('kecamatan.form-create');
        Route::get('/kecamatan/form/{id}', [KecamatanController::class, 'formEditKecamatan'])->name('kecamatan.form-edit');

        Route::post('/kecamatan', [KecamatanController::class, 'createKecamatan'])->name('kecamatan.create');
        Route::delete('/kecamatan/{id}', [KecamatanController::class, 'deleteKecamatanById'])->name('kecamatan.delete');
        Route::post('/kecamatan/{id}', [KecamatanController::class, 'updateKecamatan'])->name('kecamatan.update');
        
        Route::get('/news', [NewsController::class, 'index'])->name('master.news.list');
        Route::get('/news/form', [NewsController::class, 'formCreateNews'])->name('news.form-create');
        Route::get('/news/form/{id}', [NewsController::class, 'formEditNews'])->name('news.form-edit');

        Route::post('/news', [NewsController::class, 'createNews'])->name('news.create');
        Route::delete('/news/{id}', [NewsController::class, 'deleteNewsById'])->name('news.delete');
        Route::post('/news/{id}', [NewsController::class, 'updateNews'])->name('news.update');
        
        
        Route::get('/course', [CourseController::class, 'index'])->name('master.course.list');
        Route::get('/course/form', [CourseController::class, 'formCreateCourse'])->name('course.form-create');
        Route::get('/course/form/{id}', [CourseController::class, 'formEditCourse'])->name('course.form-edit');

        Route::post('/course', [CourseController::class, 'createCourse'])->name('course.create');
        Route::delete('/course/{id}', [CourseController::class, 'deleteCourseById'])->name('course.delete');
        Route::post('/course/{id}', [CourseController::class, 'updateCourse'])->name('course.update');
        
        Route::get('/phase', [PhaseController::class, 'index'])->name('master.phase.list');
        Route::get('/phase/lesson/{id}', [PhaseController::class, 'phaseEditLesson'])->name('phase.lesson');
        Route::get('/phase/form', [PhaseController::class, 'formCreatePhase'])->name('phase.form-create');
        Route::get('/phase/form/{id}', [PhaseController::class, 'formEditPhase'])->name('phase.form-edit');

        Route::post('/phase', [PhaseController::class, 'createPhase'])->name('phase.create');
        Route::delete('/phase/{id}', [PhaseController::class, 'deletePhaseById'])->name('phase.delete');
        Route::post('/phase/{id}', [PhaseController::class, 'updatePhase'])->name('phase.update');
        
        Route::get('/phase/lesson/{id}/form', [PhaseController::class, 'formCreatePhaseLesson'])->name('phaselesson.form-create');
        Route::post('/phase/lesson/{id}/create', [PhaseController::class, 'createPhaseLesson'])->name('phaselesson.create');
        Route::get('/phase/lesson/{id}/form/{id_image}', [PhaseController::class, 'formEditPhaseLesson'])->name('phaselesson.form-edit');
        Route::post('/phase/lesson/{id}', [PhaseController::class, 'updatePhaseLesson'])->name('phaselesson.update');
        
        Route::delete('/phase/lesson/{id}', [PhaseController::class, 'deletePhaseLessonById'])->name('phaselesson.delete');
        
        
        Route::get('/settings', [SettingController::class, 'index'])->name('master.setting.list');
        Route::get('/settings/form', [SettingController::class, 'formCreateSetting'])->name('setting.form-create');
        Route::get('/settings/form/{id}', [SettingController::class, 'formEditSetting'])->name('setting.form-edit');

        Route::post('/settings', [SettingController::class, 'createSetting'])->name('setting.create');
        Route::delete('/settings/{id}', [SettingController::class, 'deleteSettingById'])->name('setting.delete');
        Route::post('/settings/{id}', [SettingController::class, 'updateSetting'])->name('setting.update');
        
        
        Route::get('/subscription', [SubscriptionController::class, 'index'])->name('master.subscription.list');
        Route::get('/subscription/form', [SubscriptionController::class, 'formCreateSubscription'])->name('subscription.form-create');
        Route::get('/subscription/form/{id}', [SubscriptionController::class, 'formEditSubscription'])->name('subscription.form-edit');

        Route::get('/subscription/list', [SubscriptionController::class, 'index'])->name('master.subscription.list');
        Route::get('/subscription/{id}', [SubscriptionController::class, 'show'])->name('subscription.show');
        Route::post('/subscription/{id}/activate', [SubscriptionController::class, 'activateSubscription'])->name('subscription.activate');
        Route::post('/subscription/{id}/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');
        Route::delete('/subscription/{id}', [SubscriptionController::class, 'deleteSubscription'])->name('subscription.delete');
        
        Route::get('/package', [PackageController::class, 'index'])->name('master.package.list');
        Route::get('/package/form', [PackageController::class, 'formCreatePackage'])->name('package.form-create');
        Route::get('/package/form/{id}', [PackageController::class, 'formEditPackage'])->name('package.form-edit');

        Route::post('/package', [PackageController::class, 'createPackage'])->name('package.create');
        Route::delete('/package/{id}', [PackageController::class, 'deletePackageById'])->name('package.delete');
        Route::post('/package/{id}', [PackageController::class, 'updatePackage'])->name('package.update');

        Route::get('/package/{id}/phases', [PackageController::class, 'detailPhase'])->name('package.phase.detail');
        Route::get('/package/{id}/phases/form', [PackageController::class, 'formCreatePhase'])->name('package.phase.form-create');
        Route::post('/package/{id}/phase', [PackageController::class, 'createPhase'])->name('package.phase.create');
        Route::post('/package/{package_id}/phase/{id}', [PhaseController::class, 'updatePhase'])->name('package.phase.update');
        Route::get('/package/{package_id}/phase/form/{id}', [PhaseController::class, 'formEditPhase'])->name('package.phase.form-edit');


        
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/list', [UserController::class, 'index'])->name('user.list');

        Route::delete('/{id}', [UserController::class, 'deleteUserById'])->name('user.delete');
        Route::post('/{id}', [UserController::class, 'updateUser'])->name('user.update');
        Route::get('/form/{id}', [UserController::class, 'formEditUser'])->name('user.form-edit');
        
        Route::get('/user-device/{id}', [UserDeviceController::class, 'index'])->name('user.userdevice.list');
        Route::get('/user-device/form', [UserDeviceController::class, 'formCreateUserDevice'])->name('userdevice.form-create');
        Route::get('/user-device/form/{id}', [UserDeviceController::class, 'formEditUserDevice'])->name('userdevice.form-edit');

        Route::post('/user-device', [UserDeviceController::class, 'createUserDevice'])->name('userdevice.create');
        Route::delete('/user-device/{id}', [UserDeviceController::class, 'deleteUserDeviceById'])->name('userdevice.delete');
        Route::post('/user-device/{id}', [UserDeviceController::class, 'updateUserDevice'])->name('userdevice.update');
    });
});
