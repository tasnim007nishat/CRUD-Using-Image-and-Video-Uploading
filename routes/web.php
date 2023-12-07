<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleCrudController; 
//use App\Http\Controllers\AuthController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [SampleCrudController::class, 'index']);
Route::post('/store', [SampleCrudController::class, 'store'])->name('store');
Route::get('/fetchall', [SampleCrudController::class, 'fetchAll'])->name('fetchAll');
Route::get('/edit', [SampleCrudController::class, 'edit'])->name('edit');
Route::put('/update', [SampleCrudController::class, 'update'])->name('update');
Route::delete('/delete', [SampleCrudController::class, 'delete'])->name('delete');