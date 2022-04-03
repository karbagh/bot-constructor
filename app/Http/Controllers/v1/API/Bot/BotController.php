<?php

namespace App\Http\Controllers\v1\API\Bot;

use App\Http\Controllers\Controller;
use App\Services\Bot\Message\BotMessageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;
use Viber\Api\Exception\ApiException;
use Viber\Api\Sender;
use Viber\Bot;
use Viber\Client;

class BotController extends Controller
{
    public function test(
        BotMessageService $messageService
    ): void
    {
        $bot = new Telegram('5198795597:AAGmCvaioJOhg1PSezP9IOMGiYYMfv5QeQ8', 'testbotorconstructorbot');

        $bot->setWebhook('https://bot-constructor.herokuapp.com/api/bot/webhook');
        $bot->handleGetUpdates();
//        $bot->useGetUpdatesWithoutDatabase();

        Log::critical('Test Message');
        Log::critical('Hook message', [321]);
//        $bot->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
//            Log::critical('Hook message', [$update]);
//        });
        \Longman\TelegramBot\Request::sendMessage(['text' => '321654']);
    }

    public function webhook(Request $request)
    {
        $bot = new Telegram('5198795597:AAGmCvaioJOhg1PSezP9IOMGiYYMfv5QeQ8', 'testbotorconstructorbot');
        $update = $request->all();
        Log::critical('Hook message', [$update, $update['message'], $update['message']['chat'], $update['message']['chat']['id'], $update['message']['text']]);

//        $result = \Longman\TelegramBot\Request::sendMessage(['chat_id'=> $update['message']['chat']['id'],'text' => "Hello {$update['message']['chat']['last_name']} {$update['message']['chat']['first_name']}, your username is {$update['message']['chat']['username']}, you wrote {$update['message']['text']}"]);
        $lastName = $update['message']['chat']['last_name'] ?? ' ';
        $username = $update['message']['chat']['username'] ?? 'չկա';
        $result = \Longman\TelegramBot\Request::sendMessage(['chat_id'=> $update['message']['chat']['id'],'text' => "Բարև {$update['message']['chat']['first_name']} $lastName, քո յուզերնեյմն է $username, դու գրել ես {$update['message']['text']}"]);
        Log::critical('Result', [$result]);

//        $bot->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
//            Log::critical('Hook message', [$update]);
//        });
    }

    public function viber()
    {
        $apiKey = '4efc27192727e2cc-2aa2282a24fc9dd4-7f1e1496976b8161';

        $botSender = new Sender([
            'name' => 'Reply bot',
            'avatar' => 'https://developers.viber.com/img/favicon.ico',
        ]);

        try {
            $bot = new Bot([ 'token' => $apiKey ]);
            $bot
                ->onText('|.*|s', function ($event) use ($bot, $botSender) {
                    // .* - match any symbols (see PCRE)
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("Hi!")
                    );
                })
                ->run();
        } catch (Exception $e) {
            // todo - log errors
        }
    }

    public function viberWebhook()
    {

        $apiKey = '4efc27192727e2cc-2aa2282a24fc9dd4-7f1e1496976b8161'; // from "Edit Details" page
        $webhookUrl = 'https://bot-constructor.herokuapp.com/api/bot/message/viber'; // for exmaple https://my.com/bot.php

        try {
            $client = new Client([ 'token' => $apiKey ]);
            $result = $client->setWebhook($webhookUrl);
            echo "Success!\n";
        } catch (ApiException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
