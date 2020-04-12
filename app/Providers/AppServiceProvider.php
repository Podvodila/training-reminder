<?php

namespace App\Providers;

use App\Models\Activity\Activity;
use App\Models\UserExercise\UserExercise;
use App\Observers\ActivityObserver;
use App\Observers\UserExerciseObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Activity::observe(ActivityObserver::class);
        UserExercise::observe(UserExerciseObserver::class);
    }
}
