<?php

include("controller/TelegramBotController.php");

$telegramBot = new \botController\TelegramBotController("sendMessage");
$telegramBot->helloMessage();