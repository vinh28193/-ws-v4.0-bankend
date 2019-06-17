<?php
namespace User;

use User\SignUpRequest;
use User\SignUpResponse;
use Yii;
use Grpc;

/**
 */
class UserClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    public function SignUp(SignUpRequest $argument, $metadata = [], $options = []) {
        return $this->_simpleRequest('/User.UserService/SignUp',
            $argument,
            ['\User\SignUpResponse', 'decode'],
            $metadata, $options);
    }
}
