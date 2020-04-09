<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('name', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        // ACL
        DB::table('permissions')->insert(['title' => 'Roles Create', 'name' => 'roles-create']);
        DB::table('permissions')->insert(['title' => 'Roles View', 'name' => 'roles-view']);
        DB::table('permissions')->insert(['title' => 'Roles Update', 'name' => 'roles-update']);
        DB::table('permissions')->insert(['title' => 'Roles Delete', 'name' => 'roles-delete']);        
        
        // User
        DB::table('permissions')->insert(['title' => 'Users Create', 'name' => 'users-create']);
        DB::table('permissions')->insert(['title' => 'Users View', 'name' => 'users-view']);
        DB::table('permissions')->insert(['title' => 'Users Update', 'name' => 'users-update']);
        DB::table('permissions')->insert(['title' => 'Users Delete', 'name' => 'users-delete']);        

        // Foo
        DB::table('permissions')->insert(['title' => 'Foos Create', 'name' => 'foos-create']);
        DB::table('permissions')->insert(['title' => 'Foos View', 'name' => 'foos-view']);
        DB::table('permissions')->insert(['title' => 'Foos Update', 'name' => 'foos-update']);
        DB::table('permissions')->insert(['title' => 'Foos Delete', 'name' => 'foos-delete']);        

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Manager
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 5]); // User
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 6]);
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 7]);
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 8]);        

        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 9]); // Foo
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 10]);
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 11]);
        DB::table('permission_role')->insert(['role_id' => 2, 'permission_id' => 12]);        

        // Common User
        DB::table('permission_role')->insert(['role_id' => 3, 'permission_id' => 9]); // Foo
        DB::table('permission_role')->insert(['role_id' => 3, 'permission_id' => 10]);
        DB::table('permission_role')->insert(['role_id' => 3, 'permission_id' => 11]);
        DB::table('permission_role')->insert(['role_id' => 3, 'permission_id' => 12]);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
}
