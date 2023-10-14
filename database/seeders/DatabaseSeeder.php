<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        User::truncate();
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'firstname' => 'Duong',
            'lastname' => 'Dat',
            'mobile' => '0347022677',
            'indentity' => '026202001441'
        ]);

        User::query()->where('name', 'admin')->first()->roles()->attach([1,2,3,4]);
    }
}
