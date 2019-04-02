<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 10:19
 */

namespace common\components\log\driver;

use common\components\log\Logging;
use common\components\log\LoggingDriverInterface;
use common\modelsMongo\PaymentLogWS;

class PaymentLog extends PaymentLogWS implements LoggingDriverInterface
{

    /**
     * PaymentLog constructor.
     * @param Logging $logging
     * @param array $config
     */
    public function __construct(Logging $logging, $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return 'Payment';
    }

    /**
     * @param string $action
     * @param string $message
     * @param array $params
     * @return bool|mixed|void
     */
    public function push($action, $message, $params = [])
    {
        // TODO: Implement push() method.
    }

    /**
     * @param array|string $condition
     * @return mixed|void
     */
    public function pull($condition)
    {
        // TODO: Implement pull() method.
    }
}