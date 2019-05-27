<?php

namespace common\modelsMongo;

use Yii;

/**
 * This is the model class for collection "Wallet_log".
 *
 * @property \MongoId|string $_id
 * @property mixed $order_transaction
 * @property mixed $action
 * @property mixed $payment_transaction
 * @property mixed $request
 * @property mixed $respone
 * @property mixed $create_time
 */
class WalletLog extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['Weshop_log_40', 'Wallet_log'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'order_transaction',
            'action',
            'payment_transaction',
            'request',
            'respone',
            'create_time',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_transaction', 'action', 'payment_transaction', 'request', 'respone', 'create_time'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'order_transaction' => 'Order Transaction',
            'action' => 'Action',
            'payment_transaction' => 'Payment Transaction',
            'request' => 'Request',
            'respone' => 'Respone',
            'create_time' => 'Create Time',
        ];
    }
}
