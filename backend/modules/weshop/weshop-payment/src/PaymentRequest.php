<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 16:39
 */

namespace weshop\payment;


class PaymentRequest extends \yii\base\BaseObject
{


    public function isRedirect(){
        return false;
    }
}