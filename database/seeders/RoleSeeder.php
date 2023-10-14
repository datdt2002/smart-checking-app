<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::query()->create([
            'name' => 'Admin',
        ]);
        \App\Models\Role::query()->create([
            'name' => 'User',
        ]);
        \App\Models\Role::query()->create([
            'name' => 'Department Manager',
        ]);
        \App\Models\Role::query()->create([
            'name' => 'Employee',
        ]);
    }
}
