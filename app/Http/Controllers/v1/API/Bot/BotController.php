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
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

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
        $username = $update['message']['chat']['username'] ?? '??????';
        $result = \Longman\TelegramBot\Request::sendMessage(['chat_id'=> $update['message']['chat']['id'],'text' => "???????? {$update['message']['chat']['first_name']} $lastName, ???? ?????????????????????? ?? $username, ?????? ???????? ???? {$update['message']['text']}"]);
        Log::critical('Result', [$result]);

//        $bot->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
//            Log::critical('Hook message', [$update]);
//        });
    }

    public function viber()
    {
        $apiKey = '4efc27192727e2cc-2aa2282a24fc9dd4-7f1e1496976b8161';

        // ?????? ?????????? ?????????????????? ?????? ?????? (?????? ?? ???????????? - ?????????? ????????????)
        $botSender = new Sender([
            'name' => 'Whois bot',
            'avatar' => 'https://developers.viber.com/img/favicon.ico',
        ]);

        try {
            $bot = new Bot(['token' => $apiKey]);
            $bot
                ->onConversation(function ($event) use ($bot, $botSender) {
                    // ?????? ?????????????? ?????????? ??????????????, ?????? ???????????? ???????????????????????? ???????????????? ?? ??????
                    // ???? ???????????? ?????????????????? "????????????????????", ???? ???? ???????????? ???????????????? ?????????? ??????????????????
                    return (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setText("Can i help you?");
                })
                ->onText('|whois .*|si', function ($event) use ($bot, $botSender) {
                    // ?????? ?????????????? ?????????? ?????????????? ???????? ???????????????????????? ???????????? ??????????????????
                    // ?????????????? ???????????????? ?? ???????????????????? ????????????????????
                    $bot->getClient()->sendMessage(
                        (new \Viber\Api\Message\Text())
                            ->setSender($botSender)
                            ->setReceiver($event->getSender()->getId())
                            ->setText("I do not know )")
                    );
                })
                ->run();
        } catch (Exception $e) {
            // todo - log exceptions
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

    public function facebook()
    {}

    public function facebookWebhook()
    {
        $messenger = new \FbMessengerBot\Messenger('546782170085168|Dpxbo56czMbepaXJEN7QVfSZzzM', 'Dpxbo56czMbepaXJEN7QVfSZzzM');
        $messenger->listen();
        $message = new \FbMessengerBot\Message();
        $message->text('Barev');
    }

    public function vk()
    {
        Log::critical('VK main method');
        return view('vk');
    }

    public function vkWebhook(Request $request)
    {
        Log::critical('VK bot webhook', [$request->all()]);
        $vk = new VKApiClient();
        $oauth = new VKOAuth();
        $client_id = 8127124;
        $redirect_uri = route('bot.message.vk');
        $display = VKOAuthDisplay::PAGE;
        $scope = array(VKOAuthGroupScope::MESSAGES);
        $state = '8ce8729bc5e13bbff3d96d6d9e35d4a72736782d377679d5784f655d6bd72e39b913dce1ebb39a99aaefe';
        $groups_ids = array();

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state, $groups_ids);
echo "$browser_url<br>";
//        xS48beDOXQYS52yB3wvt
//        f879e1a0f879e1a0f879e1a071f805e334ff879f879e1a09a32bb539781a421987b9eb4
        return '778b7720';
    }
}
