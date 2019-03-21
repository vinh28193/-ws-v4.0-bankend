<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "tracking_code".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property int $package_id Package id after sent
 * @property int $package_item_id Package item id after sent
 * @property string $weshop_tag Package item id after sent
 * @property int $order_id Order id
 * @property int $seller_id Seller
 * @property string $seller_tracking Seller tracking
 * @property string $seller_weight seller Weight (kg)
 * @property string $seller_quantity seller quantity
 * @property string $seller_dimension_width Seller Width (cm)
 * @property string $seller_dimension_length Seller Length (cm)
 * @property string $seller_dimension_height Seller Height (cm)
 * @property int $seller_shipped_at Seller Shipped Time
 * @property int $receiver_warehouse_id receiver warehouse (Kho hop nhat, kho anh lam, kho boxme tai my)
 * @property string $receiver_warehouse_note receiver warehouse note
 * @property int $receiver_warehouse_send_at Time when receiver warehouse send 
 * @property string $local_warehouse_id local warehouse id (Boxme Ha Noi/Boxme HCM)
 * @property string $local_warehouse_tag local warehouse tag
 * @property string $local_warehouse_weight local warehouse weight
 * @property string $local_warehouse_quantity local warehouse quantity
 * @property string $local_warehouse_dimension_width local warehouse Width (cm)
 * @property string $local_warehouse_dimension_length local warehouse Length (cm)
 * @property string $local_warehouse_dimension_height local warehouse Height (cm)
 * @property string $local_warehouse_note local warehouse note
 * @property string $local_warehouse_status warehouse status (open/close)
 * @property int $local_warehouse_send_at Time when send to local warehouse
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
            [['store_id', 'package_id', 'package_item_id', 'order_id', 'seller_id', 'seller_shipped_at', 'receiver_warehouse_id', 'receiver_warehouse_send_at', 'local_warehouse_send_at', 'remove', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['seller_weight', 'seller_quantity', 'seller_dimension_width', 'seller_dimension_length', 'seller_dimension_height', 'local_warehouse_dimension_width', 'local_warehouse_dimension_length', 'local_warehouse_dimension_height', 'local_warehouse_note'], 'number'],
            [['receiver_warehouse_note', 'operation_note'], 'string'],
            [['weshop_tag', 'seller_tracking', 'local_warehouse_id', 'local_warehouse_tag', 'local_warehouse_weight', 'local_warehouse_quantity'], 'string', 'max' => 60],
            [['local_warehouse_status'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 32],
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
            'package_id' => 'Package ID',
            'package_item_id' => 'Package Item ID',
            'weshop_tag' => 'Weshop Tag',
            'order_id' => 'Order ID',
            'seller_id' => 'Seller ID',
            'seller_tracking' => 'Seller Tracking',
            'seller_weight' => 'Seller Weight',
            'seller_quantity' => 'Seller Quantity',
            'seller_dimension_width' => 'Seller Dimension Width',
            'seller_dimension_length' => 'Seller Dimension Length',
            'seller_dimension_height' => 'Seller Dimension Height',
            'seller_shipped_at' => 'Seller Shipped At',
            'receiver_warehouse_id' => 'Receiver Warehouse ID',
            'receiver_warehouse_note' => 'Receiver Warehouse Note',
            'receiver_warehouse_send_at' => 'Receiver Warehouse Send At',
            'local_warehouse_id' => 'Local Warehouse ID',
            'local_warehouse_tag' => 'Local Warehouse Tag',
            'local_warehouse_weight' => 'Local Warehouse Weight',
            'local_warehouse_quantity' => 'Local Warehouse Quantity',
            'local_warehouse_dimension_width' => 'Local Warehouse Dimension Width',
            'local_warehouse_dimension_length' => 'Local Warehouse Dimension Length',
            'local_warehouse_dimension_height' => 'Local Warehouse Dimension Height',
            'local_warehouse_note' => 'Local Warehouse Note',
            'local_warehouse_status' => 'Local Warehouse Status',
            'local_warehouse_send_at' => 'Local Warehouse Send At',
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
