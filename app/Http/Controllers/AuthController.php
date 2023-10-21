<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Notifications\ActiveAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->checkActive()) {
                $token = $user->createToken('User Token')->plainTextToken;
                return response()->json(['token' => $token]);
            } else {
                Auth::logout();
                return response()->json(['message' => 'Tài khoản của bạn chưa được kích hoạt'], 403);
            }
        }

        return response()->json(['message' => 'Tên đăng nhập hoặc mật khẩu không chính xác'], 401);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => "Đã đăng nhập đâu mà đòi đăng xuất"], 401);
        }
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Đăng xuất thành công'], 200);
    }

    public function getMe(Request $request)
    {
        return $request->user();
    }

    public function register(StoreUserRequest $request)
    {
        $registerUser = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'remember_token' => Str::random(10),
            'activation_token' => Str::random(60),
        ]);
        $registerUser->notify(new ActiveAccount($registerUser));
    }
}
