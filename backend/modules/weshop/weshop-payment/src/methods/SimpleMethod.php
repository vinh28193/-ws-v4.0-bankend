<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-02
 * Time: 09:30
 */

namespace weshop\payment\methods;

/**
 * các payment provider không dùng methods nào (ví dụ wallet, cod, thanh toán tại văn phòng ...) thì dùng.
 * Class SimpleMethod
 * @package weshop\payment\methods
 */
class SimpleMethod extends \weshop\payment\BasePaymentMethod
{

    public function getName()
    {
        return $this->owner->getName();
    }
}