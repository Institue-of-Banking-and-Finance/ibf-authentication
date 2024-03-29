<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'adminibf@admin.com',
            'password' => Hash::make('123456')
        ]);
        $admin->assignRole('admin');

        $teacher = User::create([
            'name' => 'teacher',
            'email' => 'teacher@teacher.com',
            'password' => Hash::make('123456')
        ]);
        $teacher->assignRole('teacher');

        $student = User::create([
            'name' => 'student',
            'email' => 'student@student.com',
            'password' => Hash::make('123456')
        ]);
        $student->assignRole('student');
    }
}
