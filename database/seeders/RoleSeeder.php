<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();
        Permission::truncate();
        Role::query()->create([
            'name' => 'Admin',
        ]);
        Role::query()->create([
            'name' => 'User',
        ]);
        Role::query()->create([
            'name' => 'Department Manager',
        ]);
        Role::query()->create([
            'name' => 'Employee',
        ]);
        Permission::query()->create([
            'name' => 'checkin',
        ]);
        Permission::query()->create([
            'name' => 'absent',
        ]);
        Permission::query()->create([
            'name' => 'create_user',
        ]);
        Permission::query()->create([
            'name' => 'create_role',
        ]);

        Role::query()->where('name', '=', 'User')->first()->permissions()->attach(1);
        Role::query()->where('name', '=', 'Admin')->first()->permissions()->attach([3, 4]);
        Role::query()->where('name', '=', 'DepartmentManager')->first()->permissions()->attach([3]);
        Role::query()->where('name', '=', 'Employee')->first()->permissions()->attach([2]);

    }
}
