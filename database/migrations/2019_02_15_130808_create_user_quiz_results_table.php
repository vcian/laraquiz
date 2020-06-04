<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQuizResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_quiz_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->unsignedInteger("quiz_id");
            $table->foreign("quiz_id")->references('id')->on('quizzes');
            $table->json('total_selected_options')->nullable();
            $table->tinyInteger('total_attempted')->nullable();
            $table->tinyInteger('total_skipped')->nullable();
            $table->tinyInteger('total_wrong')->nullable();
            $table->tinyInteger('total_right')->nullable();
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
        Schema::dropIfExists('user_quiz_results');
    }
}
