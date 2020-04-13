<?php

namespace App\Http\Controllers;

use App\Services\Telegram\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request as TelegramRequest;

class TelegramBotController extends Controller
{
    private $telegram;

    /**
     * TelegramBotController constructor.
     * @param Telegram $telegram
     */
    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram->getService();
    }

    public function hook()
    {
        try {
            //todo
            //Log::info(TelegramRequest::getInput());
            $this->telegram->handle();
        } catch (TelegramException $e) {
            Log::error($e->getMessage());
        }
    }
}
