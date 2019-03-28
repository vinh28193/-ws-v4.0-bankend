<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-28
 * Time: 10:19
 */

namespace common\components\log\driver;

use Yii;
use common\modelsMongo\PaymentLogWS;
use common\components\log\LoggingDriverInterface;

class PaymentLog extends PaymentLogWS implements  LoggingDriverInterface
{
    public function getProvider()
    {
        return 'Payment';
    }

    public function push($action, $message, $params = [])
    {
        // TODO: Implement push() method.
    }

    public function pull($condition)
    {
        // TODO: Implement pull() method.
    }
}