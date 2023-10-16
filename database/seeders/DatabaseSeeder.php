<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::truncate();
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

        Permission::truncate();
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
        Permission::query()->create([
            'name' => 'create_department',
        ]);

        Role::query()->where('name', '=', 'User')->first()->permissions()->attach([1]);
        Role::query()->where('name', '=', 'Admin')->first()->permissions()->attach([1, 2, 3, 4, 5]);
        Role::query()->where('name', '=', 'Department Manager')->first()->permissions()->attach([3]);
        Role::query()->where('name', '=', 'Employee')->first()->permissions()->attach([2]);


        User::truncate();
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'firstname' => 'Duong',
            'lastname' => 'Dat',
            'mobile' => '0347022677',
            'indentity' => '026202001441',
            'active' => 'true',
        ]);
        User::query()->where('name', 'admin')->first()->roles()->attach([1, 2, 3, 4]);
    }
}
