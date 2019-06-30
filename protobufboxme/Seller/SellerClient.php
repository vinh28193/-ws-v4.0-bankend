<?php


namespace Seller;

use Grpc\BaseStub;

class SellerClient extends BaseStub implements SellerServiceInterface {
    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     * @throws \Exception
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param WsCreateCahinRequest $argument
     * @param array $metadata
     * @param array $options
     * @return \Grpc\UnaryCall
     */
    public function WsCreateCahin(WsCreateCahinRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Seller.SellerService/WsCreateCahin',
            $argument,
            ['\Seller\WsCreateCahinResponse', 'decode'],
            $metadata, $options);
    }

    /**
     * @param CreateShipmentRequest $argument
     * @param array $metadata
     * @param array $options
     * @return \Grpc\UnaryCall
     */
    public function CreateShipment(CreateShipmentRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Seller.SellerService/CreateShipment',
            $argument,
            ['\Seller\CreateShipmentResponse', 'decode'],
            $metadata, $options);
    }

    /**
     * @param SyncProductRequest $argument
     * @param array $metadata
     * @param array $options
     * @return \Grpc\UnaryCall
     */
    public function SyncProduct(SyncProductRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/Seller.SellerService/SyncProduct',
            $argument,
            ['\Seller\SyncProductResponse', 'decode'],
            $metadata, $options);
    }
}