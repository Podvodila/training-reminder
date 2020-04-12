<?php

namespace App\Observers;

use App\Models\Activity\Activity;

class ActivityObserver
{
    /**
     * Handle the activity "updated" event.
     *
     * @param  Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        if ($activity->isDirty('status')) {
            if ($activity->status === Activity::STATUS_ACTIVE) {
                $activity->createUserExercises();
            } else {
                $activity->cancelFutureExercises();
            }
        }
    }
}
