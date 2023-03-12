<?php

namespace botController;

include 'madeline.php';

class TelegramBotController
{
    protected const TELEGRAM_TOKEN = '6101192048:AAEvr7Zlr33Fl4k98SkXiuGpxF0Xh_jD0xk';

    protected const TELEGRAM_API_URL = 'https://api.telegram.org/bot' . self::TELEGRAM_TOKEN . '/';

    protected $fetchData;

    protected $userID;

    protected $userName;

    protected $requestMethod;
    protected $bot_api_id = 777777;
    protected $tgBot_id = 6101192048;
    protected $users_id = array();
    protected $message_id = array();
    protected $count_message = 50;

    protected $basic_chat_id;

    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    public function helloMessage(): void
    {
        $this->fetchData = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
        if ($this->fetchData['message']['chat']['type'] == 'group' || $this->fetchData['message']['chat']['type'] == 'supergroup' || $this->fetchData['message']['chat']['type'] == 'gigagroup') {
            file_put_contents('chat.txt', json_encode($this->fetchData));
            $this->fetchData = $this->fetchData['message'];
            $this->basic_chat_id = trim($this->fetchData['chat']['id']);
            if ($this->fetchData['text'] == '/clone') {
                $sendData = [
                    'text' => 'Cloning Chat ' . '( Chat title: ' . $this->fetchData['chat']['title'] . ' )',
                    'chat_id' => $this->fetchData['chat']['id']
                ];
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => self::TELEGRAM_API_URL . $this->requestMethod,
                    CURLOPT_POSTFIELDS => http_build_query($sendData),
                ]);
                curl_exec($curl);
                curl_close($curl);
                $MadelineProto = new \danog\MadelineProto\API('session.madeline');
                $MadelineProto->async(false);
                $MadelineProto->start();

                $full_chat = $MadelineProto->getPwrChat((int)($this->basic_chat_id));
                $chat_name = $full_chat['title'];

                foreach ($full_chat['participants'] as $participant) {
                    if ($participant["user"]["id"] !== $this->bot_api_id && $participant["user"]["id"] !== $this->tgBot_id)
                        $this->users_id[] = $participant["user"]["id"];
                }

                $Updates = $MadelineProto->messages->createChat(users: $this->users_id, title: $chat_name);
                $clone_chat_id = (int)('-' . $Updates['updates'][1]['participants']['chat_id']);
                $messages_Messages = $MadelineProto->messages->getHistory(peer: (int)($this->basic_chat_id), limit: $this->count_message);
                foreach ($messages_Messages['messages'] as $message) {
                    if ($message['_'] == 'message')
                        $this->message_id[] = $message["id"];
                }
                $Updates = $MadelineProto->messages->forwardMessages(with_my_score: false, drop_author: false, from_peer: (int)($this->basic_chat_id), id: $this->message_id, to_peer: $clone_chat_id);
            }
        } else {
            $this->basic_chat_id = trim($this->fetchData['channel_post']['chat']['id']);
            file_put_contents('channel.txt', json_encode($this->fetchData,JSON_FORCE_OBJECT));
            if ($this->fetchData['channel_post']['text'] == '/clone') {
                $sendData = [
                    'text' => 'Cloning Channel',
                    'chat_id' => $this->basic_chat_id
                ];
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_POST => 1,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => self::TELEGRAM_API_URL . $this->requestMethod,
                    CURLOPT_POSTFIELDS => http_build_query($sendData),
                ]);
                curl_exec($curl);
                curl_close($curl);
                $MadelineProto = new \danog\MadelineProto\API('session.madeline');
                $MadelineProto->async(false);
                $MadelineProto->start();

                $message_id = array();

                $full_chat = $MadelineProto->getPwrChat($this->basic_chat_id);
                foreach ($full_chat['participants'] as $participant) {
                    if ($participant["user"]["id"] !== $this->bot_api_id && $participant["user"]["id"] !== $this->tgBot_id)
                        $this->users_id[] = $participant["user"]["id"];
                }
                $chat_name = $full_chat['title'];

                $Update = $MadelineProto->channels->createChannel(broadcast: true, megagroup: false, title: $chat_name.' Clone', about: 'about:'.$chat_name);
                $new_chat_id_fake = $Update['chats'][0]['id'];
                $new_chat_hash = $Update['chats'][0]['access_hash'];
                $Updates = $MadelineProto->channels->inviteToChannel(channel: ['_' => 'inputPeerChannel', 'channel_id' => $new_chat_id_fake, 'access_hash' => $new_chat_hash], users: $this->users_id);
                $messages_Messages = $MadelineProto->messages->getHistory(peer: $this->basic_chat_id, limit: $this->count_message);
                foreach ($messages_Messages['messages'] as $message) {
                    if ($message['_'] == 'message')
                        $message_id[] = $message["id"];
                }
                $newChat = $MadelineProto->messages->forwardMessages(with_my_score: false, drop_author: false, from_peer: $this->basic_chat_id, id: $message_id, to_peer: ['_' => 'inputPeerChannel', 'channel_id' => $new_chat_id_fake, 'access_hash' => $new_chat_hash]);
            }
        }
    }
}
