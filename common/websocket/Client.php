<?php

namespace common\websocket;

use yii\base\BaseObject;


class Client extends BaseObject
{

    public $baseUrl;
    public $protocol;

    public $dns = '8.8.8.8';

    public $timeout = 10;

    public function send($url,$params = [])
    {
        return \Ratchet\Client\connect($url)->then(function(\Ratchet\Client\WebSocket $conn) {
            $conn->on('message', function(\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn) {
                $conn->close();
                return "Received: {$msg}\n";

            });

            $conn->send('Hello World!');
        }, function (\Exception $e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}