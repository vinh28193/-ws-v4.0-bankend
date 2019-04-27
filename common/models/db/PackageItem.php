<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package_item".
 *
 * @property int $id
 * @property int $package_id id của package
 * @property string $package_code Mã kiện của weshop
 * @property string $box_me_warehouse_tag mã thẻ kho box me
 * @property int $order_id order id
 * @property int $product_id Product id
 * @property int $quantity số lượng
 * @property double $weight cân nặng tịnh , đơn vị gram, box me trả về
 * @property double $change_weight cân nặng quy đổi , đơn vị gram,box me trả về
 * @property double $dimension_l chiều dài , đơn vị cm,box me trả về
 * @property double $dimension_w chiều rộng , đơn vị cm,box me trả về
 * @property double $dimension_h chiều cao , đơn vị cm,box me trả về
 * @property string $stock_in_local
 * @property string $stock_out_local
 * @property string $at_customer
 * @property string $returned
 * @property string $lost
 * @property string $current_status
 * @property int $shipment_id
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property int $remove
 * @property string $price Giá trị 1 sản phẩm
 * @property string $cod
 * @property string $version version 4.0
 * @property int $hold Đánh dấu hàng hold. 1 là hold
 *
 * @property Order $order
 * @property Package $package
 */
class PackageItem extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'order_id', 'product_id', 'quantity', 'stock_in_local', 'stock_out_local', 'at_customer', 'returned', 'lost', 'shipment_id', 'created_at', 'updated_at', 'remove', 'hold'], 'integer'],
            [['weight', 'change_weight', 'dimension_l', 'dimension_w', 'dimension_h', 'price', 'cod'], 'number'],
            [['package_code'], 'string', 'max' => 32],
            [['box_me_warehouse_tag', 'version'], 'string', 'max' => 255],
            [['current_status'], 'string', 'max' => 100],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => Package::className(), 'targetAttribute' => ['package_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_id' => 'DeliveryNote ID',
            'package_code' => 'DeliveryNote Code',
            'box_me_warehouse_tag' => 'Box Me Warehouse Tag',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'weight' => 'Weight',
            'change_weight' => 'Change Weight',
            'dimension_l' => 'Dimension L',
            'dimension_w' => 'Dimension W',
            'dimension_h' => 'Dimension H',
            'stock_in_local' => 'Stock In Local',
            'stock_out_local' => 'Stock Out Local',
            'at_customer' => 'At Customer',
            'returned' => 'Returned',
            'lost' => 'Lost',
            'current_status' => 'Current Status',
            'shipment_id' => 'Shipment ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'price' => 'Price',
            'cod' => 'Cod',
            'version' => 'Version',
            'hold' => 'Hold',
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
    public function getPackage()
    {
        return $this->hasOne(Package::className(), ['id' => 'package_id']);
    }
}
