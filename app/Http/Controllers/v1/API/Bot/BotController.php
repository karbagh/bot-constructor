<?php

namespace App\Http\Controllers\v1\API\Bot;

use App\Http\Controllers\Controller;
use App\Services\Bot\Message\BotMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

class BotController extends Controller
{
    public function test(
        BotMessageService $messageService
    ): void
    {
        $bot = new Telegram('5198795597:AAGmCvaioJOhg1PSezP9IOMGiYYMfv5QeQ8', 'testbotorconstructorbot');

        $bot->setWebhook('https://bot-constructor.herokuapp.com/api/bot/webhook');
        $bot->handleGetUpdates();
        $bot->useGetUpdatesWithoutDatabase();
        Log::critical('Test Message');
        Log::critical('Hook message', [321]);
        $bot->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
            Log::critical('Hook message', [$update]);
        });
        \Longman\TelegramBot\Request::sendMessage(['text' => '321654']);
    }

    public function webhook(Request $request)
    {
        $bot = new Telegram('5198795597:AAGmCvaioJOhg1PSezP9IOMGiYYMfv5QeQ8', 'testbotorconstructorbot');
        $update = $request->all();
        Log::critical('Hook message', [$update, $update['message'], $update['message']['chat'], $update['message']['chat']['id'], $update['message']['text']]);

//        $result = \Longman\TelegramBot\Request::sendMessage(['chat_id'=> $update['message']['chat']['id'],'text' => "Hello {$update['message']['chat']['last_name']} {$update['message']['chat']['first_name']}, your username is {$update['message']['chat']['username']}, you wrote {$update['message']['text']}"]);
        $result = \Longman\TelegramBot\Request::sendMessage(['chat_id'=> $update['message']['chat']['id'],'text' => "Բարև {$update['message']['chat']['last_name']} {$update['message']['chat']['first_name']}, քո յուզերնեյմն է {$update['message']['chat']['username']}, դու գրել ես {$update['message']['text']}"]);
        Log::critical('Result', [$result]);

        $bot->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
            Log::critical('Hook message', [$update]);
        });
    }
}
