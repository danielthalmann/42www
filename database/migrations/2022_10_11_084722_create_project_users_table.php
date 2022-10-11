<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('cursus_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->integer('occurrence')->nullable();
            $table->integer('final_mark')->nullable();
            $table->string('status')->nullable();
            $table->boolean('validated')->nullable();
            $table->integer('current_team_id')->nullable();
            $table->dateTime('marked_at')->nullable();
            $table->boolean('marked')->nullable();
            $table->dateTime('retriable_at')->nullable();
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
        Schema::dropIfExists('project_users');
    }
}
