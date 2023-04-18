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

// VERPLAATS DEZE ROUTES NAAR AUTH MIDDLEWARE
Route::get('/tasks/{userId}', [TaskController::class, 'index']);
Route::post('/add-task', [TaskController::class, 'addTask']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // VERPLAATS ALLE AUTH GUARD ROUTES HIERNAARTOE
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });