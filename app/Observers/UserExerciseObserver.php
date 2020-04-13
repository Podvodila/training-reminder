<?php

namespace App\Observers;

use App\Jobs\UserExerciseTelegramNotification;
use App\Models\Activity\Activity;
use App\Models\UserExercise\UserExercise;

class UserExerciseObserver
{
    /**
     * Handle the user exercise "created" event.
     *
     * @param  UserExercise  $userExercise
     * @return void
     */
    public function created(UserExercise $userExercise)
    {
        UserExerciseTelegramNotification::dispatch($userExercise)->delay($userExercise->notify_at);
        //UserExerciseTelegramNotification::dispatch($userExercise); //TODO: uncomment for test
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  UserExercise  $userExercise
     * @return void
     */
    public function updated(UserExercise $userExercise)
    {
        if ($userExercise->isDirty('done_at')) {
            $activity = $userExercise->activity_exercise->activity;

            if (!$activity->user_exercises()->whereNull('done_at')->exists() && $activity->status === Activity::STATUS_ACTIVE) {
                $activity->createUserExercises();
            }
        }
    }
}
