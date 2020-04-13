<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';
    protected $description = 'Reply to callback query';
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query    = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = $callback_query->getData();
        $data              = [
            'callback_query_id' => $callback_query_id,
            'text'              => $callback_data,
            //'show_alert'        => true,
            //'cache_time'        => 5,
        ];
        if (preg_match("[@]", $data['text'])) {
            $arr        = explode('@', $data['text']);
            $arrcommand = $arr['0'];
            $this->telegram->executeCommand($arrcommand);
        } else {
            return Request::answerCallbackQuery($data);
        }
    }
}
