<?php

namespace App\Console\Commands;

use App\Models\User\User;
use App\Services\Telegram\Telegram;
use Illuminate\Console\Command;

class TelegramBotSendPlainMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:send-plain-msg {user} {msg}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send plain message through telegram bot';

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
        $this->telegram = $telegram;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::find($this->argument('user'));
        if (!$user) {
            $this->warn('User not found');
            return;
        }
        if (!$user->telegram_chat_id) {
            $this->warn('Telegram is not connected to this account');
            return;
        }
        $text = $this->argument('msg');
        $response = $this->telegram->sendPlainMessage($user->telegram_chat_id, $text);
        $this->info($response);
    }
}
