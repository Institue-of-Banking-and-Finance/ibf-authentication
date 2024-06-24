<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $education =  Role::create(['name' => 'education', 'guard_name' => 'api']);
        $education -> givePermissionTo([
            'create-product',
            'edit-product',
            'delete-product',
            'view-product'
        ]);
        $finance =  Role::create(['name' => 'finance', 'guard_name' => 'api']);
        $finance -> givePermissionTo([
            'create-order',
            'edit-order',
            'delete-order',
            'view-order'
        ]);


        $admin  =  Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $teacher = Role::create(['name' => 'teacher' , 'guard_name' => 'api']);
        $student = Role::create(['name' => 'student', 'guard_name' => 'api']);
        $human_resources = Role::create(['name' => 'HR', 'guard_name' => 'api']);


        $admin -> givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
        ]);

        $teacher -> givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
        ]);

        $student -> givePermissionTo([
            'create-user',
        ]);

        $human_resources -> givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
        ]);


    }
}
