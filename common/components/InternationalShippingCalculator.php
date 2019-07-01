<?php


namespace common\components;

use Yii;
use common\helpers\WeshopHelper;
use Courier\CalculateFeeRequest;
use Courier\CalculateFeeResponse;
use Courier\CourierCalculate;
use Courier\CourierClient;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class InternationalShippingCalculator extends BaseObject
{

    const LOCATION_AMAZON = 'AMAZON';
    const LOCATION_EBAY = 'EBAY';
    const LOCATION_EBAY_US = 'EBAY_US';

    public $hostname;
    private $_grpcClient;

    public function init()
    {
        parent::init();
        if ($this->hostname === null) {
            $this->hostname = Yii::$app->params['BOXME_GRPC_SERVICE_COURIER'];
        }

    }

    public function getGrpcClient()
    {
        if (!is_object($this->_grpcClient)) {
            Yii::info("Open connent to hostname {$this->hostname}", __METHOD__);
            $this->_grpcClient = new CourierClient($this->hostname, [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]);
        }
        return $this->_grpcClient;
    }


    public function CalculateFee($params, $userId, $countryCode, $currency = 'VND', $sellerContry = null)
    {
        $request = new CalculateFeeRequest();
        $params = ArrayHelper::merge([
            'config' => [
                'preview' => 'Y',
                'return_mode' => 0,
                'insurance' => 'N',
                'document' => 0,
                'currency' => $currency,
                'unit_metric' => 'metric',
                'sort_mode' => 'best_rating',
                'auto_approve' => 'Y',
                'create_by' => 0,
                'create_from' => 'create_order_netsale',
                'order_type' => 'dropship',
                'check_stock' => 'N',
                'include_special_goods' => 'N'
            ],
            'payment' => [
                'cod_amount' => 0,
                'fee_paid_by' => 'sender'
            ],
            'referral' => [
                'order_number' => 0,
                'coupon_code' => ''
            ]
        ], $params);
        $request->setData(Json::encode($params));

        $request->setUserId($userId);
        $request->setCountryCode($countryCode);
        \Yii::info([
            'setData' => $request->getData(),
            'setUserId' => $request->getUserId(),
            'setCountryCode' => $request->getCountryCode(),
        ]);
        /** @var $apires CalculateFeeResponse */
        $apires = $this->getGrpcClient()->CalculateFee($request)->wait();
        list($response, $status) = $apires;
        /** @var $response CourierCalculate */
        $data = $response->getData();
        $success = $response->getError() === false && $data->count() > 0;
        $message = $response->getErrorMessage();
        \Yii::info($message);
        if (!$success && WeshopHelper::isEmpty($message) && isset($status->details) && is_string($status->details)) {
            $message = $status->details;
        }
        return [$success, !$success ? (WeshopHelper::isEmpty($message) ? ($data->count() > 0 ? 'empty courier assigment' : 'unknown error') : $message) : $this->parserCalculateFeeResponse($data, $sellerContry)];
//        return [true,json_decode('[{"courier_logo":"https:\/\/oms.boxme.asia\/assets\/images\/courier\/boxme.svg","courier_name":"Boxme","service_name":"International Express","service_code":"BM_DEX","shipping_fee":597168,"return_fee":0,"tax_fee":0,"total_fee":597168,"currency":"VND","min_delivery_time":6,"max_delivery_time":8}]',true)];
    }

    /**
     * @param $data \Google\Protobuf\Internal\RepeatedField
     * @param $location
     * @return array
     */
    private function parserCalculateFeeResponse($data, $location = null)
    {
        $results = [];
        Yii::info($this->getAdditionTime($location), $location);
        $location = $this->getAdditionTime($location);

        foreach ($data->getIterator() as $iterator) {
            /** @var $iterator CourierCalculate */
            $courier = [];
            $courier['courier_logo'] = $iterator->getCourierLogo();
            $courier['courier_name'] = $iterator->getCourierName();
            $courier['service_name'] = $iterator->getServiceName();
            $courier['service_code'] = $iterator->getServiceCode();
            $courier['shipping_fee'] = $iterator->getShippingFee();
            $courier['return_fee'] = $iterator->getReturnFee();
            $courier['tax_fee'] = $iterator->getTax();
            $courier['total_fee'] = $iterator->getTotalFee();
            $courier['currency'] = $iterator->getCurrency();
            $courier['min_delivery_time'] = ($location !== false && isset($location['min'])) ? ($iterator->getMinDeliveryTime() + $location['min']) : $iterator->getMinDeliveryTime();
            $courier['max_delivery_time'] = ($location !== false && isset($location['max'])) ? ($iterator->getMaxDeliveryTime() + $location['max']) : $iterator->getMaxDeliveryTime();
            $results[] = $courier;
        }
        return $results;
    }

    public function getAdditionTime($location)
    {
        switch ($location) {
            case self::LOCATION_AMAZON:
                return [
                    'min' => 2,
                    'max' => 3
                ];
            case self::LOCATION_EBAY;
                return [
                    'min' => 7,
                    'max' => 15
                ];
            case  self::LOCATION_EBAY_US:
                return [
                    'min' => 3,
                    'max' => 5
                ];
            default:
                return false;
        }
    }
}