<?php
function printData($data)
{
    print_r('<pre><p style="background-color: beige; color: maroon; padding: 10px; margin: 5px; border: 1px maroon solid;">');
    print_r($data);
    print_r('</p></pre>');
}

if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';

$MadelineProto = new \danog\MadelineProto\API('session.madeline');
$MadelineProto->async(false);
$MadelineProto->start();
//$channel_id = -1001803387301;
//$users_id = Array();
//$bot_api_id = 685244760;
//$tgBot_id = 6101192048;
//$full_chat = $MadelineProto->getPwrChat($channel_id);


























//printData($message_id);


//$users_id = array();
//$message_id = array();

//
////$Updates = $MadelineProto->messages->createChat(users: $users_id, title: $chat_name);
////$clone_chat_id = (int)('-' . $Updates['updates'][1]['participants']['chat_id']);
//$messages_Messages = $MadelineProto->messages->getHistory(peer: -1001867798320,limit:50);
////printData($messages_Messages);
////$messages_Messages = $MadelineProto->messages->getHistory(peer: -1001883653610,limit: 3);
//foreach ($messages_Messages['messages'] as $message) {
//    if ($message['_'] == 'message')
//        $message_id[] = $message["id"];
//}
//printData($message_id);
//$Updates = $MadelineProto->messages->forwardMessages(with_my_score: false, drop_author: false, from_peer: -1001867798320, id: $message_id, to_peer: $clone_chat_id );
//printData($messages_Messages['messages']);

//var_dump($clone_chat_id);
//printData($Updates);


//$Updates = $MadelineProto->channels->createChannel(broadcast: Bool, megagroup: Bool, for_import: Bool, forum: Bool, title: 'string', about: 'string', geo_point: InputGeoPoint, address: 'string', ttl_period: int, );

//$Updates = $MadelineProto->messages->sendMessage(['peer'=>-1883653610,'message'=>'cloning Message']);

