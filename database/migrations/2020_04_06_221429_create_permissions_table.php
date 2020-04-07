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
        });

        // ACL
        DB::table('permissions')->insert(['title' => 'ACL Create', 'name' => 'acl-create']);
        DB::table('permissions')->insert(['title' => 'ACL Read', 'name' => 'acl-read']);
        DB::table('permissions')->insert(['title' => 'ACL Update', 'name' => 'acl-update']);
        DB::table('permissions')->insert(['title' => 'ACL Delete', 'name' => 'acl-delete']);        
        
        // User
        DB::table('permissions')->insert(['title' => 'User Create', 'name' => 'user-create']);
        DB::table('permissions')->insert(['title' => 'User Read', 'name' => 'user-read']);
        DB::table('permissions')->insert(['title' => 'User Update', 'name' => 'user-update']);
        DB::table('permissions')->insert(['title' => 'User Delete', 'name' => 'user-delete']);        

        // Foo
        DB::table('permissions')->insert(['title' => 'Foo Create', 'name' => 'foo-create']);
        DB::table('permissions')->insert(['title' => 'Foo Read', 'name' => 'foo-read']);
        DB::table('permissions')->insert(['title' => 'Foo Update', 'name' => 'foo-update']);
        DB::table('permissions')->insert(['title' => 'Foo Delete', 'name' => 'foo-delete']);        

        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->timestamps();
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
