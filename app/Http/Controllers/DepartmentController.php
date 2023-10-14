<?php

namespace App\Http\Controllers;

use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();
        return response()->json(['departments' => $departments]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create([
            'name' => $request->input('name'),
        ]);
        return response()->json(['department' => $department], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $department = Department::find($id);

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
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Không tìm thấy bộ phận'], 404);
        }

        $this->validate($request, [
            'name' => 'required|string|max:50',
            //Thêm các quy tắc xác thực cho các trường khác nếu cần
        ]);

        $department->update($request->all());

        return response()->json(['department' => $department], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Không tìm thấy bộ phận'], 404);
        }

        $department->delete();

        return response()->json(['message' => 'Bộ phận đã được xóa'], 204);
    }
}
