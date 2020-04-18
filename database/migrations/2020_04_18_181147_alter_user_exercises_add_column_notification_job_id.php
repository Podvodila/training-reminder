<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserExercisesAddColumnNotificationJobId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exercises', function (Blueprint $table) {
            $table->foreignId('notification_job_id')->nullable()->after('repetitions')->constrained('jobs')->onDelete('set null');
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
            $table->dropForeign(['notification_job_id']);
            $table->dropColumn('notification_job_id');
        });
    }
}
