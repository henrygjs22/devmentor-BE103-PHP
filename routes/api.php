<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 括號內左邊是網址api/'uri'
// 可用group/prefix簡化
// Public routes
Route::group(['prefix' => 'events'], function() {
    Route::get('test', [EventController::class, 'indexN1']);
    Route::get('/', [EventController::class, 'index']);
    Route::get('{id}', [EventController::class, 'show']);
});
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'events'], function() {
    Route::post('/', [EventController::class, 'store']);
    Route::put('{id}', [EventController::class, 'update']);
    Route::delete('{id}', [EventController::class, 'delete']);
});
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });