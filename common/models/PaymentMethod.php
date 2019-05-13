<?php


namespace common\models;


class PaymentMethod extends \common\models\db\PaymentMethod
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodBanks()
    {
        return $this->hasMany(PaymentMethodBank::className(), ['payment_method_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodProviders()
    {
        return $this->hasMany(PaymentMethodProvider::className(), ['payment_method_id' => 'id']);
    }
}