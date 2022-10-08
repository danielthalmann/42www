<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus', function (Blueprint $table) {
            $table->id();
            $table->integer('campus_id');
            $table->string('name');
            $table->string('time_zone');
            $table->integer('users_count');
            $table->integer('vogsphere_id');
            $table->string('country');
            $table->string('address');
            $table->string('zip');
            $table->string('city');
            $table->string('website');
            $table->string('facebook');
            $table->string('twitter');
            $table->boolean('active');
            $table->boolean('public');
            $table->string('email_extension');
            $table->string('default_hidden_phone');
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
        Schema::dropIfExists('campus');
    }
}
