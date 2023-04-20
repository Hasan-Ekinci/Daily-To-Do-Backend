<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TaskController;

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

Route::post('/login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/tasks/{userId}/{type?}', [TaskController::class, 'index']);
    Route::post('/add-task', [TaskController::class, 'addTask']);
    Route::get('/task/{userId}/{taskId}', [TaskController::class, 'show']);

    Route::post('/edit', [TaskController::class, 'editField']);
    Route::post('/edit/action', [TaskController::class, 'taskAction']);
    Route::post('/addsubtask', [TaskController::class, 'addSubtask']);

    Route::post('/logout', [AuthenticationController::class, 'logout']);
});