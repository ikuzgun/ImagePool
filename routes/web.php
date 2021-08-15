<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Homecontroller;
use App\Http\Controllers\Imagecontroller;

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

Route::get('/', [Homecontroller::class, "index"]);
Route::post('/image/upload', [Imagecontroller::class, "uploadImage"])->name('image.upload');
Route::post('/image/delete', [Imagecontroller::class, "delete"])->name('image.delete');
Route::post('/image/all', [Imagecontroller::class, "all"])->name('image.all');
Route::post('/image/sort', [Imagecontroller::class, "sort"])->name('image.sort');
