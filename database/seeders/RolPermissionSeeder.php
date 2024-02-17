<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRol = Role::create([
            'name' => 'admin',
        ]);

        $readerRol = Role::create([
            'name' => 'reader',
        ]);

        $permissions = [
            'read_form',
            'create_form',
            'delete_form',
            'edit_form',
            'update_form',
        ];

        foreach ($permissions as $permissionName) {
            Permission::create(['name' => $permissionName]);
        }

        $adminRol->givePermissionTo($permissions);

        $readerRol->givePermissionTo('read_form');


    }
}
