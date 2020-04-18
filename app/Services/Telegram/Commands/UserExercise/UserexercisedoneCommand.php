<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use \App\Models\UserExercise\UserExercise;
use Carbon\Carbon;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;

class UserexercisedoneCommand extends SystemCommand
{
    protected $name = 'userexercisedone'; // User Exercise done
    protected $description = 'Mark User Exercise as Done';
    protected $version = '1.0.0';

    /**
     * @var CallbackQuery
     */
    private $allQueryData;

    public function execute()
    {
        //todo: get rid of duplicated code in commands
        $this->allQueryData = $this->getCallbackQuery();
        $queryData = $this->allQueryData->getData();
        $queryData = (preg_match('[@]', $queryData)) ? explode('@', $queryData)[1] : '';

        $userExerciseId = explode(' ', $queryData)[0];
        $difficulty = explode(' ', $queryData)[1];

        $userExercise = UserExercise::find($userExerciseId);
        $userExercise->done_at = Carbon::now();
        $userExercise->difficulty_type = $difficulty;
        $userExercise->save();

        $this->sendResponse();
    }

    /**
     * Remove buttons from message
     */
    private function sendResponse()
    {
        $message = $this->allQueryData->getMessage();
        $chat = $message->getChat();
        $data = [
            'chat_id' => $chat->getId(),
            'message_id' => $message->getMessageId(),
            'text' => $message->getText(),
        ];
        Request::editMessageText($data);
    }
}
