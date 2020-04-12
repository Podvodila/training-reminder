<?php

namespace App\Models\Exercise;

use App\Models\Activity\Activity;
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

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->withPivot(['default_sets', 'default_repetitions', 'progression_type']);
    }
}
