<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'user'], function() {
    // api register
    Route::post('/register', [App\Http\Controllers\UserAuthController::class, 'register']);
    // api login
    Route::post('/login', [App\Http\Controllers\UserAuthController::class, 'login']);
    Route::group(['middleware' => ['auth:user_api']], function() {
        // api logout
        Route::post('/logout', [App\Http\Controllers\UserAuthController::class, 'logout']);

        Route::get('/get-data', [App\Http\Controllers\TracerStudyController::class, 'getData']);
    });
});

Route::group(['prefix' => 'school'], function() {
    // api register
    Route::post('/register', [App\Http\Controllers\SchoolAuthController::class, 'register']);
    // api login
    Route::post('/login', [App\Http\Controllers\SchoolAuthController::class, 'login']);
    Route::group(['middleware' => ['auth:school_api']], function() {
        // api logout
        Route::post('/logout', [App\Http\Controllers\SchoolAuthController::class, 'logout']);
        Route::post('/add-tracer-study', [App\Http\Controllers\TracerStudyController::class, 'addTracerStudy']);
        Route::patch('/update-tracer-study/{id}', [App\Http\Controllers\TracerStudyController::class, 'updateTracerStudy']);
        Route::delete('/delete-tracer-study/{id}', [App\Http\Controllers\TracerStudyController::class, 'deleteTracerStudy']);
    });
});