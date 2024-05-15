<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demo = User::create([
            'name' => 'demo',
            'email' => 'demo@ibfkh.org',
            'password' => Hash::make('demo123')
        ]);
        $demo->assignRole('admin');
        $admin = User::create([
            'name' => 'admin',
            'email' => 'adminibf@admin.com',
            'password' => Hash::make('123456')
        ]);
        $admin->assignRole('admin');
        $admin->assignRole('teacher');

        $teacher = User::create([
            'name' => 'teacher',
            'email' => 'teacher@teacher.com',
            'password' => Hash::make('123456')
        ]);
        $teacher->assignRole('teacher');

        $trainer = User::create([
            'name' => 'Marya',
            'email' => 'rc@ibfkh.org',
            'password' => Hash::make('12345678')
        ]);
        $trainer->assignRole('admin');

        $ui_officer = User::create([
            'name' => 'Lyna',
            'email' => 'ui.officer@ibfkh.org',
            'password' => Hash::make('12345678')
        ]);
        $ui_officer->assignRole('admin');

        $student = User::create([
            'name' => 'student',
            'email' => 'student@student.com',
            'password' => Hash::make('123456')
        ]);
        $student->assignRole('student');

    }
}
