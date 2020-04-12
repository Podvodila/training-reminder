<?php

namespace App\Services\Telegram;

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
    }

    public function getService()
    {
        return $this->service;
    }
}
