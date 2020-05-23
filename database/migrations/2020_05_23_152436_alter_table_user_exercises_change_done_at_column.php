<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserExercisesChangeDoneAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exercises', function (Blueprint $table) {
            $table->renameColumn('done_at', 'finished_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_exercises', function (Blueprint $table) {
            $table->renameColumn('finished_at', 'done_at');
        });
    }
}
