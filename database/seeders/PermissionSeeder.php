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
        self::createClientPermission();
        self::createSocialPermission();
        self::createContactPermission();

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

    static private function createClientPermission() {
        self::createPermission('0100', [
            'view_client_list',
            'view_client_detail',
            'create_client',
            'update_client',
            'delete_client',
            'update_client_status',
            'update_client_type',
        ]);
    }

    static private function createSocialPermission() {
        self::createPermission('0200', [
            'view_social_list',
            'view_social_detail',
            'create_social',
            'update_social',
            'delete_social',
        ]);
    }

    static private function createContactPermission() {
        self::createPermission('0300', [
            'view_contact_list',
            'view_contact_detail',
            'create_contact',
            'update_contact',
            'delete_contact',
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
