<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%target_additional_fee}}".
 *
 * @property int $id
 * @property int $order_id order id
 * @property string $target product/order/payment
 * @property int $target_id Product Id
 * @property string $name loại phí: đơn giá gốc, ship us, ship nhật , weshop fee, ...
 * @property string $label
 * @property string $amount Amount
 * @property string $local_amount Local amount
 * @property string $discount_amount Discount of type fee
 * @property string $currency loại tiền ngoại tệ
 * @property string $created_at
 * @property string $updated_at
 * @property int $remove
 * @property string $version version 4.0
 * @property string $type
 */
class TargetAdditionalFee extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%target_additional_fee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'target_id', 'created_at', 'updated_at', 'remove'], 'integer'],
            [['target', 'target_id', 'label', 'type'], 'required'],
            [['amount', 'local_amount', 'discount_amount'], 'number'],
            [['target', 'type'], 'string', 'max' => 32],
            [['name', 'currency', 'version'], 'string', 'max' => 255],
            [['label'], 'string', 'max' => 60],
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
            'target' => 'Target',
            'target_id' => 'Target ID',
            'name' => 'Name',
            'label' => 'Label',
            'amount' => 'Amount',
            'local_amount' => 'Local Amount',
            'discount_amount' => 'Discount Amount',
            'currency' => 'Currency',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
            'type' => 'Type',
        ];
    }
}
