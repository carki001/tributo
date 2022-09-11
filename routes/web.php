<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontendController::class, 'index']);
Route::get('/section/{alias}', [FrontendController::class, 'index']);
Route::get('/section', function () {
    return redirect('/');
});

Route::get('getHomeData', [FrontendController::class, 'getHomeData']);
Route::get('getSections', [FrontendController::class, 'getSections']);
Route::get('getSectionDetails/{alias}', [FrontendController::class, 'getSectionDetails']);

Route::get('reduceimg', [SectionController::class, 'reduceimg']);

// Backend
Route::get('/login', [BackendController::class, 'index']);
Route::get('/admin', [BackendController::class, 'index']);
Route::get('admin/users', [BackendController::class, 'index']);
Route::get('admin/settings', [BackendController::class, 'index']);
Route::get('admin/sections', [BackendController::class, 'index']);
Route::get('admin/galleries', [BackendController::class, 'index']);

Route::get('404', [BackendController::class, 'index']);
