<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/me', [UserController::class, 'getMe']);
    Route::post('/createUser', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/createDepartment', [DepartmentController::class, 'store']);
});

Route::post('/register', [UserController::class, 'register']);
Route::get('/activate/{activationToken}', function ($activationToken) {
    $needActiveUser = User::where('activation_token', $activationToken)->first();
    if ($needActiveUser) {
        $needActiveUser->update(['active' => true]);
        $needActiveUser->activation_token = null;
        $needActiveUser->save();
        return response()->json(['message' => 'Kích hoạt tài khoản thành công'], 200);
    } else {
        return response()->json(['message' => 'Mã kích hoạt bị lỗi hoặc không tồn tại'], 404);
    }
});
