<?php

namespace App\Models\Activity;

use App\Models\ActivityExercise\ActivityExercise;
use App\Models\Exercise\Exercise;
use App\Models\User\User;
use App\Models\UserExercise\UserExercise;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'interval_minutes',
        'available_time_from',
        'available_time_to',
        'exercises_per_time',
        'status',
        'user_id',
    ];

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    const STATUSES = [
        self::STATUS_ACTIVE => [
            'title' => 'Active',
        ],
        self::STATUS_INACTIVE => [
            'title' => 'Inactive',
        ],
    ];

    const PROGRESSION_TYPE_STATIC = 0;
    const PROGRESSION_TYPE_AUTO = 1;

    const PROGRESSION_TYPES = [
        self::PROGRESSION_TYPE_STATIC => [
            'title' => 'Static',
        ],
        self::PROGRESSION_TYPE_AUTO => [
            'title' => 'Auto',
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withPivot(['id', 'default_sets', 'default_repetitions', 'progression_type']);
    }

    public function user_exercises()
    {
        return $this->hasManyThrough(UserExercise::class, ActivityExercise::class, 'activity_id', 'activity_exercise_id', 'id', 'id');
    }

    public function createUserExercises()
    {
        $userExercisesToCreate = $this->getUserExercisesToCreate();
        foreach ($userExercisesToCreate as $userExercise) {
            UserExercise::create($userExercise);
        }
    }

    public function cancelFutureExercises()
    {
        $this->user_exercises()->where('notify_at', '>', Carbon::now())->whereNull('done_at')->delete();
    }

    private function getUserExercisesToCreate()
    {
        $userExercisesToCreate = [];
        $allExercises = $this->exercises;
        $existedUserExercises = $this->user_exercises()->orderBy('done_at', 'desc')->get()->unique('activity_exercise_id')->sortBy('done_at');

        foreach ($allExercises as $exercise) {
            if (in_array($exercise->pivot->id, $existedUserExercises->pluck('activity_exercise_id')->toArray())) {
                continue;
            }
            $userExerciseToCreate = [
                'activity_exercise_id' => $exercise->pivot->id,
                'notify_at' => now()->addMinutes($this->interval_minutes),
                'status' => UserExercise::STATUS_WAITING,
            ];

            if ($exercise->type === Exercise::TYPE_SPORT) {
                $userExerciseToCreate['sets'] = $exercise->pivot->default_sets;
                $userExerciseToCreate['repetitions'] = $exercise->pivot->default_repetitions;
            }
            array_push($userExercisesToCreate, $userExerciseToCreate);

            if (count($userExercisesToCreate) >= $this->exercises_per_time) {
                break;
            }
        }

        if (count($userExercisesToCreate) < $this->exercises_per_time) {
            foreach ($existedUserExercises as $existedUserExercise) {
                $userExerciseToCreate = [
                    'activity_exercise_id' => $existedUserExercise['activity_exercise_id'],
                    'notify_at' => now()->addMinutes($this->interval_minutes),
                    'status' => UserExercise::STATUS_WAITING,
                ];
                if ($existedUserExercise->activity_exercise->exercise->type === Exercise::TYPE_SPORT) {
                    //todo: handle max repetitions per set
                    $userExerciseToCreate['sets'] = $existedUserExercise['sets']; //todo
                    switch ($existedUserExercise['difficulty_type']) {
                        case UserExercise::DIFFICULTY_TYPE_EASY:
                            $userExerciseToCreate['repetitions'] = ceil($existedUserExercise['repetitions'] * 1.1);
                            break;
                        case UserExercise::DIFFICULTY_TYPE_NORMAL:
                            $userExerciseToCreate['repetitions'] = ceil($existedUserExercise['repetitions'] * 1.05);
                            break;
                        case UserExercise::DIFFICULTY_TYPE_VERY_HARD:
                            $userExerciseToCreate['repetitions'] = floor($existedUserExercise['repetitions'] * 0.95);
                            break;
                        case UserExercise::DIFFICULTY_TYPE_HARD:
                        default:
                            $userExerciseToCreate['repetitions'] = $existedUserExercise['repetitions'];
                            break;
                    }
                }
                array_push($userExercisesToCreate, $userExerciseToCreate);

                if (count($userExercisesToCreate) >= $this->exercises_per_time) {
                    break;
                }
            }
        }

        return $userExercisesToCreate;
    }
}
