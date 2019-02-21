<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package_item".
 *
 * @property int $id
 * @property int $package_id id của package
 * @property int $package_code mã kiện của weshop
 * @property string $box_me_warehouse_tag mã thẻ kho box me
 * @property int $order_id order id
 * @property string $sku sku sản phẩm
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
 * @property string $created_time thời gian tạo
 * @property string $updated_time thời gian cập nhật
 *
 * @property Order $order
 * @property Package $package
 */
class PackageItem extends \yii\db\ActiveRecord
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
            [['package_id', 'package_code', 'order_id', 'quantity', 'stock_in_local', 'stock_out_local', 'at_customer', 'returned', 'lost', 'current_status', 'shipment_id', 'created_time', 'updated_time'], 'integer'],
            [['weight', 'change_weight', 'dimension_l', 'dimension_w', 'dimension_h'], 'number'],
            [['box_me_warehouse_tag', 'sku'], 'string', 'max' => 255],
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
            'package_id' => 'Package ID',
            'package_code' => 'Package Code',
            'box_me_warehouse_tag' => 'Box Me Warehouse Tag',
            'order_id' => 'Order ID',
            'sku' => 'Sku',
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
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
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
