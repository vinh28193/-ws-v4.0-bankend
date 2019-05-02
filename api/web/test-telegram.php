<?php
$apiToken = "764381102:AAE9qR9ZxLS4qOpFlauOM1rItFSxhrjic3A";

$data = [
'chat_id' => '855666866',
'text' => 'MY_MESSAGE_TEXT'
];
//https://api.telegram.org/bot64381102:AAE9qR9ZxLS4qOpFlauOM1rItFSxhrjic3A/sendMessage?chat_id=Ws_Notifi_252_bot&text=Haloooo
$response = @file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );
var_dump($response);
?>
