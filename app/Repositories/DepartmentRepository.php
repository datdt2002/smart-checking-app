<?php

namespace App\Repositories;

use App\Models\Department;

class DepartmentRepository
{
    public function getAllDepartments()
    {
        return Department::orderBy('name')->get();
    }

    public function getDepartmentById($id)
    {
        return Department::find($id);
    }

    public function createDepartment(array $data)
    {
        return Department::create($data);
    }

    public function updateDepartment(Department $department, array $data)
    {
        $department->update($data);
    }

    public function deleteDepartment(Department $department)
    {
        $department->delete();
    }
}
