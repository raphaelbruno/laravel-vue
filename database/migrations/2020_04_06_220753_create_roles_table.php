<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('name', 50);
            $table->integer('level')->nullable();
            $table->boolean('default');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('roles')->insert(['title' => 'Super User', 'name' => 'su', 'default' => 0, 'level' => 0]);
        DB::table('roles')->insert(['title' => 'Manager', 'name' => 'manager', 'default' => 0, 'level' => 1]);
        DB::table('roles')->insert(['title' => 'Common User', 'name' => 'common', 'default' => 0, 'level' => 2]);
        DB::table('roles')->insert(['title' => 'Public User', 'name' => 'public', 'default' => 1]);

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
