<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "wallet_transaction".
 *
 * @property int $id
 * @property string $transaction_code mã giao dịch của weshop
 * @property string $created_at thời gian giao dịch
 * @property string $updated_at thời gian cập nhật giao dịch
 * @property string $transaction_status trạng thái giao dịch
 * @property string $transaction_type Loại giao dịch: top up , payment, withdraw
 * @property int $customer_id
 * @property int $order_id
 * @property string $transaction_amount_local số tiền giao dịch, có thể âm hoặc dương
 * @property string $transaction_description mô tả giao dịch
 * @property string $note ghi chú của nhân viên
 * @property string $transaction_reference_code mã tham chiếu thu tiền , vd : mã vận đơn thu cod
 * @property string $third_party_transaction_code mã giao dịch với bên thứ 3. VD: ngân lượng
 * @property string $third_party_transaction_link Link thanh toán bên thứ 3
 * @property string $third_party_transaction_status Trạng thái thanh toán của bên thứ 3
 * @property string $third_party_transaction_time thời gian giao dịch bên thứ 3
 * @property string $before_transaction_amount_local Số tiền trước giao dịch
 * @property string $after_transaction_amount_local Số tiền sau giao dịch
 *
 * @property Customer $customer
 * @property Order $order
 */
class WalletTransaction extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'customer_id', 'order_id', 'third_party_transaction_time'], 'integer'],
            [['transaction_amount_local', 'before_transaction_amount_local', 'after_transaction_amount_local'], 'number'],
            [['transaction_description', 'note', 'third_party_transaction_link'], 'string'],
            [['transaction_code', 'transaction_status', 'transaction_type', 'transaction_reference_code', 'third_party_transaction_code'], 'string', 'max' => 255],
            [['third_party_transaction_status'], 'string', 'max' => 200],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_code' => 'Transaction Code',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
            'transaction_status' => 'Transaction Status',
            'transaction_type' => 'Transaction Type',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'transaction_amount_local' => 'Transaction Amount Local',
            'transaction_description' => 'Transaction Description',
            'note' => 'Note',
            'transaction_reference_code' => 'Transaction Reference Code',
            'third_party_transaction_code' => 'Third Party Transaction Code',
            'third_party_transaction_link' => 'Third Party Transaction Link',
            'third_party_transaction_status' => 'Third Party Transaction Status',
            'third_party_transaction_time' => 'Third Party Transaction Time',
            'before_transaction_amount_local' => 'Before Transaction Amount Local',
            'after_transaction_amount_local' => 'After Transaction Amount Local',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
