<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_exercise_id')->nullable()->constrained('activity_exercise')->onDelete('cascade');
            $table->unsignedTinyInteger('difficulty_type')->nullable();
            $table->dateTime('notify_at');
            $table->boolean('is_notified')->default(false);
            $table->dateTime('done_at')->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('sets')->nullable();
            $table->unsignedTinyInteger('repetitions')->nullable();
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
        Schema::dropIfExists('user_exercises');
    }
}
