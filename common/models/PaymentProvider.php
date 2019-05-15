<?php


namespace common\models;


class PaymentProvider extends \common\models\db\PaymentProvider
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodProviders()
    {
        return $this->hasMany(PaymentMethodProvider::className(), ['payment_provider_id' => 'id'])->joinWith(['paymentMethod' => function ($q) {
            $q->where(['payment_method.status' => 1]);
        }], false);
    }
}