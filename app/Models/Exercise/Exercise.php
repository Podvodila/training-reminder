<?php

namespace App\Models\Exercise;

use App\Models\Activity\Activity;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'type',
        'user_id',
    ];

    const TYPE_OTHER = 0;
    const TYPE_SPORT = 1;

    const TYPES = [
        self::TYPE_OTHER => [
            'title' => 'Other',
        ],
        self::TYPE_SPORT => [
            'title' => 'Sport',
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

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->withPivot([
            'default_sets',
            'default_repetitions',
            'progression_type',
            'max_reps_per_set',
        ]);
    }
}
