<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "draft_boxme_tracking".
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $product_id
 * @property int $order_id
 * @property int $manifest_id
 * @property string $manifest_code
 * @property string $purchase_invoice_number
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class DraftBoxmeTracking extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_boxme_tracking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code'], 'required'],
            [['product_id', 'order_id', 'manifest_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['tracking_code', 'manifest_code', 'purchase_invoice_number', 'status'], 'string', 'max' => 255],
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
            'manifest_id' => 'Manifest ID',
            'manifest_code' => 'Manifest Code',
            'purchase_invoice_number' => 'Purchase Invoice Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
