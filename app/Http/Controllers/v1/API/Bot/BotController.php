<?php

namespace App\Http\Controllers\v1\API\Bot;

use App\Http\Controllers\Controller;
use App\Services\Bot\Message\BotMessageService;
use Illuminate\Http\Request;
use Longman\TelegramBot\Telegram;

class BotController extends Controller
{
    public function test(
        BotMessageService $messageService
    ): void {
        $bot = new Telegram('5198795597:AAGmCvaioJOhg1PSezP9IOMGiYYMfv5QeQ8', 'testbotorconstructorbot');

        $bot->setWebhook('http://bot-constructor.test/webhook');
        \Longman\TelegramBot\Request::sendMessage(['text' => '321654']);
    }
}
