<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "order_fee".
 *
 * @property int $id
 * @property int $order_id order id
 * @property int $product_id Product Id
 * @property string $type loại phí: đơn giá gốc, ship us, ship nhật , weshop fee, ...
 * @property string $name
 * @property string $amount Amount
 * @property string $local_amount Local amount
 * @property string $discount_amount Discount of type fee
 * @property string $currency loại tiền ngoại tệ
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $version version 4.0
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
            [['product_id', 'name'], 'required'],
            [['amount', 'local_amount', 'discount_amount'], 'number'],
            [['type', 'currency', 'version'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 60],
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
            'type' => 'Type',
            'name' => 'Name',
            'amount' => 'Amount',
            'local_amount' => 'Local Amount',
            'discount_amount' => 'Discount Amount',
            'currency' => 'Currency',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
        ];
    }
}
