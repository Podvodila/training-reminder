<?php

namespace App;

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
}
