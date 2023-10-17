<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $excludedDepartmentName = 'Admin Department';
        $departments = $this->departmentRepository->getAllDepartmentsExcept($excludedDepartmentName);
        return response()->json(['departments' => $departments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $currentUser = Auth::user();
        if ($currentUser->checkPermission('create_department')) {
            $data = [
                'name' => $request->input('name'),
            ];
            $department = $this->departmentRepository->createDepartment($data);
            return response()->json(['message' => 'Tạo mới phòng ban thành công!'], 201);
        }
        return response()->json(['message' => 'Bạn không có quyền!'], 403);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $department = $this->departmentRepository->getDepartmentById($id);

        if (!$department) {
            return response()->json(['message' => 'Không tìm thấy bộ phận'], 404);
        }

        return response()->json(['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = $this->departmentRepository->getDepartmentById($id);

        if (!$department) {
            return response()->json(['message' => 'Không tìm thấy bộ phận'], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|max:50',
            //Thêm các quy tắc xác thực cho các trường khác nếu cần
        ]);

        $data = $request->all();
        $this->departmentRepository->updateDepartment($department, $data);

        return response()->json(['department' => $department], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $department = $this->departmentRepository->getDepartmentById($id);

        if (!$department) {
            return response()->json(['message' => 'Không tìm thấy bộ phận'], 404);
        }

        $this->departmentRepository->deleteDepartment($department);

        return response()->json(['message' => 'Bộ phận đã được xóa'], 204);
    }
}
