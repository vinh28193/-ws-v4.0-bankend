<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "purchase_payment_card".
 *
 * @property int $id
 * @property string $card_code
 * @property string $balance
 * @property string $current_balance
 * @property int $last_transaction_time
 * @property int $last_amount
 * @property int $store_id
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 */
class PurchasePaymentCard extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_payment_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance', 'current_balance'], 'number'],
            [['last_transaction_time', 'last_amount', 'store_id', 'status', 'updated_at', 'created_at'], 'integer'],
            [['card_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_code' => 'Card Code',
            'balance' => 'Balance',
            'current_balance' => 'Current Balance',
            'last_transaction_time' => 'Last Transaction Time',
            'last_amount' => 'Last Amount',
            'store_id' => 'Store ID',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
