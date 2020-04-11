<?php

namespace App\Models\UserExercise;

use Illuminate\Database\Eloquent\Model;

class UserExercise extends Model
{
    protected $fillable = [
        'activity_id',
        'difficulty_type',
        'notify_at',
        'done_at',
        'status',
        'sets',
        'repetitions',
    ];

    const DIFFICULTY_TYPE_EASY = 0;
    const DIFFICULTY_TYPE_NORMAL = 1;
    const DIFFICULTY_TYPE_HARD = 2;
    const DIFFICULTY_TYPE_VERY_HARD = 2;

    const STATUS_WAITING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DONE = 2;
    const STATUS_ABANDONED = 3;
}
