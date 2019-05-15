<?php

namespace wallet\modules\v1\base\lib;

use Yii;
use SoapClient;
use yii\helpers\ArrayHelper;
use common\components\TextUtility;
use yii\base\InvalidConfigException;

/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/8/2018
 * Time: 9:59 AM
 */
class SmsClient extends \yii\base\BaseObject
{
    public $text;

    public $phone;

    public $action = 'sendSMS';

    public $params = [
        'username' => 'weshop',
        'password' => 'geqidi',
        'loaisp' => 2,
        'brandname' => 'WESHOP',
    ];


    /**
     * @throws InvalidConfigException
     */
    public function prepare()
    {
        if (empty($this->phone)) {
            throw new InvalidConfigException(__CLASS__ . '::$phone must be requited');
        }
        if (empty($this->text)) {
            throw new InvalidConfigException(__CLASS__ . '::$phone must be requited');
        }
        $this->phone = trim($this->phone);
        //$this->text = TextUtility::removeUnicode($this->text);
        $this->params = ArrayHelper::merge($this->params, [
            'receiver' => $this->phone,
            'content' => $this->text,
            'target' => time()
        ]);
        $this->params = [
            $this->action => $this->params
        ];
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function send()
    {
        $this->prepare();
        $client = new SoapClient('http://g3g4.vn:8008/smsws/services/SendMT?wsdl', ['trace' => 1]);
        $res = $client->__call($this->action, $this->params);

        $command = Yii::$app->db->createCommand()->insert('sms_log', [
            'to' => $this->phone,
            'content' => $this->text,
            'apiResponse' => json_encode($res),
            'createdTime' => Yii::$app->formatter->asDatetime('now'),
        ]);
        $command->execute();
        return [true, $res];

    }
}