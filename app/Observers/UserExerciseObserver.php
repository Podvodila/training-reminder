<?php

namespace App\Observers;

use App\Jobs\UserExerciseTelegramNotification;
use App\Models\Activity\Activity;
use App\Models\UserExercise\UserExercise;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Bus\Dispatcher;

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
        $this->createNotification($userExercise);
    }

    /**
     * Handle the user exercise "updated" event.
     *
     * @param  UserExercise  $userExercise
     * @return void
     */
    public function updated(UserExercise $userExercise)
    {
        if ($this->isNewExercisesShouldBeCreated($userExercise)) {
            $activity = $userExercise->activity_exercise->activity;

            $activity->createUserExercises();
        }
    }

    private function isNewExercisesShouldBeCreated(UserExercise $userExercise)
    {
        $result = $userExercise->isDirty('done_at');
        if (!$result) {
            $result = $userExercise->isDirty('status') && $userExercise->status === UserExercise::STATUS_ABANDONED;
        }
        if ($result) {
            $activity = $userExercise->activity_exercise->activity;
            $result = !$activity
                    ->user_exercises()
                    ->where(function ($query) {
                        return $query->whereNull('done_at')
                            ->where('status', '!=', UserExercise::STATUS_ABANDONED);
                    })
                    ->exists()
                && $activity->status === Activity::STATUS_ACTIVE;
        }
        return $result;
    }

    private function createNotification(UserExercise $userExercise)
    {
        $job = (new UserExerciseTelegramNotification($userExercise))->delay($userExercise->notify_at);
        $jobId = app(Dispatcher::class)->dispatch($job);
        $userExercise->notification_job_id = $jobId;
        $userExercise->save();
    }
}
