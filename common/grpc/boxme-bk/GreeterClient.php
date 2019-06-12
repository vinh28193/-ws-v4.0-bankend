<?php
// GENERATED CODE -- DO NOT EDIT!

namespace common\grpc\boxme;
use common\grpc\boxme\Accouting\GetListMerchantByIdResponse;
use common\grpc\boxme\Accouting\GetListMerchantByIdRequest;


/**
 */
class GreeterClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param GetListMerchantByIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetListMerchantById(GetListMerchantByIdResponse $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Accouting.Accouting/GetListMerchantById',
        $argument,
        ['GetListMerchantByIdResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param GetListMerchantByIdRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function SayRepeated(GetListMerchantByIdRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/greeter.Greeter/SayRepeated',
        $argument,
        ['GetListMerchantByIdResponse', 'decode'],
        $metadata, $options);
    }

}
