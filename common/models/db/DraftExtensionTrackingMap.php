<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%draft_extension_tracking_map}}".
 *
 * @property int $id
 * @property string $tracking_code
 * @property int $product_id
 * @property int $order_id
 * @property string $purchase_invoice_number
 * @property string $status trạng thái của tracking bên us
 * @property int $quantity
 * @property double $weight
 * @property double $dimension_l
 * @property double $dimension_w
 * @property double $dimension_h
 * @property int $number_run
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $draft_data_tracking_id
 */
class DraftExtensionTrackingMap extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%draft_extension_tracking_map}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tracking_code', 'product_id', 'order_id', 'purchase_invoice_number'], 'required'],
            [['product_id', 'order_id', 'quantity', 'number_run', 'created_at', 'updated_at', 'created_by', 'updated_by', 'draft_data_tracking_id'], 'integer'],
            [['weight', 'dimension_l', 'dimension_w', 'dimension_h'], 'number'],
            [['tracking_code', 'purchase_invoice_number', 'status'], 'string', 'max' => 255],
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
            'purchase_invoice_number' => Yii::t('db', 'Purchase Invoice Number'),
            'status' => Yii::t('db', 'Status'),
            'quantity' => Yii::t('db', 'Quantity'),
            'weight' => Yii::t('db', 'Weight'),
            'dimension_l' => Yii::t('db', 'Dimension L'),
            'dimension_w' => Yii::t('db', 'Dimension W'),
            'dimension_h' => Yii::t('db', 'Dimension H'),
            'number_run' => Yii::t('db', 'Number Run'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'created_by' => Yii::t('db', 'Created By'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'draft_data_tracking_id' => Yii::t('db', 'Draft Data Tracking ID'),
        ];
    }
}
