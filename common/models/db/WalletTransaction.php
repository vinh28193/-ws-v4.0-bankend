<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "wallet_transaction".
 *
 * @property int $id
 * @property string $wallet_transaction_code
 * @property int $wallet_client_id
 * @property int $wallet_merchant_id
 * @property string $type TOP_UP/FREEZE/UN_FREEZE/PAY_ORDER/REFUND/WITH_DRAW
 * @property string $order_number Mã thanh toán (order, addfee)
 * @property int $status 0:Queue//1:processing//2:complete//3:cancel//4:fail
 * @property double $amount
 * @property double $credit_amount Số tiền nạp vào tài khoản khách(Topup,refund...)
 * @property double $debit_amount Số tiền khách thanh toán
 * @property string $note
 * @property string $description Mô tả giao dịch
 * @property int $verify_receive_type Kieu xac thuc 0:phone,1:email
 * @property string $verify_code OTP code
 * @property int $verify_count
 * @property int $verify_expired_at
 * @property string $verified_at Thoi gian xac thuc otp
 * @property int $refresh_count
 * @property string $refresh_expired_at
 * @property string $create_at
 * @property string $update_at
 * @property string $complete_at Thoi gian giao dich thanh cong
 * @property string $cancel_at Thoi gian giao dich bi huy
 * @property string $fail_at Thoi gian giao dich that bai
 * @property string $payment_method
 * @property string $payment_provider_name
 * @property string $payment_bank_code
 * @property string $payment_transaction
 * @property string $request_content
 * @property string $response_content
 *
 * @property WalletLog[] $walletLogs
 * @property WalletClient $walletClient
 * @property WalletMerchant $walletMerchant
 */
class WalletTransaction extends \yii\db\ActiveRecord
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
            [['wallet_transaction_code', 'wallet_client_id', 'wallet_merchant_id', 'create_at'], 'required'],
            [['wallet_client_id', 'wallet_merchant_id', 'status', 'verify_receive_type', 'verify_count', 'verify_expired_at', 'refresh_count'], 'integer'],
            [['amount', 'credit_amount', 'debit_amount'], 'number'],
            [['verified_at', 'create_at', 'update_at', 'complete_at', 'cancel_at', 'fail_at'], 'safe'],
            [['wallet_transaction_code', 'type', 'order_number', 'note', 'description', 'refresh_expired_at', 'payment_method', 'payment_provider_name', 'payment_bank_code', 'payment_transaction'], 'string', 'max' => 255],
            [['verify_code'], 'string', 'max' => 10],
            [['request_content', 'response_content'], 'string', 'max' => 1000],
            [['wallet_client_id'], 'exist', 'skipOnError' => true, 'targetClass' => WalletClient::className(), 'targetAttribute' => ['wallet_client_id' => 'id']],
            [['wallet_merchant_id'], 'exist', 'skipOnError' => true, 'targetClass' => WalletMerchant::className(), 'targetAttribute' => ['wallet_merchant_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wallet_transaction_code' => 'Wallet Transaction Code',
            'wallet_client_id' => 'Wallet Client ID',
            'wallet_merchant_id' => 'Wallet Merchant ID',
            'type' => 'Type',
            'order_number' => 'Order Number',
            'status' => 'Status',
            'amount' => 'Amount',
            'credit_amount' => 'Credit Amount',
            'debit_amount' => 'Debit Amount',
            'note' => 'Note',
            'description' => 'Description',
            'verify_receive_type' => 'Verify Receive Type',
            'verify_code' => 'Verify Code',
            'verify_count' => 'Verify Count',
            'verify_expired_at' => 'Verify Expired At',
            'verified_at' => 'Verified At',
            'refresh_count' => 'Refresh Count',
            'refresh_expired_at' => 'Refresh Expired At',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'complete_at' => 'Complete At',
            'cancel_at' => 'Cancel At',
            'fail_at' => 'Fail At',
            'payment_method' => 'Payment Method',
            'payment_provider_name' => 'Payment Provider Name',
            'payment_bank_code' => 'Payment Bank Code',
            'payment_transaction' => 'Payment Transaction',
            'request_content' => 'Request Content',
            'response_content' => 'Response Content',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletLogs()
    {
        return $this->hasMany(WalletLog::className(), ['walletTransactionId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletClient()
    {
        return $this->hasOne(WalletClient::className(), ['id' => 'wallet_client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletMerchant()
    {
        return $this->hasOne(WalletMerchant::className(), ['id' => 'wallet_merchant_id']);
    }
}
