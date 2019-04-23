<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "draft_missing_tracking".
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
 * @property string $type_tracking split, normal, unknown
 */
class DraftMissingTracking extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_missing_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code'], 'required'],
            [['product_id', 'order_id', 'quantity', 'manifest_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['weight', 'dimension_l', 'dimension_w', 'dimension_h'], 'number'],
            [['image'], 'string'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status', 'item_name', 'warehouse_tag_boxme', 'note_boxme', 'type_tracking'], 'string', 'max' => 255],
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
            'type_tracking' => 'Type Tracking',
        ];
    }
}
