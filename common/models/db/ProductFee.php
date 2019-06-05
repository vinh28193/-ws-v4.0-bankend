<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%product_fee}}".
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
class ProductFee extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_fee}}';
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
            'id' => Yii::t('db', 'ID'),
            'order_id' => Yii::t('db', 'Order ID'),
            'product_id' => Yii::t('db', 'Product ID'),
            'type' => Yii::t('db', 'Type'),
            'name' => Yii::t('db', 'Name'),
            'amount' => Yii::t('db', 'Amount'),
            'local_amount' => Yii::t('db', 'Local Amount'),
            'discount_amount' => Yii::t('db', 'Discount Amount'),
            'currency' => Yii::t('db', 'Currency'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'remove' => Yii::t('db', 'Remove'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
