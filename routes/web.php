<?php

use App\Http\Controllers\ProjectInvitationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;



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
    return redirect('/projects');
});


Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::post('/projects/{project}/tasks', [ProjectTaskController::class, 'store']);
    Route::patch('/projects/{project}/tasks/{task}', [ProjectTaskController::class, 'update']);

    Route::post('/projects/{project}/invitations', [ProjectInvitationsController::class, 'store']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
