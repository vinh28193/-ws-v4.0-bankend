<?php
namespace Accouting;

use Accouting\GetListMerchantByIdRequest;
use Accouting\GetListMerchantByIdResponse;
use Yii;
use Grpc;

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
    public function GetListMerchantById(GetListMerchantByIdRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Accouting.Accouting/GetListMerchantById',
            $argument,
            ['\Accouting\GetListMerchantByIdResponse', 'decode'],
            $metadata, $options);
    }
}
