<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%wallet_transaction}}".
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
class WalletTransaction extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wallet_transaction}}';
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
            'id' => Yii::t('db', 'ID'),
            'wallet_transaction_code' => Yii::t('db', 'Wallet Transaction Code'),
            'wallet_client_id' => Yii::t('db', 'Wallet Client ID'),
            'wallet_merchant_id' => Yii::t('db', 'Wallet Merchant ID'),
            'type' => Yii::t('db', 'Type'),
            'order_number' => Yii::t('db', 'Order Number'),
            'status' => Yii::t('db', 'Status'),
            'amount' => Yii::t('db', 'Amount'),
            'credit_amount' => Yii::t('db', 'Credit Amount'),
            'debit_amount' => Yii::t('db', 'Debit Amount'),
            'note' => Yii::t('db', 'Note'),
            'description' => Yii::t('db', 'Description'),
            'verify_receive_type' => Yii::t('db', 'Verify Receive Type'),
            'verify_code' => Yii::t('db', 'Verify Code'),
            'verify_count' => Yii::t('db', 'Verify Count'),
            'verify_expired_at' => Yii::t('db', 'Verify Expired At'),
            'verified_at' => Yii::t('db', 'Verified At'),
            'refresh_count' => Yii::t('db', 'Refresh Count'),
            'refresh_expired_at' => Yii::t('db', 'Refresh Expired At'),
            'create_at' => Yii::t('db', 'Create At'),
            'update_at' => Yii::t('db', 'Update At'),
            'complete_at' => Yii::t('db', 'Complete At'),
            'cancel_at' => Yii::t('db', 'Cancel At'),
            'fail_at' => Yii::t('db', 'Fail At'),
            'payment_method' => Yii::t('db', 'Payment Method'),
            'payment_provider_name' => Yii::t('db', 'Payment Provider Name'),
            'payment_bank_code' => Yii::t('db', 'Payment Bank Code'),
            'payment_transaction' => Yii::t('db', 'Payment Transaction'),
            'request_content' => Yii::t('db', 'Request Content'),
            'response_content' => Yii::t('db', 'Response Content'),
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
