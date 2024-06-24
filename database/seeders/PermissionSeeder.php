<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-order',
            'edit-order',
            'delete-order',
            'view-order',
            'create-product',
            'edit-product',
            'delete-product',
            'view-product',
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
         ];

         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
