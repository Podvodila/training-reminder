<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use \App\Models\UserExercise\UserExercise;
use Carbon\Carbon;
use App\Services\Telegram\Commands\CustomSystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;

class UserexercisedoneCommand extends CustomSystemCommand
{
    protected $name = 'userexercisedone'; // User Exercise done
    protected $description = 'Mark User Exercise as Done';
    protected $version = '1.0.0';

    const PARAM_EXERCISE_ID = 0;
    const PARAM_DIFFICULTY = 1;

    const AVAILABLE_QUERY_PARAMETERS = [
        self::PARAM_EXERCISE_ID => 'userExerciseId',
        self::PARAM_DIFFICULTY => 'difficulty',
    ];

    public function execute()
    {
        $userExerciseId = $this->getQueryData(self::PARAM_EXERCISE_ID);
        $difficulty = $this->getQueryData(self::PARAM_DIFFICULTY);

        $userExercise = UserExercise::find($userExerciseId);
        $userExercise->finished_at = Carbon::now();
        $userExercise->difficulty_type = $difficulty;
        $userExercise->save();

        $this->sendResponse();
    }

    /**
     * Remove buttons from message
     */
    private function sendResponse()
    {
        $message = $this->query->getMessage();
        $chat = $message->getChat();
        $data = [
            'chat_id' => $chat->getId(),
            'message_id' => $message->getMessageId(),
            'text' => $message->getText(),
        ];
        Request::editMessageText($data);
    }
}
