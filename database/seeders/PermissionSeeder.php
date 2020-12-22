<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        self::createUserPermission();

        $role = Role::firstOrCreate(['name' => 'Admin'],['admin' => true]);
        $permissions = Permission::get('id');
        $role->permissions()->sync($permissions);
    }

    static private function createUserPermission() {
        self::createPermission('0000', [
            'view_user_list',
            'invite_user',
            'update_user',
        ]);
    }

    static private function createPermission($moduleCode, $permissions) {
        foreach($permissions as $index => $permission ) {
            Permission::firstOrCreate(
                ['code'  => $permission],[
                    'order_code' => $moduleCode.str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'description' => 'Allows user to ' . str_replace('_', ' ', $permission)
                ]
            );
        }
    }
}
