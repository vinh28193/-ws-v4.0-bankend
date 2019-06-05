<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%draft_wasting_tracking}}".
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
 * @property string $tracking_merge List tracking đã được merge
 */
class DraftWastingTracking extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%draft_wasting_tracking}}';
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
            [['image', 'tracking_merge'], 'string'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status', 'item_name', 'warehouse_tag_boxme', 'note_boxme', 'type_tracking'], 'string', 'max' => 255],
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
            'type_tracking' => Yii::t('db', 'Type Tracking'),
            'tracking_merge' => Yii::t('db', 'Tracking Merge'),
        ];
    }
}
