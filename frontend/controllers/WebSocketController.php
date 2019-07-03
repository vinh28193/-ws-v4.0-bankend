<?php


namespace frontend\controllers;


use common\websocket\Client;

class WebSocketController extends FrontendController
{


    public function actionIndex()
    {
        $client = new Client();
        echo "<pre>";
        var_dump($client->send('ws://echo.socketo.me:9000'));
        echo "</pre>";

        die;
    }
}