<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [UserController::class, 'getMe']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

Route::group(['middleware' => 'admin_access'], function () {
    Route::post('/users', 'UserController@store');
    Route::put('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@destroy');
});

Route::apiResource('departments', DepartmentController::class);
