<?php

namespace App\Models\Activity;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'interval_minutes',
        'available_time_from',
        'available_time_to',
        'status',
        'user_id',
    ];

    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;

    const PROGRESSION_TYPE_STATIC = 0;
    const PROGRESSION_TYPE_AUTO = 1;
}
