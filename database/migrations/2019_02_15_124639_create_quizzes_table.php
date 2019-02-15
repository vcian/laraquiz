<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("admin_id");
            $table->foreign("admin_id")->references('id')->on('admins');
            $table->string('quiz_name');
            $table->string('slug');
            $table->time('time_limit');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->boolean('status')->default(1)->comment("0-Inactive, 1-Active");
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
        Schema::dropIfExists('quizzes');
    }
}
