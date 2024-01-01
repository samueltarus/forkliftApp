<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [App\Http\Controllers\ForkliftController::class, 'index']);
Route::get('/forklift/create', [App\Http\Controllers\ForkliftController::class, 'create']);
Route::post('/forklift/store', [App\Http\Controllers\ForkliftController::class, 'store']);
Route::get('/forklift/edit', [App\Http\Controllers\ForkliftController::class, 'edit']);
Route::post('/forklift/update', [App\Http\Controllers\ForkliftController::class, 'update']);
Route::get('/forklift/show', [App\Http\Controllers\ForkliftController::class, 'show']);
//Route::get('/admin/logs', [Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
