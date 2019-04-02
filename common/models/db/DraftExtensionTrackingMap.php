<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "draft_extension_tracking_map".
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $product_id
 * @property int $order_id
 * @property string $purchase_invoice_number
 * @property string $status trạng thái của tracking bên us
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class DraftExtensionTrackingMap extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'draft_extension_tracking_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code', 'product_id', 'order_id', 'purchase_invoice_number'], 'required'],
            [['product_id', 'order_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['tracking_code', 'purchase_invoice_number', 'status'], 'string', 'max' => 255],
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
            'purchase_invoice_number' => 'Purchase Invoice Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
