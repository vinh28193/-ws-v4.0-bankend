<?php
namespace Courier;

use Yii;
use Grpc;

/**
 */
class CourierClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param CalculateFeeRequest $argument
     * @param array $metadata
     * @param array $options
     * @return Grpc\UnaryCall
     */
    public function CalculateFee(CalculateFeeRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Courier.CourierService/CalculateFee',
            $argument,
            ['\Courier\CalculateFeeResponse', 'decode'],
            $metadata, $options);
    }

    /**
     * @param CreateOrderRequest $argument
     * @param array $metadata
     * @param array $options
     * @return Grpc\UnaryCall
     */
    public function CreateOrder(CreateOrderRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Courier.CourierService/CreateOrder',
            $argument,
            ['\Courier\CreateOrderResponse', 'decode'],
            $metadata, $options);
    }
}
