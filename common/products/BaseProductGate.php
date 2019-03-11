<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-06
 * Time: 09:26
 */

namespace common\products;

use Yii;
use yii\httpclient\Client;

abstract class BaseProductGate extends \yii\base\Component
{

    const EVENT_BEFORE_SEND_REQUEST = 'beforeSendRequest';
    const EVENT_AFTER_SEND_REQUEST = 'afterSendRequest';

    /**
     * @var string|\yii\caching\CacheInterface
     */
    public $cache = 'cache';

    /**
     * @var Client;
     */
    private $_httpClient;

    /**
     * @return Client
     * @throws \yii\base\InvalidConfigException
     */
    public function getHttpClient()
    {
        if ($this->_httpClient === null) {
            $config = $this->_httpClient;
            $this->_httpClient = $this->createHttpClient($config);
        }
        return $this->_httpClient;
    }

    /**
     * @param $config
     * @return Client
     * @throws \yii\base\InvalidConfigException
     */
    public function createHttpClient($config)
    {
        if ($config === null || (is_array($config) && !isset($config['class']))) {
            $config['class'] = Client::className();

        };
        return Yii::createObject($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->cache = Yii::$app->cache;
    }

    /**
     * @param $response
     * @return mixed
     */
    abstract function parseResponse($response);

    /**
     * @param BaseRequest $request
     * @param bool $renew
     * @param int $cacheTime
     * @return array|mixed
     * @throws \HttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function sendRequest(BaseRequest $request, $renew = false, $cacheTime = 3600)
    {
        if ($renew || !($response = $this->cache->get($request->getCacheKey()))) {
            $httpClient = $this->getHttpClient();
            $httpRequest = $httpClient->createRequest();
            $httpRequest = $request->buildHttpRequest($httpRequest);
            $this->beforeSendRequest($httpRequest);
            $httpResponse = $httpClient->send($httpRequest);
            if (!$httpResponse->isOk) {
                throw new \HttpException("can not sent data");
            }
            $this->afterSendRequest($httpRequest, $httpResponse);
            $response = $httpResponse->getData();
            $this->cache->set($request->getCacheKey(), $response, $cacheTime);
        }
        $response = $this->parseResponse($response);
        return $response;
    }

    public function beforeSendRequest($request)
    {
        $event = new ProductGateEvent();
        $event->request = $request;
        $this->trigger(self::EVENT_BEFORE_SEND_REQUEST, $event);
    }

    public function afterSendRequest($request, $response)
    {
        $event = new ProductGateEvent();
        $event->request = $request;
        $event->response = $response;
        $this->trigger(self::EVENT_AFTER_SEND_REQUEST, $event);
    }

    public function isEmpty($value){
        return \common\helpers\WeshopHelper::isEmpty($value);
    }
}