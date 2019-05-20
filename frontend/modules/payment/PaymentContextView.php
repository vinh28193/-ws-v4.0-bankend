<?php


namespace frontend\modules\payment;

use Yii;
use yii\base\BaseObject;
use yii\base\ViewContextInterface;

class PaymentContextView extends BaseObject implements ViewContextInterface
{

    public function getViewPath()
    {
        return Yii::getAlias('@common/payment/views');
    }
}