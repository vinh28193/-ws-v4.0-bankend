<?php


namespace frontend\modules\checkout;

use Yii;
use yii\base\ViewContextInterface;

class PaymentViewContext implements ViewContextInterface
{

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return Yii::getAlias('@frontend/modules/checkout/views/payment');
    }
}