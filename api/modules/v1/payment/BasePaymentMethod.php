<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-28
 * Time: 11:09
 */

namespace api\modules\v1\payment;


class BasePaymentMethod extends \yii\base\BaseObject
{


    public function getName(){
        return '';
    }
    /**
     * @return string
     */
    public function getNavigator(){
        return '';
    }

    /**
     * @return string
     */
    public function getDetail($payment){
        return '';
    }

}