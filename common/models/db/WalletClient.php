<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%wallet_client}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $email
 * @property int $customer_id
 * @property string $customer_phone
 * @property string $customer_name
 * @property double $current_balance Tổng số dư hiện tại (=freeze_balance+usable_balance)
 * @property double $freeze_balance Số tiền bị đóng băng hiện tại
 * @property double $usable_balance Số tiền có thể sử dụng để thanh toán
 * @property double $withdrawable_balance Số tiền có thể sử dụng để rút khỏi ví
 * @property double $total_refunded_amount Tổng số tiền được refund
 * @property double $total_topup_amount Tổng số tiền đã nạp
 * @property double $total_using_amount Tổng số tiền đã thanh toán đơn hàng
 * @property double $total_withdraw_amount Tổng số tiền đã rút
 * @property double $last_refund_amount số tiền được refund lần cuôi
 * @property string $last_refund_at thời gian refund lần cuối
 * @property double $last_topup_amount Số tiền nạp lần cuôi
 * @property string $last_topup_at thời gian nạp lần cuối
 * @property double $last_using_amount Số tiền giao dịch lần cuối
 * @property string $last_using_at Thời gian thực hiện giao dịch cuối cùng
 * @property double $last_withdraw_amount Số tiền rút lần cuối
 * @property string $last_withdraw_at Thời gian rút lần cuối
 * @property int $current_bulk_point Số điểm bulk hiện tại
 * @property double $current_bulk_balance Số tiền được quy đổi bulk hiện tại
 * @property string $otp_veryfy_code Mã xác thực otp hiện tại
 * @property string $otp_veryfy_expired_at Thời gian hết hạn mã otp
 * @property int $otp_veryfy_count Tổng số mã xác thực đã sử dụng
 * @property int $store_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $identity_number
 * @property string $identity_issued_date
 * @property string $identity_issued_by
 * @property string $identity_image_url_before
 * @property int $identity_verified
 * @property string $identity_image_url_after
 * @property int $status 0:inactive;1active;2:freeze
 *
 * @property WalletTransaction[] $walletTransactions
 */
class WalletClient extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wallet_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key', 'email', 'customer_id', 'customer_phone'], 'required'],
            [['customer_id', 'current_bulk_point', 'otp_veryfy_count', 'store_id', 'identity_verified', 'status'], 'integer'],
            [['current_balance', 'freeze_balance', 'usable_balance', 'withdrawable_balance', 'total_refunded_amount', 'total_topup_amount', 'total_using_amount', 'total_withdraw_amount', 'last_refund_amount', 'last_topup_amount', 'last_using_amount', 'last_withdraw_amount', 'current_bulk_balance'], 'number'],
            [['last_refund_at', 'last_topup_at', 'last_using_at', 'last_withdraw_at', 'otp_veryfy_expired_at', 'created_at', 'updated_at', 'identity_issued_date'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'auth_key', 'email', 'customer_phone', 'customer_name', 'identity_issued_by', 'identity_image_url_before', 'identity_image_url_after'], 'string', 'max' => 255],
            [['otp_veryfy_code'], 'string', 'max' => 10],
            [['identity_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'username' => Yii::t('db', 'Username'),
            'password_hash' => Yii::t('db', 'Password Hash'),
            'password_reset_token' => Yii::t('db', 'Password Reset Token'),
            'auth_key' => Yii::t('db', 'Auth Key'),
            'email' => Yii::t('db', 'Email'),
            'customer_id' => Yii::t('db', 'Customer ID'),
            'customer_phone' => Yii::t('db', 'Customer Phone'),
            'customer_name' => Yii::t('db', 'Customer Name'),
            'current_balance' => Yii::t('db', 'Current Balance'),
            'freeze_balance' => Yii::t('db', 'Freeze Balance'),
            'usable_balance' => Yii::t('db', 'Usable Balance'),
            'withdrawable_balance' => Yii::t('db', 'Withdrawable Balance'),
            'total_refunded_amount' => Yii::t('db', 'Total Refunded Amount'),
            'total_topup_amount' => Yii::t('db', 'Total Topup Amount'),
            'total_using_amount' => Yii::t('db', 'Total Using Amount'),
            'total_withdraw_amount' => Yii::t('db', 'Total Withdraw Amount'),
            'last_refund_amount' => Yii::t('db', 'Last Refund Amount'),
            'last_refund_at' => Yii::t('db', 'Last Refund At'),
            'last_topup_amount' => Yii::t('db', 'Last Topup Amount'),
            'last_topup_at' => Yii::t('db', 'Last Topup At'),
            'last_using_amount' => Yii::t('db', 'Last Using Amount'),
            'last_using_at' => Yii::t('db', 'Last Using At'),
            'last_withdraw_amount' => Yii::t('db', 'Last Withdraw Amount'),
            'last_withdraw_at' => Yii::t('db', 'Last Withdraw At'),
            'current_bulk_point' => Yii::t('db', 'Current Bulk Point'),
            'current_bulk_balance' => Yii::t('db', 'Current Bulk Balance'),
            'otp_veryfy_code' => Yii::t('db', 'Otp Veryfy Code'),
            'otp_veryfy_expired_at' => Yii::t('db', 'Otp Veryfy Expired At'),
            'otp_veryfy_count' => Yii::t('db', 'Otp Veryfy Count'),
            'store_id' => Yii::t('db', 'Store ID'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_at' => Yii::t('db', 'Updated At'),
            'identity_number' => Yii::t('db', 'Identity Number'),
            'identity_issued_date' => Yii::t('db', 'Identity Issued Date'),
            'identity_issued_by' => Yii::t('db', 'Identity Issued By'),
            'identity_image_url_before' => Yii::t('db', 'Identity Image Url Before'),
            'identity_verified' => Yii::t('db', 'Identity Verified'),
            'identity_image_url_after' => Yii::t('db', 'Identity Image Url After'),
            'status' => Yii::t('db', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactions()
    {
        return $this->hasMany(WalletTransaction::className(), ['wallet_client_id' => 'id']);
    }
}
