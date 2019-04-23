<?php


namespace common\boxme;

use Yii;
use Exception;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

class BoxmeClient extends BaseObject
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
            throw new InvalidConfigException("params for env `{$this->env}` can not be null");
        }
        if (!isset($params['api_key']) || !isset($params['base_url'])) {
            throw new InvalidConfigException("params for env `{$this->env}` can not missing config of `api_key` and `base_url`");
        }
        $this->api_key = $params['api_key'];
        $this->base_url = $params['base_url'];
    }

    public function createRequest($url, $data)
    {
        $client = new Client([
            'baseUrl' => $this->base_url,
            'transport' => CurlTransport::className()
        ]);
        $request = $client->createRequest();
        $request->setFormat(Client::FORMAT_JSON);
        $request->setMethod('POST');
        $request->setUrl($url);
        $request->setData($data);
        $request->addHeaders([
            'Authorization' => 'Token ' . $this->api_key,
        ]);
        try {
            $response = $client->send($request);
            $result = $response->getData();
            // Todo Log here
            if (!$response->getIsOk()) {
                Yii::error($result, __METHOD__);
                return false;
            }
            return $result;
        } catch (Exception $exception) {
            // Todo Log Exception
            Yii::error($exception, __METHOD__);
            return false;
        }
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

    /**
     * Create Order Domestic
     * @param $params
     * @return bool|mixed
     */
    public function createOrder($params)
    {
        return $this->createRequest('courier/pricing/calculate', $params);
    }

    /**
     * @param $params
     * @return bool|mixed
     */
    public function pricingCalculate($params)
    {
        return $this->createRequest('courier/pricing/create_order', $params);
    }

    /**
     * @param $orderCode
     * @return bool|mixed
     */
    public function cancelOrder($orderCode)
    {
        return $this->createRequest("orders/cancel/$orderCode/", []);
    }

    /**
     * @param $orderCode
     * @param string $note
     * @return bool|mixed
     */
    public function returnOrder($orderCode, $note = 'Reason for return order')
    {
        return $this->createRequest('courier/intergrate/confirm_return_tracking_code', [
            'tracking_code' => $orderCode,
            'note' => $note
        ]);
    }

    /**
     * @param $orderCode
     * @param string $note
     * @return bool|mixed
     */
    public function reshipOrder($orderCode, $note = 'Reason for reshipping  order')
    {
        return $this->createRequest('courier/intergrate/confirm_delivery_tracking_code', [
            'tracking_code' => $orderCode,
            'note' => $note
        ]);
    }

    /**
     * @param $params
     * @return bool|mixed
     */
    public function shippingLabel($params)
    {
        return $this->createRequest('courier/intergrate/confirm_delivery_tracking_code', $params);
    }
}