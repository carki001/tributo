<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AttachmentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout',  [AuthController::class, 'logout']);
        Route::get('profile',  [AuthController::class, 'profile']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['middleware' => 'scope:admin,user'], function () {
        Route::get('sectionList',  [SectionController::class, 'index']);
        Route::post('updateSection', [SectionController::class, 'update']);
        Route::post('storeSection',  [SectionController::class, 'store']);
        Route::delete('deleteSection/{id}',  [SectionController::class, 'delete']);
        Route::get('getMaxPosition',  [SectionController::class, 'getMaxPosition']);

        Route::get('getSectionGalleries', [AttachmentController::class, 'index']);
        Route::post('uploadSectionGalleryImage', [AttachmentController::class, 'store']);
        Route::delete('deleteSectionGalleryImage/{id}',  [AttachmentController::class, 'delete']);
    });

    Route::group(['middleware' => 'scope:admin'], function () {
        Route::get('userList',  [UserController::class, 'index'])->name('users');
        Route::post('storeUser',  [UserController::class, 'store']);
        Route::put('updateUser/{id}', [UserController::class, 'update']);
        Route::delete('deleteUser/{id}',  [UserController::class, 'delete']);
        Route::get('userRoles', [UserController::class, 'getUserRoles']);

        Route::get('settings', [SettingController::class, 'index']);
        Route::put('updateSetting', [SettingController::class, 'update']);
        Route::get('getHeaderImages', [SettingController::class, 'getHeaderImages']);
        Route::post('uploadHeaderImage', [SettingController::class, 'uploadHeaderImage']);
        Route::delete('deleteHeaderImage/{model_name}',  [SettingController::class, 'delete']);
    });
});
