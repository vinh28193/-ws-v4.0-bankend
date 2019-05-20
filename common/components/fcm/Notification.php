<?php
namespace common\components\fcm;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use common\helpers\WeshopHelper;
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
 * @property $param array
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

    public function Create()
    {
        $_post = (array)$this->post;
        if (isset($_post) !== null) {
            $_to_token_fingerprint = "eFbHagcSYD8:APA91bH2bcY4fpKwMezwGJUOWJLyYb7vnmg5NbycLJftuRGxgFH4DYAOCHcjq2xUiMwCr4LRnUbKmYV1PukYkPnFNutJTLuJ_EK-uIzVPPYmo9tq8XP1xq2savPE0gjbjsWs-0snI6or"; // Mã UIDI thiết bị
            $_title = "Message Title Weshop 2019";
            $_body = "Message body Weshop 2019";
            $_click_action = "https://weshop.com.vn";
            $body = '{
            "notification": {
                "title": '. $_title . ',
                 "body": '. $_body .',
                 "click_action" : '. $_click_action .'
             },
             "to" : '. $_to_token_fingerprint .'}';

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setFormat('json')
                ->addHeaders(['Authorization'=> $this->key])
                ->setUrl($this->fcm_google)
                ->setData($body)
                ->setOptions([
                    'timeout' => 90, // set timeout to 5 seconds for the case server is not responding
                ])
                ->send();

            return WeshopHelper::response($success = true, 'Success', $response);
        } else {
            return WeshopHelper::response($success = false, "Invalid FCM Record requested", []);
        }
    }

}
