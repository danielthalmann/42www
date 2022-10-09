<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add42columnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('user42_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('login')->nullable();
            $table->string('url')->nullable();
            $table->string('phone')->nullable();
            $table->string('image_url')->nullable();
            $table->string('pool_month')->nullable();
            $table->string('pool_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user42_id','first_name','last_name','login','url','phone','image_url','pool_month','pool_year']);
        });
    }
}
