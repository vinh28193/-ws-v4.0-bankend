<?php


namespace common\models;

use common\models\db\PaymentTransaction as DbPaymentTransaction;
use yii\db\Expression;

/**
 * Class PaymentTransaction
 * @package common\models
 *
 * @property-read PaymentTransaction[] $childPaymentTransaction
 * @property-read Order $order
 */
class PaymentTransaction extends DbPaymentTransaction
{

    const TRANSACTION_TYPE_PAYMENT = 'PAYMENT';
    const TRANSACTION_ADDFEE = 'ADDFEE';
    const TRANSACTION_TYPE_TOP_UP = 'TOP_UP';
    const TRANSACTION_TYPE_REFUND = 'REFUND';

    const TRANSACTION_STATUS_CREATED = 'CREATED';
    const TRANSACTION_STATUS_QUEUED = 'QUEUED';
    const TRANSACTION_STATUS_FAILED = 'FAILED';
    const TRANSACTION_STATUS_SUCCESS = 'SUCCESS';
    const TRANSACTION_STATUS_REPLACED = 'REPLACED';

    const PAYMENT_TYPE_BUY_NOW = 'buynow';
    const PAYMENT_TYPE_SHOPPING = 'shopping';
    const PAYMENT_TYPE_TOP_UP = 'top_up';
    const PAYMENT_TYPE_ADDFEE = 'addfee';
    const PAYMENT_TYPE_REFUND = 'refund';

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildPaymentTransaction()
    {
        return $this->hasMany(self::className(), ['transaction_code' => 'transaction_code'])->where(['IS NOT','order_code', new Expression('NULL')]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['ordercode' => 'order_code']);
    }

}