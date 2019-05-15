<?php


namespace common\models;


/**
 * Class PaymentMethodProvider
 * @package common\models
 *
 * @property PaymentProvider $paymentProvider
 * @property PaymentMethod $paymentMethod
 */
class PaymentMethodProvider extends \common\models\db\PaymentMethodProvider
{


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentProvider()
    {
        return $this->hasOne(PaymentProvider::className(), ['id' => 'payment_provider_id'])->where(['payment_provider.status' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id'])->where(['payment_method.status' => 1]);
    }
}