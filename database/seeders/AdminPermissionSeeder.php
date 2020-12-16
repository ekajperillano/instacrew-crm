<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Admin'],['admin' => true]);
        $permissions = Permission::get('id');
        $role->permissions()->sync($permissions);

        $role = Role::firstOrCreate(['name' => 'User'],['admin' => false]);
        $permissions = Permission::get('id');
        $role->permissions()->sync($permissions);
    }
}
