<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Models\Activity\Activity;
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
    private $userExercise;

    public function execute()
    {
        //todo: get rid of duplicated code in commands
        $this->allQueryData = $this->getCallbackQuery();
        $queryData = $this->allQueryData->getData();
        $queryData = (preg_match('[@]', $queryData)) ? explode('@', $queryData)[1] : '';

        $userExerciseId = explode(' ', $queryData)[0];
        $this->userExercise = UserExercise::find($userExerciseId);
        $status = explode(' ', $queryData)[1];

        $this->userExercise->status = intval($status);
        $this->userExercise->save();
        Log::info('Status of ' . $this->userExercise->id . ' UserExercise successfully changed');

        switch ($this->userExercise->status) {
            case UserExercise::STATUS_DONE:
                $this->handleStatusDone();
                break;
            case UserExercise::STATUS_ABANDONED:
                $this->handleStatusAbandoned();
                break;
        }
    }

    private function handleStatusDone()
    {
        switch ($this->userExercise->activity_exercise->progression_type) {
            case Activity::PROGRESSION_TYPE_STATIC:
                $this->userExercise->done_at = now();
                $this->userExercise->save();
                $this->sendSuccessMsg();
                break;
            case Activity::PROGRESSION_TYPE_AUTO:
                $this->sendSuccessMsg(true);
                break;
        }
    }

    private function handleStatusAbandoned()
    {
        $message = $this->allQueryData->getMessage();
        $chat = $message->getChat();
        $data = [
            'chat_id' => $chat->getId(),
            'message_id' => $message->getMessageId(),
            'text' => $this->getAbandonMsg(),
        ];
        Request::editMessageText($data);
    }

    private function sendSuccessMsg($withDifficultyBtns = false)
    {
        $message = $this->allQueryData->getMessage();
        $chat = $message->getChat();
        $data = [
            'chat_id' => $chat->getId(),
            'message_id' => $message->getMessageId(),
            'text' => $this->getSuccessMsg(),
        ];
        if ($withDifficultyBtns) {
            $data['reply_markup'] = $this->getDifficultyBtns();
        }
        Request::editMessageText($data);
    }

    private function getDifficultyBtns()
    {
        $userExercise = $this->userExercise;
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

        return new InlineKeyboard(['inline_keyboard' => $inline_keyboard]);
    }

    private function getSuccessMsg()
    {
        $userExercise = $this->userExercise;
        $exercise = $userExercise->activity_exercise->exercise;
        $text = "You have successfully done:" . PHP_EOL . "$exercise->name";
        if ($userExercise->activity_exercise->progression_type === Activity::PROGRESSION_TYPE_AUTO) {
            $text .= ',' . PHP_EOL . "$userExercise->sets sets, $userExercise->repetitions reps";
        }

        return $text;
    }

    private function getAbandonMsg()
    {
        $userExercise = $this->userExercise;
        $exercise = $userExercise->activity_exercise->exercise;
        $text = "Exercise $exercise->name is not completed";

        return $text;
    }
}
