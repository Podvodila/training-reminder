<?php

namespace App\Models\Activity;

use App\Models\ActivityExercise\ActivityExercise;
use App\Models\Exercise\Exercise;
use App\Models\User\User;
use App\Models\UserExercise\UserExercise;
use App\Scopes\UserScope;
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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new UserScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withPivot([
            'id',
            'default_sets',
            'default_repetitions',
            'progression_type',
            'max_reps_per_set',
        ]);
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
        $this->user_exercises()->where('notify_at', '>', Carbon::now())->whereNull('finished_at')->delete();
    }

    private function getUserExercisesToCreate()
    {
        $userExercisesToCreate = [];
        $allExercises = $this->exercises;
        $existedUserExercises = $this
            ->user_exercises()
            ->orderBy('finished_at', 'desc')
            ->get()
            ->unique('activity_exercise_id')
            ->sortBy('finished_at');

        $existedActivityExerciseIds = $existedUserExercises->pluck('activity_exercise_id')->toArray();
        foreach ($allExercises as $exercise) {
            if (in_array($exercise->pivot->id, $existedActivityExerciseIds)) {
                continue;
            }
            $userExerciseToCreate = $this->generateExercise($exercise);
            array_push($userExercisesToCreate, $userExerciseToCreate);

            if (count($userExercisesToCreate) >= $this->exercises_per_time) {
                break;
            }
        }

        if (count($userExercisesToCreate) < $this->exercises_per_time) {
            foreach ($existedUserExercises as $existedUserExercise) {
                $userExerciseToCreate = $this->generateExercise($existedUserExercise->activity_exercise->exercise, $existedUserExercise);
                array_push($userExercisesToCreate, $userExerciseToCreate);

                if (count($userExercisesToCreate) >= $this->exercises_per_time) {
                    break;
                }
            }
        }

        return $userExercisesToCreate;
    }

    private function generateExercise(Exercise $exercise, UserExercise $userExercise = null)
    {
        $notifyAt = now()->addMinutes($this->interval_minutes);
        if (!$notifyAt->between($this->available_time_from, $this->available_time_to)) {
            $notifyToday = Carbon::createFromTimeString($this->available_time_from);
            $notifyAt = $notifyAt->lt($notifyToday) ? $notifyToday : $notifyToday->addDay();
        }
        $userExerciseToCreate = [
            'activity_exercise_id' => $exercise->pivot ? $exercise->pivot->id : $userExercise['activity_exercise_id'],
            'notify_at' => $notifyAt,
            'status' => UserExercise::STATUS_WAITING,
        ];

        if ($exercise->type === Exercise::TYPE_SPORT) {
            if ($userExercise) {
                $userExerciseToCreate['sets'] = $userExercise['sets'];
                $userExerciseToCreate['repetitions'] = $userExercise['repetitions'];
                if ($userExercise->activity_exercise->progression_type === Activity::PROGRESSION_TYPE_AUTO) {
                    $progressedData = $this->calculateAutoProgression($userExercise);

                    $userExerciseToCreate['sets'] = $progressedData['sets'];
                    $userExerciseToCreate['repetitions'] = $progressedData['repetitions'];
                }
            } else {
                $userExerciseToCreate['sets'] = $exercise->pivot->default_sets;
                $userExerciseToCreate['repetitions'] = $exercise->pivot->default_repetitions;
            }
        }
        return $userExerciseToCreate;
    }

    private function calculateAutoProgression($userExercise)
    {
        $result = [
            'sets' => $userExercise['sets'],
            'repetitions' => $userExercise['repetitions'],
        ];
        switch ($userExercise['difficulty_type']) {
            case UserExercise::DIFFICULTY_TYPE_EASY:
                $result['repetitions'] = ceil($result['repetitions'] * 1.1);
                break;
            case UserExercise::DIFFICULTY_TYPE_NORMAL:
                $result['repetitions'] = ceil($result['repetitions'] * 1.05);
                break;
            case UserExercise::DIFFICULTY_TYPE_VERY_HARD:
                $result['repetitions'] = floor($result['repetitions'] * 0.95);
                break;
        }

        $maxRepsPerSet = $userExercise->activity_exercise->max_reps_per_set;
        if ($maxRepsPerSet && $result['repetitions'] > $maxRepsPerSet) {
            $result['repetitions'] = ceil(($result['repetitions'] * $result['sets']) / ($result['sets'] + 1));
            $result['sets'] += 1;
        }

        return $result;
    }
}
