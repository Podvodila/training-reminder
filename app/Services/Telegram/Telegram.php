<?php

namespace App\Services\Telegram;

use App\Models\Activity\Activity;
use App\Models\UserExercise\UserExercise;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use \Longman\TelegramBot\Telegram as TelegramService;

class Telegram
{
    protected $service;

    protected $commandPaths;

    /**
     * Telegram constructor.
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct()
    {
        $this->service = new TelegramService(config('services.telegram.bot.token'), config('services.telegram.bot.name'));
        $this->commandPaths = [
            __DIR__ . '/Commands',
        ];
        $this->service->addCommandsPaths($this->commandPaths);
        $this->service->enableAdmin(env('TELEGRAM_ADMIN_ID'));
    }

    public function getService()
    {
        return $this->service;
    }


    /**
     * @param UserExercise $userExercise
     * @throws \Exception
     */
    public function sendExerciseNotification(UserExercise $userExercise)
    {
        $user = $userExercise->activity_exercise->activity->user;
        $exercise = $userExercise->activity_exercise->exercise;

        if (!$user->telegram_chat_id) {
            throw new \Exception('User does not have the telegram chat id');
        }

        $inline_keyboard = [
            ['text' => 'Done', 'callback_data' => "userexercisemarkstatus@$userExercise->id " . UserExercise::STATUS_DONE],
            ['text' => 'Give Up', 'callback_data' => "userexercisemarkstatus@$userExercise->id " . UserExercise::STATUS_ABANDONED],
        ];

        $text = "It's time to:" . PHP_EOL . "$exercise->name";
        if ($userExercise->activity_exercise->progression_type === Activity::PROGRESSION_TYPE_AUTO) {
            $text .= ',' . PHP_EOL . "$userExercise->sets sets, $userExercise->repetitions reps";
        }

        $data = [
            'chat_id' => $user->telegram_chat_id,
            'text' => $text,
            'reply_markup' => new InlineKeyboard(['inline_keyboard' => [$inline_keyboard]]),
        ];

        Request::sendMessage($data);
    }
}
