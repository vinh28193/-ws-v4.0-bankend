<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "payment_method_provider".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property int $payment_method_id
 * @property int $payment_provider_id
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 */
class PaymentMethodProvider extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method_provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'payment_method_id', 'payment_provider_id'], 'required'],
            [['store_id', 'payment_method_id', 'payment_provider_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
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
            'payment_method_id' => 'Payment Method ID',
            'payment_provider_id' => 'Payment Provider ID',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
