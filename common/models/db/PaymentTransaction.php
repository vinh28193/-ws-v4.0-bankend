<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%payment_transaction}}".
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
 * @property string $payment_type
 * @property string $payment_provider
 * @property string $payment_method
 * @property string $payment_bank_code
 * @property string $coupon_code
 * @property int $used_xu
 * @property int $bulk_point
 * @property string $carts list cart
 * @property int $shipping
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
 * @property string $topup_transaction_code
 * @property string $parent_transaction_code
 * @property string $order_code
 * @property string $courier_name
 * @property string $service_code
 * @property int $international_shipping_fee
 * @property int $insurance_fee
 */
class PaymentTransaction extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'payment_type', 'payment_provider', 'payment_method', 'carts', 'courier_name', 'service_code', 'international_shipping_fee'], 'required'],
            [['store_id', 'customer_id', 'used_xu', 'bulk_point', 'shipping', 'third_party_transaction_time', 'created_at', 'international_shipping_fee', 'insurance_fee'], 'integer'],
            [['carts', 'transaction_description', 'note', 'third_party_transaction_link'], 'string'],
            [['total_discount_amount', 'before_discount_amount_local', 'transaction_amount_local', 'before_transaction_amount_local', 'after_transaction_amount_local'], 'number'],
            [['transaction_code', 'payment_bank_code', 'coupon_code', 'topup_transaction_code', 'parent_transaction_code', 'order_code', 'service_code'], 'string', 'max' => 32],
            [['transaction_type', 'transaction_status', 'payment_type'], 'string', 'max' => 10],
            [['transaction_customer_name', 'transaction_customer_email', 'transaction_customer_phone', 'transaction_customer_address', 'transaction_customer_city', 'transaction_customer_postcode', 'transaction_customer_district', 'transaction_customer_country', 'transaction_reference_code', 'third_party_transaction_code', 'courier_name'], 'string', 'max' => 255],
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
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'transaction_code' => Yii::t('db', 'Transaction Code'),
            'transaction_type' => Yii::t('db', 'Transaction Type'),
            'transaction_status' => Yii::t('db', 'Transaction Status'),
            'transaction_customer_name' => Yii::t('db', 'Transaction Customer Name'),
            'transaction_customer_email' => Yii::t('db', 'Transaction Customer Email'),
            'transaction_customer_phone' => Yii::t('db', 'Transaction Customer Phone'),
            'transaction_customer_address' => Yii::t('db', 'Transaction Customer Address'),
            'transaction_customer_city' => Yii::t('db', 'Transaction Customer City'),
            'transaction_customer_postcode' => Yii::t('db', 'Transaction Customer Postcode'),
            'transaction_customer_district' => Yii::t('db', 'Transaction Customer District'),
            'transaction_customer_country' => Yii::t('db', 'Transaction Customer Country'),
            'payment_type' => Yii::t('db', 'Payment Type'),
            'payment_provider' => Yii::t('db', 'Payment Provider'),
            'payment_method' => Yii::t('db', 'Payment Method'),
            'payment_bank_code' => Yii::t('db', 'Payment Bank Code'),
            'coupon_code' => Yii::t('db', 'Coupon Code'),
            'used_xu' => Yii::t('db', 'Used Xu'),
            'bulk_point' => Yii::t('db', 'Bulk Point'),
            'carts' => Yii::t('db', 'Carts'),
            'shipping' => Yii::t('db', 'Shipping'),
            'total_discount_amount' => Yii::t('db', 'Total Discount Amount'),
            'before_discount_amount_local' => Yii::t('db', 'Before Discount Amount Local'),
            'transaction_amount_local' => Yii::t('db', 'Transaction Amount Local'),
            'transaction_description' => Yii::t('db', 'Transaction Description'),
            'note' => Yii::t('db', 'Note'),
            'transaction_reference_code' => Yii::t('db', 'Transaction Reference Code'),
            'third_party_transaction_code' => Yii::t('db', 'Third Party Transaction Code'),
            'third_party_transaction_link' => Yii::t('db', 'Third Party Transaction Link'),
            'third_party_transaction_status' => Yii::t('db', 'Third Party Transaction Status'),
            'third_party_transaction_time' => Yii::t('db', 'Third Party Transaction Time'),
            'before_transaction_amount_local' => Yii::t('db', 'Before Transaction Amount Local'),
            'after_transaction_amount_local' => Yii::t('db', 'After Transaction Amount Local'),
            'created_at' => Yii::t('db', 'Created At'),
            'topup_transaction_code' => Yii::t('db', 'Topup Transaction Code'),
            'parent_transaction_code' => Yii::t('db', 'Parent Transaction Code'),
            'order_code' => Yii::t('db', 'Order Code'),
            'courier_name' => Yii::t('db', 'Courier Name'),
            'service_code' => Yii::t('db', 'Service Code'),
            'international_shipping_fee' => Yii::t('db', 'International Shipping Fee'),
            'insurance_fee' => Yii::t('db', 'Insurance Fee'),
        ];
    }
}
