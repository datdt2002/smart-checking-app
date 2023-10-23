<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUsersByDepartmentId($departmentId)
    {
        return User::where('department_id', $departmentId)->get();
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function updateUser(User $user, array $data)
    {
        $user->update($data);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
    }
    public function attachRole($user, $role)
    {
        $user->roles()->attach($role);
    }
    public function attachDefaultedRole($user)
    {
        $user->roles()->attach([2, 4]);
    }
}
