<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package".
 *
 * @property int $id
 * @property string $package_code Mã kiện của weshop
 * @property string $tracking_seller mã giao dịch của weshop
 * @property string $order_ids List mã order cách nhau bằng dấu ,
 * @property string $tracking_reference_1 mã tracking tham chiếu 1
 * @property string $tracking_reference_2 mã tracking tham chiếu 2
 * @property string $manifest_code mã lô hàng
 * @property double $package_weight cân nặng tịnh của cả gói , đơn vị gram
 * @property double $package_change_weight cân nặng quy đổi của cả gói , đơn vị gram
 * @property double $package_dimension_l chiều dài của cả gói , đơn vị cm
 * @property double $package_dimension_w chiều rộng của cả gói , đơn vị cm
 * @property double $package_dimension_h chiều cao của cả gói , đơn vị cm
 * @property string $seller_shipped
 * @property string $stock_in_us
 * @property string $stock_out_us
 * @property string $stock_in_local
 * @property string $lost
 * @property string $current_status
 * @property int $warehouse_id id kho nhận
 * @property string $created_at thời gian tạo
 * @property string $updated_at thời gian cập nhật
 * @property int $remove
 * @property string $version version 4.0
 * @property int $shipment_id
 */
class Package extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_ids', 'tracking_reference_1', 'tracking_reference_2', 'manifest_code'], 'string'],
            [['package_weight', 'package_change_weight', 'package_dimension_l', 'package_dimension_w', 'package_dimension_h'], 'number'],
            [['seller_shipped', 'stock_in_us', 'stock_out_us', 'stock_in_local', 'lost', 'warehouse_id', 'created_at', 'updated_at', 'remove', 'shipment_id'], 'integer'],
            [['package_code'], 'string', 'max' => 32],
            [['tracking_seller', 'version'], 'string', 'max' => 255],
            [['current_status'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'package_code' => 'Package Code',
            'tracking_seller' => 'Tracking Seller',
            'order_ids' => 'Order Ids',
            'tracking_reference_1' => 'Tracking Reference 1',
            'tracking_reference_2' => 'Tracking Reference 2',
            'manifest_code' => 'Manifest Code',
            'package_weight' => 'Package Weight',
            'package_change_weight' => 'Package Change Weight',
            'package_dimension_l' => 'Package Dimension L',
            'package_dimension_w' => 'Package Dimension W',
            'package_dimension_h' => 'Package Dimension H',
            'seller_shipped' => 'Seller Shipped',
            'stock_in_us' => 'Stock In Us',
            'stock_out_us' => 'Stock Out Us',
            'stock_in_local' => 'Stock In Local',
            'lost' => 'Lost',
            'current_status' => 'Current Status',
            'warehouse_id' => 'Warehouse ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remove' => 'Remove',
            'version' => 'Version',
            'shipment_id' => 'Shipment ID',
        ];
    }
}
