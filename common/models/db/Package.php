<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "package".
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $product_id
 * @property int $order_id
 * @property int $quantity
 * @property double $weight
 * @property double $dimension_l
 * @property double $dimension_w
 * @property double $dimension_h
 * @property int $manifest_id
 * @property string $manifest_code
 * @property string $purchase_invoice_number
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $item_name tên sản phẩm trả về từ boxme
 * @property string $warehouse_tag_boxme wtag của boxme
 * @property string $note_boxme note của boxme
 * @property string $image
 * @property string $tracking_merge  List tracking khi merge từ thừa và thiếu 
 * @property int $hold DDasnhh dấu hàng hold. 1 là hold
 * @property string $type_tracking split, normal, unknown
 * @property string $seller_refund_amount Sô tiền seller hoàn
 * @property int $draft_data_tracking_id
 * @property int $stock_in_local Thời gian nhập kho local
 * @property int $stock_out_local Thời gian xuất kho local
 * @property int $at_customer Thời gian tới tay khách hàng
 * @property int $returned Thời gian hoàn trả
 * @property int $lost Thời gian mất hàng
 * @property string $current_status Trạng thái hiện tại
 * @property int $shipment_id
 * @property int $remove Xoá
 * @property string $price Giá trị của 1 sản phẩm
 * @property string $cod Tiền cod
 * @property string $version Version
 * @property int $delivery_note_id
 * @property string $delivery_note_code
 * @property string $ws_tracking_code Mã tracking của weshop
 * @property string $package_code
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
            [['tracking_code'], 'required'],
            [['product_id', 'order_id', 'quantity', 'manifest_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'hold', 'draft_data_tracking_id', 'stock_in_local', 'stock_out_local', 'at_customer', 'returned', 'lost', 'shipment_id', 'remove', 'delivery_note_id'], 'integer'],
            [['weight', 'dimension_l', 'dimension_w', 'dimension_h', 'seller_refund_amount', 'price', 'cod'], 'number'],
            [['image', 'tracking_merge'], 'string'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status', 'item_name', 'warehouse_tag_boxme', 'note_boxme', 'type_tracking', 'current_status', 'version', 'delivery_note_code', 'ws_tracking_code', 'package_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracking_code' => 'Tracking Code',
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'quantity' => 'Quantity',
            'weight' => 'Weight',
            'dimension_l' => 'Dimension L',
            'dimension_w' => 'Dimension W',
            'dimension_h' => 'Dimension H',
            'manifest_id' => 'Manifest ID',
            'manifest_code' => 'Manifest Code',
            'purchase_invoice_number' => 'Purchase Invoice Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'item_name' => 'Item Name',
            'warehouse_tag_boxme' => 'Warehouse Tag Boxme',
            'note_boxme' => 'Note Boxme',
            'image' => 'Image',
            'tracking_merge' => 'Tracking Merge',
            'hold' => 'Hold',
            'type_tracking' => 'Type Tracking',
            'seller_refund_amount' => 'Seller Refund Amount',
            'draft_data_tracking_id' => 'Draft Data Tracking ID',
            'stock_in_local' => 'Stock In Local',
            'stock_out_local' => 'Stock Out Local',
            'at_customer' => 'At Customer',
            'returned' => 'Returned',
            'lost' => 'Lost',
            'current_status' => 'Current Status',
            'shipment_id' => 'Shipment ID',
            'remove' => 'Remove',
            'price' => 'Price',
            'cod' => 'Cod',
            'version' => 'Version',
            'delivery_note_id' => 'Delivery Note ID',
            'delivery_note_code' => 'Delivery Note Code',
            'ws_tracking_code' => 'Ws Tracking Code',
            'package_code' => 'Package Code',
        ];
    }
}
