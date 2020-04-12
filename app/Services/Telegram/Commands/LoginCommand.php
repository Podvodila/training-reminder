<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class LoginCommand extends UserCommand
{
    protected $name = 'login';
    protected $description = 'Login command';
    protected $usage = '/login';
    protected $version = '1.0.0';

    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $responseMsg = 'You have successfully logged in';
        try {
            $loginAndPassword = trim($message->getText(true));
            $loginAndPassword = explode(' ', $loginAndPassword);
            if (!is_array($loginAndPassword) || count($loginAndPassword) !== 2) {
                throw new \Exception('Invalid format. Please try again');
            }

            $user = User::where('email', '=', $loginAndPassword[0])->first();
            if (!$user) {
                throw new \Exception('User with that email not found');
            }

            if (!Hash::check($loginAndPassword[1], $user->password)) {
                throw new \Exception('Password is not correct');
            }

            $user->telegram_chat_id = $chat_id;
            $user->save();
        } catch (\Exception $e) {
            $responseMsg = $e->getMessage();
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $responseMsg,
        ];

        return Request::sendMessage($data);
    }
}
