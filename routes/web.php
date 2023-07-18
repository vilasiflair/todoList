<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('todo', TodoController::class);

// Route::post('storeTaskData', 'TodoController@storeTaskData');
Route::get('getTaskData', [TodoController::class, 'getTaskData'])->name('getTaskData'); 
Route::post('storeTaskData', [TodoController::class, 'storeTaskData'])->name('storeTaskData'); 
Route::post('updateTaskData', [TodoController::class, 'updateTaskData'])->name('updateTaskData'); 




