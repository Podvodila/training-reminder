<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Models\Activity\Activity;
use App\Models\UserExercise\UserExercise;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class TrainnowCommand extends UserCommand
{
    protected $name = 'trainnow';
    protected $description = 'Get next exercises immediately';
    protected $usage = '/trainnow';
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        try {
            $activityName = trim($message->getText(true));
            $activity = Activity
                ::where('name', '=', $activityName)
                ->whereHas('user', function ($query) use ($chat_id) {
                    return $query->where('telegram_chat_id', '=', $chat_id);
                })
                ->first();
            if (!$activity) {
                throw new \Exception('Activity with that name not found');
            }
            if ($activity->status === Activity::STATUS_INACTIVE) {
                throw new \Exception('Activity is not active');
            }
            $userExercises = $activity->user_exercises()->whereNotNull('notification_job_id')->get();
            if ($userExercises->isEmpty()) {
                throw new \Exception('Exercises are not scheduled');
            }
            $jobIds = $userExercises->pluck('notification_job_id');
            DB::table('jobs')->whereIn('id', $jobIds)->update(['available_at' => Carbon::now()->timestamp]);
        } catch (\Exception $e) {
            $responseMsg = $e->getMessage();
            $data = [
                'chat_id' => $chat_id,
                'text'    => $responseMsg,
            ];
            Request::sendMessage($data);
        }
    }
}
