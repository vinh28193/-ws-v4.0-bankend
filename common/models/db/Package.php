<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%package}}".
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
 * @property int $hold Đánh dấu hàng hold. 1 là hold
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
 * @property int $stock_in_us
 * @property int $stock_out_us
 * @property int $insurance 0: auto, 1: insurance, 2: unInsurance
 * @property int $pack_wood 0: unInsurance, 1: insurance
 */
class Package extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%package}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code'], 'required'],
            [['product_id', 'order_id', 'quantity', 'manifest_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'hold', 'draft_data_tracking_id', 'stock_in_local', 'stock_out_local', 'at_customer', 'returned', 'lost', 'shipment_id', 'remove', 'delivery_note_id', 'stock_in_us', 'stock_out_us', 'insurance', 'pack_wood'], 'integer'],
            [['weight', 'dimension_l', 'dimension_w', 'dimension_h', 'seller_refund_amount', 'price', 'cod'], 'number'],
            [['image', 'tracking_merge'], 'string'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status', 'item_name', 'warehouse_tag_boxme', 'note_boxme', 'type_tracking', 'current_status', 'version', 'ws_tracking_code', 'package_code'], 'string', 'max' => 255],
            [['delivery_note_code'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'tracking_code' => Yii::t('db', 'Tracking Code'),
            'product_id' => Yii::t('db', 'Product ID'),
            'order_id' => Yii::t('db', 'Order ID'),
            'quantity' => Yii::t('db', 'Quantity'),
            'weight' => Yii::t('db', 'Weight'),
            'dimension_l' => Yii::t('db', 'Dimension L'),
            'dimension_w' => Yii::t('db', 'Dimension W'),
            'dimension_h' => Yii::t('db', 'Dimension H'),
            'manifest_id' => Yii::t('db', 'Manifest ID'),
            'manifest_code' => Yii::t('db', 'Manifest Code'),
            'purchase_invoice_number' => Yii::t('db', 'Purchase Invoice Number'),
            'status' => Yii::t('db', 'Status'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'created_by' => Yii::t('db', 'Created By'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'item_name' => Yii::t('db', 'Item Name'),
            'warehouse_tag_boxme' => Yii::t('db', 'Warehouse Tag Boxme'),
            'note_boxme' => Yii::t('db', 'Note Boxme'),
            'image' => Yii::t('db', 'Image'),
            'tracking_merge' => Yii::t('db', 'Tracking Merge'),
            'hold' => Yii::t('db', 'Hold'),
            'type_tracking' => Yii::t('db', 'Type Tracking'),
            'seller_refund_amount' => Yii::t('db', 'Seller Refund Amount'),
            'draft_data_tracking_id' => Yii::t('db', 'Draft Data Tracking ID'),
            'stock_in_local' => Yii::t('db', 'Stock In Local'),
            'stock_out_local' => Yii::t('db', 'Stock Out Local'),
            'at_customer' => Yii::t('db', 'At Customer'),
            'returned' => Yii::t('db', 'Returned'),
            'lost' => Yii::t('db', 'Lost'),
            'current_status' => Yii::t('db', 'Current Status'),
            'shipment_id' => Yii::t('db', 'Shipment ID'),
            'remove' => Yii::t('db', 'Remove'),
            'price' => Yii::t('db', 'Price'),
            'cod' => Yii::t('db', 'Cod'),
            'version' => Yii::t('db', 'Version'),
            'delivery_note_id' => Yii::t('db', 'Delivery Note ID'),
            'delivery_note_code' => Yii::t('db', 'Delivery Note Code'),
            'ws_tracking_code' => Yii::t('db', 'Ws Tracking Code'),
            'package_code' => Yii::t('db', 'Package Code'),
            'stock_in_us' => Yii::t('db', 'Stock In Us'),
            'stock_out_us' => Yii::t('db', 'Stock Out Us'),
            'insurance' => Yii::t('db', 'Insurance'),
            'pack_wood' => Yii::t('db', 'Pack Wood'),
        ];
    }
}
