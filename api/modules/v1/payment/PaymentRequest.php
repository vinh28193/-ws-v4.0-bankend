<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 16:39
 */

namespace api\modules\v1\payment;


class PaymentRequest extends \yii\base\BaseObject
{


    public function isRedirect(){
        return false;
    }
}