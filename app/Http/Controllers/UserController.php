<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            // 'mobile' => $request->input('mobile'),
            // 'identity' => $request->input('identity'),
        ]);

        return response()->json(['message' => 'Thông tin đã được thêm thành công']);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Kiểm tra quyền admin
        if (auth()->user()->name !== 'admin') {
            return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Kiểm tra quyền admin
        if (auth()->user()->name !== 'admin') {
            return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này'], 403);
        }

        $user = Auth::user();

        $user->update($request->all());

        return response()->json(['message' => 'Thông tin người dùng đã được cập nhật thành công', 'user' => $user]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        // Kiểm tra quyền admin
        if (Auth::user()->name !== 'admin') {
            return response()->json(['message' => 'Bạn không có quyền xóa thông tin'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Thông tin người dùng đã được xóa thành công']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('User Token')->plainTextToken;
            return response()->json(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'login' => ['Tên đăng nhập hoặc mật khẩu không chính xác'],
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function getMe(Request $request)
    {
        return $request->user();
    }
}
