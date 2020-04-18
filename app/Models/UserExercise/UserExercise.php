<?php

namespace App\Models\UserExercise;

use App\Models\ActivityExercise\ActivityExercise;
use App\Models\Exercise\Exercise;
use Illuminate\Database\Eloquent\Model;

class UserExercise extends Model
{
    protected $fillable = [
        'activity_exercise_id',
        'difficulty_type',
        'notify_at',
        'is_notified',
        'done_at',
        'status',
        'sets',
        'repetitions',
        'notification_job_id',
    ];

    const DIFFICULTY_TYPE_EASY = 1;
    const DIFFICULTY_TYPE_NORMAL = 2;
    const DIFFICULTY_TYPE_HARD = 3;
    const DIFFICULTY_TYPE_VERY_HARD = 4;

    const STATUS_WAITING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DONE = 2;
    const STATUS_ABANDONED = 3;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity_exercise()
    {
        return $this->belongsTo(ActivityExercise::class);
    }
}
