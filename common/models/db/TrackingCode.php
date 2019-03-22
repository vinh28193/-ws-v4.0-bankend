<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "tracking_code".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property int $manifest_id Manifest Id
 * @property string $manifest_code Manifest code
 * @property int $package_id Package id after sent
 * @property string $package_code Mã kiện của weshop
 * @property int $package_item_id Package item id after create item
 * @property string $tracking_code Tracking code
 * @property int $order_ids Order id(s)
 * @property string $weshop_tag Weshop Tag
 * @property string $warehouse_alias warehouse alias BMVN_HN (Boxme Ha Noi/Boxme HCM)
 * @property string $warehouse_tag warehouse tag
 * @property string $warehouse_note warehouse note
 * @property string $warehouse_status warehouse status (open/close)
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
 */
class TrackingCode extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tracking_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'manifest_id', 'package_id', 'package_item_id', 'order_ids', 'remove', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['warehouse_note', 'operation_note'], 'string'],
            [['weight', 'quantity', 'dimension_width', 'dimension_length', 'dimension_height'], 'number'],
            [['manifest_code', 'package_code', 'tracking_code', 'weshop_tag', 'warehouse_alias', 'warehouse_tag', 'status'], 'string', 'max' => 32],
            [['warehouse_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'manifest_id' => 'Manifest ID',
            'manifest_code' => 'Manifest Code',
            'package_id' => 'Package ID',
            'package_code' => 'Package Code',
            'package_item_id' => 'Package Item ID',
            'tracking_code' => 'Tracking Code',
            'order_ids' => 'Order Ids',
            'weshop_tag' => 'Weshop Tag',
            'warehouse_alias' => 'Warehouse Alias',
            'warehouse_tag' => 'Warehouse Tag',
            'warehouse_note' => 'Warehouse Note',
            'warehouse_status' => 'Warehouse Status',
            'weight' => 'Weight',
            'quantity' => 'Quantity',
            'dimension_width' => 'Dimension Width',
            'dimension_length' => 'Dimension Length',
            'dimension_height' => 'Dimension Height',
            'operation_note' => 'Operation Note',
            'status' => 'Status',
            'remove' => 'Remove',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\TrackingCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\TrackingCodeQuery(get_called_class());
    }
}
