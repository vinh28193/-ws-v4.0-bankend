<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseApiController;
use Yii;
use yii\httpclient\Client;
use linslin\yii2\curl;
use yii\helpers\ArrayHelper;

class FcmNotiController extends BaseApiController
{
    private $fcm_google = 'https://fcm.googleapis.com/fcm/send';
    private $key = 'key=AIzaSyARiuZmDuXm4Tz7k_3c57Y0dC8az_e3Lz8';

    public function rules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'create'],
                'roles' => $this->getAllRoles(true),
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => $this->getAllRoles(true),
                'permissions' => ['canCreate']
            ],
        ];
    }

    public function init()
    {
        parent::init();
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'POST'],
            'create' => ['POST'],
        ];
    }

    public function actionIndex()
    {
        return $this->response(true, 'Success', []);
    }

    public function actionCreate()
    {
        Yii::info("Send Start FCM");
        $post = (array)$this->post;
        if (isset($post) !== null) {
            $_to_token_fingerprint = ArrayHelper::getValue($post,'token_fcm');
            $_title = ArrayHelper::getValue($post,'title');
            $_body = ArrayHelper::getValue($post,'messa_body');
            $_click_action = ArrayHelper::getValue($post,'action');

            $fcm_json = new \stdClass();
            $json_text = new \stdClass();
            $json_text->title = $_title;
            $json_text->body = $_body;
            $json_text->click_action = $_click_action;
            $fcm_json->notification = $json_text;
            $fcm_json->to = $_to_token_fingerprint;
            $now_body_send = $fcm_json;

            Yii::info([
                'to_token_fingerprint' => $_to_token_fingerprint ,
                'title ' => $_title ,
                '_body' => $_body ,
                'click_action' => $_click_action ,
                //'body' => $body,
                'now_body_send' => $now_body_send,
                'fcm_google' => $this->fcm_google,
                'key' => $this->key
            ], __CLASS__);

            $client = new curl\Curl();
            $response = $client->setHeaders([
                            'Content-Type' => 'application/json',
                            'Content-Length' => strlen(json_encode($now_body_send)),
                            'Authorization'=> $this->key
                        ])
                ->setRawPostData(json_encode($now_body_send))
                ->post($this->fcm_google);

            Yii::info("ket qua send : ".$response);

            return $this->response(true, 'Success', $response);
        } else {
            Yii::$app->api->sendFailedResponse("Invalid FCM Record requested");
        }
    }

}
