<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Exception\TelegramException;
use App\Services\Telegram\Telegram;

class UnsetTelegramBotWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:unset-bot-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unset telegram bot webhook';

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
            $result = $this->telegram->deleteWebhook();

            if ($result->isOk()) {
                $this->info($result->getDescription());
            }
        } catch (TelegramException $e) {
            $this->error($e->getMessage());
            Log::error($e->getMessage());
        }
    }
}
