<?php


namespace common\payment;


use yii\base\ViewContextInterface;

class PaymentContextView extends \yii\base\BaseObject implements ViewContextInterface
{

    public function getViewPath()
    {
        return \Yii::getAlias('@common/payment/views');
    }
}