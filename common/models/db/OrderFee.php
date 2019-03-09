<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "order_fee".
 *
 * @property int $id
 * @property int $order_id order id
 * @property int $product_id Product Id
 * @property string $type_fee loại phí: đơn giá gốc, ship us, ship nhật , weshop fee, ...
 * @property string $amount_local tiền local
 * @property string $amount tiền ngoại tệ
 * @property string $currency loại tiền ngoại tệ
 * @property string $discount_amount tiền giảm giá
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 *
 * @property Order $order
 */
class OrderFee extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['product_id'], 'required'],
            [['amount_local', 'amount', 'discount_amount'], 'number'],
            [['type_fee', 'currency'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'type_fee' => 'Type Fee',
            'amount_local' => 'Amount Local',
            'amount' => 'Amount',
            'currency' => 'Currency',
            'discount_amount' => 'Discount Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
