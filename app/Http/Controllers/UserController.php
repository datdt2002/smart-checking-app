<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\User\StoreUserRequest;
use App\Models\User;
use App\Notifications\ActiveAccount;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index()
    {
        $currentUser = Auth::user();
        if ($currentUser->checkRole('Admin')) {
            $users = $this->userRepository->getAllUsers();
            return response()->json(['All users' => $users], 200);
        }
        if ($currentUser->checkRole('Department Manager')) {
            $users = $this->userRepository->getUsersByDepartmentId($currentUser->department_id);
            return response()->json(['All users' => $users], 200);
        }
        return response()->json(['message' => 'Bạn không có quyền này!'], 403);
    }

    public function test() {
        event(new MessageEvent('', "Create successfully"));
        return 1;
    }
    /**
     * Người có quyền tạo mới user sẽ tạo mới user, email đã xác thực luôn tại thời điểm tạo
     */
    public function store(StoreUserRequest $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->checkPermission('create_user')) {
            return response()->json(['message' => 'Bạn không có quyền tạo mới User!'], 403);
        }

        try {
            $newUser = $this->userRepository->createUser($request->validated());

            if ($currentUser->checkPermission('create_role')) {
                $this->userRepository->attachRole($newUser, $request->role);
            } elseif ($request->role) {
                return response()->json(['message' => 'Bạn không có quyền set role cho user!'], 403);
            } else {
                $this->userRepository->attachDefaultedRole($newUser);
            }

            return response()->json(['message' => 'Tạo mới người dùng thành công!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi tạo mới người dùng!'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Kiểm tra quyền admin
        if (!auth()->user()->checkRole('Admin')) {
            return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này'], 403);
        }

        try {
            $user = $this->userRepository->getUserById($id);

            if (!$user) {
                return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
            }

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi lấy thông tin người dùng!'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!auth()->check()) {
            return response()->json(['message' => 'Bạn cần đăng nhập để thực hiện thao tác này'], 401);
        }

        try {
            $user = Auth::user();

            // Kiểm tra quyền admin hoặc chính người dùng
            if (!auth()->user()->checkRole('Admin') && $user->id !== $request->user_id) {
                return response()->json(['message' => 'Bạn không có quyền thực hiện thao tác này'], 403);
            }

            $this->userRepository->updateUser($user, $request->all());

            return response()->json(['message' => 'Thông tin người dùng đã được cập nhật thành công', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi cập nhật thông tin người dùng!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = $this->userRepository->getUserById($id);
            // Kiểm tra quyền admin
            if (Auth::user()->name !== 'admin') {
                return response()->json(['message' => 'Bạn không có quyền xóa thông tin'], 403);
            }

            if (!$user) {
                return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
            }

            $this->userRepository->deleteUser($user);

            return response()->json(['message' => 'Thông tin người dùng đã được xóa thành công']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi khi xóa thông tin người dùng!'], 500);
        }
    }
}
