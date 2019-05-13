<?php


namespace common\models;


class PaymentMethodBank extends \common\models\db\PaymentMethodBank
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentBank()
    {
        return $this->hasOne(PaymentBank::className(), ['id' => 'payment_bank_id']);
    }
}