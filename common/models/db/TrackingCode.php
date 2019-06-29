<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%tracking_code}}".
 *
 * @property int $id ID
 * @property string $version version 4.0
 * @property int $store_id Store ID reference
 * @property int $manifest_id Manifest Id
 * @property string $manifest_code Manifest code
 * @property int $delivery_note_id Package id after sent
 * @property string $delivery_note_code Mã kiện của weshop
 * @property int $package_id Package item id after create item
 * @property string $tracking_code
 * @property string $order_ids Order id(s)
 * @property string $weshop_tag Weshop Tag
 * @property int $warehouse_us_id
 * @property string $warehouse_us_name
 * @property int $warehouse_local_id
 * @property string $warehouse_local_name warehouse alias BMVN_HN (Boxme Ha Noi/Boxme HCM)
 * @property string $warehouse_local_tag warehouse tag
 * @property string $warehouse_local_note warehouse note
 * @property string $warehouse_local_status warehouse status (open/close)
 * @property string $weight seller Weight (kg)
 * @property string $quantity seller quantity
 * @property string $dimension_width Width (cm)
 * @property string $dimension_length Length (cm)
 * @property string $dimension_height Height (cm)
 * @property string $operation_note Note
 * @property string $status Status
 * @property int $remove removed or not (1:Removed)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 * @property string $status_merge Trạng thái của tracking với việc đối chiếu tracking với bảng ext
 * @property int $stock_in_us
 * @property int $stock_out_us
 * @property int $stock_in_local
 * @property int $stock_out_local
 * @property string $product_ids list product id Cách nhau dấu (,)
 * @property string $shipment_bm_code Mã shipment bên boxme
 */
class TrackingCode extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tracking_code}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'manifest_id', 'delivery_note_id', 'package_id', 'warehouse_us_id', 'warehouse_local_id', 'remove', 'created_by', 'created_at', 'updated_by', 'updated_at', 'stock_in_us', 'stock_out_us', 'stock_in_local', 'stock_out_local'], 'integer'],
            [['warehouse_local_note', 'operation_note'], 'string'],
            [['weight', 'quantity', 'dimension_width', 'dimension_length', 'dimension_height'], 'number'],
            [['version', 'tracking_code', 'order_ids', 'warehouse_us_name', 'status_merge', 'product_ids', 'shipment_bm_code'], 'string', 'max' => 255],
            [['manifest_code', 'delivery_note_code', 'weshop_tag', 'warehouse_local_name', 'warehouse_local_tag', 'status'], 'string', 'max' => 32],
            [['warehouse_local_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'version' => Yii::t('db', 'Version'),
            'store_id' => Yii::t('db', 'Store ID'),
            'manifest_id' => Yii::t('db', 'Manifest ID'),
            'manifest_code' => Yii::t('db', 'Manifest Code'),
            'delivery_note_id' => Yii::t('db', 'Delivery Note ID'),
            'delivery_note_code' => Yii::t('db', 'Delivery Note Code'),
            'package_id' => Yii::t('db', 'Package ID'),
            'tracking_code' => Yii::t('db', 'Tracking Code'),
            'order_ids' => Yii::t('db', 'Order Ids'),
            'weshop_tag' => Yii::t('db', 'Weshop Tag'),
            'warehouse_us_id' => Yii::t('db', 'Warehouse Us ID'),
            'warehouse_us_name' => Yii::t('db', 'Warehouse Us Name'),
            'warehouse_local_id' => Yii::t('db', 'Warehouse Local ID'),
            'warehouse_local_name' => Yii::t('db', 'Warehouse Local Name'),
            'warehouse_local_tag' => Yii::t('db', 'Warehouse Local Tag'),
            'warehouse_local_note' => Yii::t('db', 'Warehouse Local Note'),
            'warehouse_local_status' => Yii::t('db', 'Warehouse Local Status'),
            'weight' => Yii::t('db', 'Weight'),
            'quantity' => Yii::t('db', 'Quantity'),
            'dimension_width' => Yii::t('db', 'Dimension Width'),
            'dimension_length' => Yii::t('db', 'Dimension Length'),
            'dimension_height' => Yii::t('db', 'Dimension Height'),
            'operation_note' => Yii::t('db', 'Operation Note'),
            'status' => Yii::t('db', 'Status'),
            'remove' => Yii::t('db', 'Remove'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'status_merge' => Yii::t('db', 'Status Merge'),
            'stock_in_us' => Yii::t('db', 'Stock In Us'),
            'stock_out_us' => Yii::t('db', 'Stock Out Us'),
            'stock_in_local' => Yii::t('db', 'Stock In Local'),
            'stock_out_local' => Yii::t('db', 'Stock Out Local'),
            'product_ids' => Yii::t('db', 'Product Ids'),
            'shipment_bm_code' => Yii::t('db', 'Shipment Bm Code'),
        ];
    }
}
