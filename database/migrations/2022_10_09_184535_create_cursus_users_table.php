<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursusUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursus_users', function (Blueprint $table) {
            $table->id();
            $table->integer('cursus_id')->nullable();
            $table->string('cursus_name')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('grade')->nullable();
            $table->float('level')->nullable();
            $table->dateTime('begin_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->dateTime('blackholed_at')->nullable();
            $table->boolean('has_coalition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursuses_users');
    }
}
