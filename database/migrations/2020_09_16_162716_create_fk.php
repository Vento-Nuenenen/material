<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
           $table->foreign('group_id')->references('id')->on('groups')->nullOnDelete();
           $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });

        Schema::table('groups', function(Blueprint $table){
            $table->foreign('parent_group_id')->references('id')->on('groups')->nullOnDelete();
        });

        Schema::table('items', function(Blueprint $table){
            $table->foreign('group_id')->references('id')->on('groups');
        });

        Schema::table('categories', function(Blueprint $table){
            $table->foreign('group_id')->references('id')->on('groups')->nullOnDelete();
        });

        Schema::table('orders', function(Blueprint $table){
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
