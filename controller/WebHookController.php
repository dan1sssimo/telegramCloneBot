<?php

namespace webHooks;

class WebHookController
{
    protected const TELEGRAM_TOKEN = '6101192048:AAEvr7Zlr33Fl4k98SkXiuGpxF0Xh_jD0xk';

    public function setHookTelegram(): void
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.telegram.org/bot" . self::TELEGRAM_TOKEN . "/setWebhook",
            CURLOPT_POSTFIELDS => http_build_query(["url" => "https://danyloabel1.dev.yeducoders.com/"]),
        ));
        curl_exec($curl);
        curl_close($curl);
    }
}

$webHookModel = new WebHookController();
$webHookModel->setHookTelegram();