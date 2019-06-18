<?php


namespace common\boxme;


use common\helpers\WeshopHelper;
use Courier\CalculateFeeRequest;
use Courier\CalculateFeeResponse;
use Courier\CourierCalculate;
use Courier\CourierClient;
use Yii;
use yii\base\BaseObject;
use common\components\AdditionalFeeInterface;
use yii\helpers\Json;

class InternationalShippingCalculator extends BaseObject
{

    private $_grpcClient;

    public function getGrpcClient()
    {
        if (!is_object($this->_grpcClient)) {
            $this->_grpcClient = new CourierClient('206.189.94.203:50056', [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]);
        }
        return $this->_grpcClient;
    }


    public function CalculateFee($shipmentData, $userId, $countryCode)
    {
        $request = new CalculateFeeRequest();
        $request->setData(Json::encode($shipmentData));
        $request->setUserId($userId);
        $request->setCountryCode($countryCode);
        list($response, $status) = $this->getGrpcClient()->CalculateFee($request)->wait();
        /** @var $response CalculateFeeResponse */
        $data = $response->getData();
        $success = $response->getError() === false && $data->count() > 0;
        $message = $response->getErrorMessage();
        if (!$success && WeshopHelper::isEmpty($message) && isset($status->details) && is_string($status->details)) {
            $message = $status->details;
        }
        return [$success, !$success ? (WeshopHelper::isEmpty($message) ? ($data->count() > 0 ? 'empty courier assigment' : 'unknown error') : $message) : $this->parserCalculateFeeResponse($data)];
//        return [true,json_decode('[{"courier_logo":"https:\/\/oms.boxme.asia\/assets\/images\/courier\/boxme.svg","courier_name":"Boxme","service_name":"International Express","service_code":"BM_DEX","shipping_fee":597168,"return_fee":0,"tax_fee":0,"total_fee":597168,"currency":"VND","min_delivery_time":6,"max_delivery_time":8}]',true)];
    }

    /**
     * @param $data \Google\Protobuf\Internal\RepeatedField
     * @return array
     */
    private function parserCalculateFeeResponse($data)
    {
        $results = [];
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
            $courier['min_delivery_time'] = $iterator->getMinDeliveryTime();
            $courier['max_delivery_time'] = $iterator->getMaxDeliveryTime();
            $results[] = $courier;
        }
        return $results;
    }

    public function trace(AdditionalFeeInterface $additional)
    {
        $feeValue = rand(1, 99);
        return $feeValue;
    }
}