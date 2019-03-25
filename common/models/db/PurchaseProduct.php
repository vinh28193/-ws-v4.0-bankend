<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "purchase_product".
 *
 * @property int $id
 * @property int $product_id id product mua
 * @property int $purchase_order_id id purchase order
 * @property int $order_id id order
 * @property string $sku Mã sku của sản phẩm mua
 * @property string $product_name Tên sản phẩm
 * @property string $image Ảnh sản phẩm
 * @property int $purchase_quantity số lượng mua
 * @property int $receive_quantity số lượng nhận
 * @property string $paid_to_seller Số tiền đã thanh toán cho người bán
 * @property string $changing_price số tiền chênh lệch giá . amount_purchase - amount_order
 * @property string $type_changing kiểu chênh lệch: up, down
 * @property string $purchase_price giá khi đi mua
 * @property string $purchase_us_tax us tax khi đi mua
 * @property string $purchase_shipping_fee phí ship khi đi mua
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Order $order
 * @property Product $product
 * @property PurchaseOrder $purchaseOrder
 */
class PurchaseProduct extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'purchase_order_id', 'order_id', 'purchase_quantity', 'receive_quantity', 'created_at', 'updated_at'], 'integer'],
            [['paid_to_seller', 'changing_price', 'type_changing', 'purchase_price', 'purchase_us_tax', 'purchase_shipping_fee'], 'number'],
            [['sku', 'product_name', 'image'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['purchase_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PurchaseOrder::className(), 'targetAttribute' => ['purchase_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'purchase_order_id' => 'Purchase Order ID',
            'order_id' => 'Order ID',
            'sku' => 'Sku',
            'product_name' => 'Product Name',
            'image' => 'Image',
            'purchase_quantity' => 'Purchase Quantity',
            'receive_quantity' => 'Receive Quantity',
            'paid_to_seller' => 'Paid To Seller',
            'changing_price' => 'Changing Price',
            'type_changing' => 'Type Changing',
            'purchase_price' => 'Purchase Price',
            'purchase_us_tax' => 'Purchase Us Tax',
            'purchase_shipping_fee' => 'Purchase Shipping Fee',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'purchase_order_id']);
    }
}
