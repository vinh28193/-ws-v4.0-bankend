<?php


namespace common\boxme;


use Yii;
use Exception;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class BaseClient extends BaseObject
{

    const ENV_SANDBOX = 'sandbox';
    const ENV_PRODUCT = 'product';

    public $env = self::ENV_SANDBOX;
    public $params = [];

    protected $api_key;
    protected $base_url;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!isset($this->params[$this->env]) || ($params = $this->params[$this->env]) === null) {
            throw new InvalidConfigException('params for env `{$this->env}` can not be null');
        }
        if (!isset($params['api_key']) || !isset($params['base_url'])) {
            throw new InvalidConfigException('params for env `{$this->env}` can not missing config of `api_key` and `base_url`');
        }
        $this->api_key = $params['api_key'];
        $this->base_url = $params['base_url'];
    }

    public function createRequest($url, $data, $method = 'POST')
    {
        $client = new Client([
            'baseUrl' => $this->base_url,
            'transport' => CurlTransport::className()
        ]);
        $request = $client->createRequest();
        $request->setFormat(Client::FORMAT_JSON);
        $request->setMethod($method);
        $request->setUrl($url);
        $request->setData($data);
        $request->addHeaders($this->getAuthorization());
        try {
            $response = $client->send($request);
            $result = $response->getData();
            // Todo Log here
            if (!$response->getIsOk()) {
                Yii::error($result, __METHOD__);
            }
            return $result;
        } catch (Exception $exception) {
            Yii::error($exception, __METHOD__);
            return [
                'error' => true,
                'error_code' =>  $exception->getCode(),
                'messages' =>  $exception->getMessage(),
                'data' => []
            ];
        }
    }

    protected function getAuthorization(){
        return [
            'Authorization' => 'Token ' . $this->api_key,
        ];
    }
    /**
     * @param $url
     * @return mixed|string
     */
    private function normalUrl($url)
    {
        if (is_string($url) && strpos($url, 'orders/cancel') !== false) {
            return 'cancel';
        }
        $url = is_string($url) ? explode('/', $url) : $url;
        if (($pop = array_pop($url)) === null) {
            $pop = $this->normalUrl($url);
        }
        return $pop;
    }

}