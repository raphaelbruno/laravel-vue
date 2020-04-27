<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * permissions
         */
        $permissions = [
            ['title' => 'Administrator Access', 'name' => 'admin'],
            ['title' => 'Roles Create', 'name' => 'roles-create'],
            ['title' => 'Roles View', 'name' => 'roles-view'],
            ['title' => 'Roles Update', 'name' => 'roles-update'],
            ['title' => 'Roles Delete', 'name' => 'roles-delete'],
            ['title' => 'Users Create', 'name' => 'users-create'],
            ['title' => 'Users View', 'name' => 'users-view'],
            ['title' => 'Users Update', 'name' => 'users-update'],
            ['title' => 'Users Delete', 'name' => 'users-delete'],
            ['title' => 'Foos Create', 'name' => 'foos-create'],
            ['title' => 'Foos View', 'name' => 'foos-view'],
            ['title' => 'Foos Update', 'name' => 'foos-update'],
            ['title' => 'Foos Delete', 'name' => 'foos-delete'],
        ];
        DB::table('permissions')->insert($permissions);
        
        /**
         * permission_role
         */
        $permission_role = [
            // Manager
            ['role_id' => 2, 'permission_id' => 1], // Administrator Access
            ['role_id' => 2, 'permission_id' => 6], // Users
            ['role_id' => 2, 'permission_id' => 7],
            ['role_id' => 2, 'permission_id' => 8],
            ['role_id' => 2, 'permission_id' => 9],
            ['role_id' => 2, 'permission_id' => 10], // Foos
            ['role_id' => 2, 'permission_id' => 11],
            ['role_id' => 2, 'permission_id' => 12],
            ['role_id' => 2, 'permission_id' => 13],
            
            // Common User
            ['role_id' => 3, 'permission_id' => 1], // Administrator Access
            ['role_id' => 3, 'permission_id' => 10], // Foos
            ['role_id' => 3, 'permission_id' => 11],
            ['role_id' => 3, 'permission_id' => 12],
            ['role_id' => 3, 'permission_id' => 13],
        ];
        DB::table('permission_role')->insert($permission_role);
    }
}