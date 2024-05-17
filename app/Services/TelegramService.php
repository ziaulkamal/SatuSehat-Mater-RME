<?php

namespace App\Services;

use Telegram;

class TelegramService
{
    protected $bot;

    public function __construct()
    {
        $this->bot = new \Telegram\Bot\Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function sendMessage($message)
    {
        $this->bot->sendMessage([
            'chat_id' => env('TELEGRAM_ID'),
            'text' => $message,
        ]);
    }
}
