<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%purchase_product}}".
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
 * @property int $receive_warehouse_id Id Kho nhận
 * @property string $receive_warehouse_name Tên Kho nhận
 * @property string $seller_refund_amount Số tiền người bán hoàn chả
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
        return '{{%purchase_product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'purchase_order_id', 'order_id', 'purchase_quantity', 'receive_quantity', 'created_at', 'updated_at', 'receive_warehouse_id'], 'integer'],
            [['paid_to_seller', 'changing_price', 'purchase_price', 'purchase_us_tax', 'purchase_shipping_fee', 'seller_refund_amount'], 'number'],
            [['sku', 'product_name', 'image', 'type_changing', 'receive_warehouse_name'], 'string', 'max' => 255],
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
            'id' => Yii::t('db', 'ID'),
            'product_id' => Yii::t('db', 'Product ID'),
            'purchase_order_id' => Yii::t('db', 'Purchase Order ID'),
            'order_id' => Yii::t('db', 'Order ID'),
            'sku' => Yii::t('db', 'Sku'),
            'product_name' => Yii::t('db', 'Product Name'),
            'image' => Yii::t('db', 'Image'),
            'purchase_quantity' => Yii::t('db', 'Purchase Quantity'),
            'receive_quantity' => Yii::t('db', 'Receive Quantity'),
            'paid_to_seller' => Yii::t('db', 'Paid To Seller'),
            'changing_price' => Yii::t('db', 'Changing Price'),
            'type_changing' => Yii::t('db', 'Type Changing'),
            'purchase_price' => Yii::t('db', 'Purchase Price'),
            'purchase_us_tax' => Yii::t('db', 'Purchase Us Tax'),
            'purchase_shipping_fee' => Yii::t('db', 'Purchase Shipping Fee'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'receive_warehouse_id' => Yii::t('db', 'Receive Warehouse ID'),
            'receive_warehouse_name' => Yii::t('db', 'Receive Warehouse Name'),
            'seller_refund_amount' => Yii::t('db', 'Seller Refund Amount'),
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
