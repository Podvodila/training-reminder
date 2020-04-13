<?php

namespace App\Jobs;

use App\Models\UserExercise\UserExercise;
use App\Services\Telegram\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserExerciseTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userExercise;

    /**
     * Create a new job instance.
     *
     * @param  UserExercise  $userExercise
     * @return void
     */
    public function __construct(UserExercise $userExercise)
    {
        $this->userExercise = $userExercise;
    }

    /**
     * Execute the job.
     *
     * @param Telegram $telegram
     * @return void
     */
    public function handle(Telegram $telegram)
    {
        try {
            $telegram->sendExerciseNotification($this->userExercise);
            Log::info("Notified through telegram: userExerciseId - " . $this->userExercise->id);
            $this->userExercise->is_notified = true;
            $this->userExercise->save();
        } catch (\Exception $e) {
            Log::error("Error in UserExerciseTelegramNotification job: " . $e->getMessage());
        }
    }
}
