<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableActivityExerciseAddMaxRepsPerSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_exercise', function (Blueprint $table) {
            $table->unsignedTinyInteger('max_reps_per_set')->nullable()->after('default_repetitions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_exercise', function (Blueprint $table) {
            $table->dropColumn('max_reps_per_set');
        });
    }
}
