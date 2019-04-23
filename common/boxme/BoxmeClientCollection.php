<?php


namespace common\boxme;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class BoxmeClientCollection extends Component
{

    private $_clients = [
        Location::COUNTRY_VN => [
            'class' => 'common\boxme\BoxmeClient',
            'env' => BoxmeClient::ENV_SANDBOX,
            'params' => [
                BoxmeClient::ENV_SANDBOX => [
                    'api_key' => '424d0c829922f4ba3046b7344b008ced8a40964840a673c588fe10be4440769a',
                    'base_url' => 'https://v2.boxme.vn/api/v1',
                ],
                BoxmeClient::ENV_PRODUCT => [
                    'api_key' => '424d7386b54575bb8bb75f1c6dacbd1a4f3257b83c1607a8ab635bc34f7c65bb',
                    'base_url' => 'https://s.boxme.asia/api/v1',
                ]
            ]
        ],
        Location::COUNTRY_ID => [
            'class' => 'common\boxme\BoxmeClient',
            'env' => BoxmeClient::ENV_SANDBOX,
            'params' => [
                BoxmeClient::ENV_SANDBOX => [
                    'api_key' => '424d0c829922f4ba3046b7344b008ced8a40964840a673c588fe10be4440769a',
                    'base_url' => 'https://v2.boxme.vn/api/v1',
                ],
                BoxmeClient::ENV_PRODUCT => [
                    'api_key' => '424dcf510b055bc4345cafbe0e1e73da0a665ef236e965897ab48f165a599a19',
                    'base_url' => 'https://s.boxme.asia/api/v1',
                ]
            ]
        ]
    ];

    /**
     * create object
     * @param $config
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    protected function createClient($config)
    {
        return Yii::createObject($config);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasClient($name)
    {
        return array_key_exists($name, $this->_clients);
    }

    /**
     * @param $name
     * @return BoxmeClient|mixed
     * @throws InvalidConfigException
     */
    public function getClient($name)
    {
        if (!array_key_exists($name, $this->_clients)) {
            throw new \yii\base\InvalidParamException("Unknown Client '{$name}'.");
        }
        $this->setClient($name, $this->_clients[$name]);

        return $this->_clients[$name];

    }

    /**
     * set a Client
     * @param $name string
     * @param $config string|array|LoggingClientInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function setClient($name, $config)
    {
        if (is_array($config) || is_string($config)) {
            $config = $this->createClient($config);
        }
        if ($config instanceof BoxmeClient) {
            $this->_clients[$name] = $config;
        } else {
            Yii::warning("can not set: " . get_class($config) . " not instanceof LoggingClientInterface");
        }
    }

    /**
     * get Clients
     * @return array|BoxmeClient[]
     */
    public function getClients()
    {
        return $this->_clients;
    }


    public function setClients($Client)
    {
        $this->_clients = $Client;
    }

    /**
     * php magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->hasClient($name)) {
            return $this->getClient($name);
        }
        return parent::__get($name);
    }

    /**
     * php magic method isset
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_clients[$name]) || parent::__isset($name);
    }
}