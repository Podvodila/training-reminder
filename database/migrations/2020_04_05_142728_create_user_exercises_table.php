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
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedTinyInteger('difficulty_type')->nullable();
            $table->dateTime('notify_at');
            $table->dateTime('done_at')->nullable();
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('sets');
            $table->unsignedTinyInteger('repetitions');
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
