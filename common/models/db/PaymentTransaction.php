<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "payment_transaction".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property int $customer_id customer id
 * @property string $transaction_code mã giao dịch của weshop
 * @property string $transaction_type Loại giao dịch: top up , payment, withdraw
 * @property string $transaction_status trạng thái giao dịch
 * @property string $transaction_customer_name
 * @property string $transaction_customer_email
 * @property string $transaction_customer_phone
 * @property string $transaction_customer_address
 * @property string $transaction_customer_city
 * @property string $transaction_customer_postcode
 * @property string $transaction_customer_district
 * @property string $transaction_customer_country
 * @property string $payment_provider
 * @property string $payment_method
 * @property string $payment_bank_code
 * @property string $coupon_code
 * @property int $used_xu
 * @property int $bulk_point
 * @property string $orders list cart id
 * @property int $shipping Dia chi ship
 * @property string $total_discount_amount
 * @property string $before_discount_amount_local
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
 * @property int $created_at Created at (timestamp)
 */
class PaymentTransaction extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'payment_provider', 'payment_method', 'payment_bank_code', 'coupon_code', 'orders', 'shipping'], 'required'],
            [['store_id', 'customer_id', 'used_xu', 'bulk_point', 'shipping', 'third_party_transaction_time', 'created_at'], 'integer'],
            [['orders', 'transaction_description', 'note', 'third_party_transaction_link'], 'string'],
            [['total_discount_amount', 'before_discount_amount_local', 'transaction_amount_local', 'before_transaction_amount_local', 'after_transaction_amount_local'], 'number'],
            [['transaction_code', 'payment_bank_code', 'coupon_code'], 'string', 'max' => 32],
            [['transaction_type', 'transaction_status'], 'string', 'max' => 10],
            [['transaction_customer_name', 'transaction_customer_email', 'transaction_customer_phone', 'transaction_customer_address', 'transaction_customer_city', 'transaction_customer_postcode', 'transaction_customer_district', 'transaction_customer_country', 'transaction_reference_code', 'third_party_transaction_code'], 'string', 'max' => 255],
            [['payment_provider', 'payment_method'], 'string', 'max' => 50],
            [['third_party_transaction_status'], 'string', 'max' => 200],
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
            'customer_id' => 'Customer ID',
            'transaction_code' => 'Transaction Code',
            'transaction_type' => 'Transaction Type',
            'transaction_status' => 'Transaction Status',
            'transaction_customer_name' => 'Transaction Customer Name',
            'transaction_customer_email' => 'Transaction Customer Email',
            'transaction_customer_phone' => 'Transaction Customer Phone',
            'transaction_customer_address' => 'Transaction Customer Address',
            'transaction_customer_city' => 'Transaction Customer City',
            'transaction_customer_postcode' => 'Transaction Customer Postcode',
            'transaction_customer_district' => 'Transaction Customer District',
            'transaction_customer_country' => 'Transaction Customer Country',
            'payment_provider' => 'Payment Provider',
            'payment_method' => 'Payment Method',
            'payment_bank_code' => 'Payment Bank Code',
            'coupon_code' => 'Coupon Code',
            'used_xu' => 'Used Xu',
            'bulk_point' => 'Bulk Point',
            'orders' => 'Orders',
            'shipping' => 'Shipping',
            'total_discount_amount' => 'Total Discount Amount',
            'before_discount_amount_local' => 'Before Discount Amount Local',
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
            'created_at' => 'Created At',
        ];
    }
}
