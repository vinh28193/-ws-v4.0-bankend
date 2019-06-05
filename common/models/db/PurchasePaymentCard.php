<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%purchase_payment_card}}".
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
        return '{{%purchase_payment_card}}';
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
            'id' => Yii::t('db', 'ID'),
            'card_code' => Yii::t('db', 'Card Code'),
            'balance' => Yii::t('db', 'Balance'),
            'current_balance' => Yii::t('db', 'Current Balance'),
            'last_transaction_time' => Yii::t('db', 'Last Transaction Time'),
            'last_amount' => Yii::t('db', 'Last Amount'),
            'store_id' => Yii::t('db', 'Store ID'),
            'status' => Yii::t('db', 'Status'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'created_at' => Yii::t('db', 'Created At'),
        ];
    }
}
