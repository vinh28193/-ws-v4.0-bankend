<?php
namespace common\components\fcm;

use Yii;
use yii\base\InvalidConfigException;
use common\helpers\WeshopHelper;
use linslin\yii2\curl;
use yii\helpers\ArrayHelper;
/**
 *  3. config as application component:
 *      components => [
 *          'wsFcnApn' => [
 *                 'class' => Notification::className,
 *                  'param' => [
 *                      // config param here
 *                  ]
 *          ],
 *
 *   ]
 * Class Notification
 * @package common\components\fcm
 */
class Notification extends \yii\base\Component
{
    private $fcm_google = 'https://fcm.googleapis.com/fcm/send';
    private $key = 'key=AIzaSyARiuZmDuXm4Tz7k_3c57Y0dC8az_e3Lz8';

    public function init()
    {
        parent::init();
    }

    public function Create($_to_token_fingerprint, $_title , $_body , $_click_action)
    {
        Yii::info("Send Start FCM");

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

        return ['success' => true, 'message' => 'success', 'data' =>$response ];

    }

}
