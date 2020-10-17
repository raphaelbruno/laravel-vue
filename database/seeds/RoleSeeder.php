<?php

use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * roles
         */
        $roles = [
            ['title' => 'Super User', 'name' => 'su', 'default' => 1, 'level' => 0],
            ['title' => 'Manager', 'name' => 'manager', 'default' => 0, 'level' => 1],
            ['title' => 'Common User', 'name' => 'common', 'default' => 0, 'level' => 2],
            ['title' => 'Public User', 'name' => 'public', 'default' => 0, 'level' => null],
        ];
        Role::insert($roles);
    }
}
