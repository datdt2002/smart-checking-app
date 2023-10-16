<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use Carbon\Carbon;
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
        $currentUser = Auth::user();
        if ($currentUser->checkRole('Admin') || $currentUser->checkRole('Department manager')) {
            $users = User::all();
            return response()->json(['All users' => $users], 200);
        }
        return response()->json(['message' => 'Bạn không có quyền này!'], 403);
    }

    /**
     * Người có quyền tạo mới user sẽ tạo mới user, email đã xác thực luôn tại thời điểm tạo
     */
    public function store(StoreUserRequest $request)
    {
        $currentUser = Auth::user();
        if ($currentUser->checkPermission('create_user')) {
            $newUser = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'active' => true,
            ]);

            // Gán vai trò cho người dùng mới (2-User, 4-Employee)
            $newUser->roles()->attach([2, 4]);

            return response()->json(['message' => 'Tạo mới người dùng thành công!'], 201);
        }
        return response()->json(['message' => 'Bạn không có quyền!'], 403);
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
            // xác thực đăng nhập
            $user = Auth::user();
            if ($user->checkActive()) {
                $token = $user->createToken('User Token')->plainTextToken;
                return response()->json(['token' => $token]);
            } //Không có quyền checkin thì logout
            else {
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
}
