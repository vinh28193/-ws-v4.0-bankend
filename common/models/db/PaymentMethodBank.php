<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%payment_method_bank}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property int $payment_method_id
 * @property int $payment_bank_id
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 *
 * @property PaymentBank $paymentBank
 * @property PaymentMethod $paymentMethod
 */
class PaymentMethodBank extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_method_bank}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'payment_method_id', 'payment_bank_id'], 'required'],
            [['store_id', 'payment_method_id', 'payment_bank_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['payment_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentBank::className(), 'targetAttribute' => ['payment_bank_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'payment_method_id' => Yii::t('db', 'Payment Method ID'),
            'payment_bank_id' => Yii::t('db', 'Payment Bank ID'),
            'status' => Yii::t('db', 'Status'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_at' => Yii::t('db', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentBank()
    {
        return $this->hasOne(PaymentBank::className(), ['id' => 'payment_bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }
}
