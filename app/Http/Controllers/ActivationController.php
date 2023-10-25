<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function activate($activationToken)
    {
        $needActiveUser = User::where('activation_token', $activationToken)->first();
        if ($needActiveUser) {
            $needActiveUser->update(['active' => true]);
            $needActiveUser->activation_token = null;
            $needActiveUser->roles()->attach(2);
            $needActiveUser->save();
            return response()->json(['message' => 'Kích hoạt tài khoản thành công'], 200);
        }
        return response()->json(['message' => 'Mã kích hoạt bị lỗi hoặc không tồn tại'], 404);
    }
}
