<?php

namespace App\Models\ActivityExercise;

use App\Models\Activity\Activity;
use App\Models\Exercise\Exercise;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityExercise extends Pivot
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
