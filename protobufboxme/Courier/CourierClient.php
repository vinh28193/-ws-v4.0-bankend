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

    public function SignUp(SignUpRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/user.UserService/SignUp',
            $argument,
            ['\User\SignUpResponse', 'decode'],
            $metadata, $options);
    }
}
