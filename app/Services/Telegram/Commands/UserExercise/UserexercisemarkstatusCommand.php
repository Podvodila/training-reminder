<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use \App\Models\UserExercise\UserExercise;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\CallbackQuery;

class UserexercisemarkstatusCommand extends SystemCommand
{
    protected $name = 'userexercisemarkstatus'; // User Exercise mark status
    protected $description = 'Mark User Exercise Status';
    protected $version = '1.0.0';

    /**
     * @var CallbackQuery
     */
    private $allQueryData;
    private $userExerciseId;

    public function execute()
    {
        //todo: get rid of duplicated code in commands
        $this->allQueryData = $this->getCallbackQuery();
        $queryData = $this->allQueryData->getData();
        $queryData = (preg_match('[@]', $queryData)) ? explode('@', $queryData)[1] : '';

        $this->userExerciseId = explode(' ', $queryData)[0];
        $status = explode(' ', $queryData)[1];

        UserExercise::where('id', '=', $this->userExerciseId)->update(['status' => $status]);
        Log::info('Status of ' . $this->userExerciseId . ' UserExercise successfully changed');
        //todo: different handle for ABANDONED status
        $this->askAboutDifficulty();
    }

    private function askAboutDifficulty()
    {
        $message = $this->allQueryData->getMessage();
        $chat = $message->getChat();
        $userExercise = UserExercise::find($this->userExerciseId);
        $exercise = $userExercise->activity_exercise->exercise;
        $text = "You have successfully done:" . PHP_EOL . "$exercise->name," . PHP_EOL . "$userExercise->sets sets, $userExercise->repetitions reps";
        $inline_keyboard = [
            [
                ['text' => 'Easy', 'callback_data' => "userexercisedone@$userExercise->id " . UserExercise::DIFFICULTY_TYPE_EASY],
                ['text' => 'Normal', 'callback_data' => "userexercisedone@$userExercise->id " . UserExercise::DIFFICULTY_TYPE_NORMAL],
            ],
            [
                ['text' => 'Hard', 'callback_data' => "userexercisedone@$userExercise->id " . UserExercise::DIFFICULTY_TYPE_HARD],
                ['text' => 'Very Hard', 'callback_data' => "userexercisedone@$userExercise->id " . UserExercise::DIFFICULTY_TYPE_VERY_HARD],
            ],
            //TODO: add back btn
        ];
        $data = [
            'chat_id' => $chat->getId(),
            'message_id' => $message->getMessageId(),
            'text' => $text,
            'reply_markup' => new InlineKeyboard(['inline_keyboard' => $inline_keyboard]),
        ];
        Request::editMessageText($data);
    }
}
