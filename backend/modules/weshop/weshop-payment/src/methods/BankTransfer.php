<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-01
 * Time: 09:51
 */

namespace weshop\payment\methods;

class BankTransfer extends \weshop\payment\BasePaymentMethod
{

    public function getName()
    {
        return 'BankTransfer';
    }
}