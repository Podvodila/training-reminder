<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Exception\TelegramException;
use App\Services\Telegram\Telegram;

class SetTelegramBotWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-bot-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set telegram bot webhook';

    /**
     * @var Telegram
     */
    private $telegram;

    /**
     * Create a new command instance.
     *
     * @param Telegram $telegram
     * @return void
     */
    public function __construct(Telegram $telegram)
    {
        parent::__construct();
        $this->telegram = $telegram->getService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $hookUrl = route('telegram-bot.hook');
            $result = $this->telegram->setWebhook($hookUrl);
            if ($result->isOk()) {
                $this->info($result->getDescription());
            }
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());
        }
    }
}
