<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Models\User;
use \App\Http\Controllers\AuthController;
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

Route::get("/test", [UserController::class, "test"]);

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    //auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'getMe']);

    Route::post('/createUser', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::post('/createDepartment', [DepartmentController::class, 'store']);
});


Route::get('/departments', [DepartmentController::class, 'index']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/activate/{activationToken}', [ActivationController::class, 'activate']);
