<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-06-12
 * Time: 11:21
 */

namespace common\components;

use common\components\ThirdPartyLogs;
use Yii;
use SoapClient;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class MessageClient extends Component
{
    const PROVIDER_VN = 'VN';
    const PROVIDER_NEXMO = 'NEXMO';

    const LOG_PROVIDER_DEFAULT_SMS = 'SMS_VN';
    const LOG_PROVIDER_DEFAULT_NEXMO = 'SMS_NEXTMO';
    const LOG_PROVIDER_WALLET_SMS = 'SMS_WALLET_VN';
    const LOG_PROVIDER_WALLET_NEXMO = 'SMS_WALLET_NEXMO';
    const LOG_PROVIDER_SC_BOXME = 'SC_BOXME';
    const LOG_PROVIDER_NGANLUONG = 'NGANLUONG';

    public $provider;
    public $from;
    public $to;
    public $content;

    public $logProvider;
    public $providerConfig;

    public $sendFail = [false, 'Your message could not be send'];

    public function init()
    {
        parent::init();

        $this->providerConfig = [
            self::PROVIDER_VN => [
                'username' => 'weshop',
                'password' => 'geqidi',
                'loaisp' => 2,
                'brandname' => 'WESHOP',
            ],
            self::PROVIDER_NEXMO => [
                'token' => ''
            ]
        ];

    }

    public function prepare()
    {
        if (!$this->provider) {
            throw new InvalidConfigException(get_called_class() . '::$provider must be requited');
        }
        if (!$this->to) {
            throw new InvalidConfigException(get_called_class() . '::$to must be requited');
        }
        if (!$this->content) {
            throw new InvalidConfigException(get_called_class() . '::$content must be requited');
        }
        if(!$this->logProvider){
            throw new InvalidConfigException(get_called_class() . '::$logProvider must be requited');
        }
        $this->to = trim($this->to);
        $this->from = trim($this->from);
        $providerConfig = $this->providerConfig[$this->provider];

        if ($this->provider === self::PROVIDER_VN) {
            $providerConfig = ArrayHelper::merge($providerConfig, [
                'receiver' => $this->to,
                'content' => $this->content,
                'target' => time()
            ]);
        } elseif ($this->provider === self::PROVIDER_NEXMO) {
            $providerConfig = ArrayHelper::merge($providerConfig, [
                'from' => $this->from,
                'to' => $this->to,
                'text' => $this->content
            ]);
        }

        $this->providerConfig = $providerConfig;
    }

    public function send()
    {
        $this->prepare();
        if ($this->provider === self::PROVIDER_VN) {
            return $this->smsVN();
        } elseif ($this->provider === self::PROVIDER_NEXMO) {
            return $this->nexmo();
        }
        return $this->sendFail;
    }

    public function smsVN()
    {
        $action = 'sendSMS';
        $params[$action] = $this->providerConfig;
        $client = new SoapClient('http://g3g4.vn:8008/smsws/services/SendMT?wsdl', ['trace' => 1]);
        $res = $client->__call($action,$params);

        $log = new ThirdPartyLogs();
        $formatter = Yii::$app->formatter;
        $log->provider = $this->logProvider;
        $log->date = $formatter->asDate('now');
        $log->create_by = 'system';
        $log->create_time = $formatter->asDatetime('now');
        $log->action = $action;
        $log->request = $params;
        $log->response = $res;

        return [$log->save(false), $res];
    }

    public function nexmo()
    {
        $config = $this->providerConfig;
        if(!$config['class']){
            $config['class'] = Component::className();
        }
        $client = Yii::createObject($config);
        return $this->sendFail;
    }
}