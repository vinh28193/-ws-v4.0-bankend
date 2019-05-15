<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "wallet_log".
 *
 * @property int $id
 * @property int $walletTransactionId
 * @property string $TypeTransaction  TOPUP - nạp tiền REFUN - nạp tiền do refun WITHDRAW - rút tiền FREEZE - đóng băng UNFREEZEADD - mở đóng băng cộng UNFREEZEREDUCE - mở đóng băng trừ PAYMENT - thanh toán 
 * @property int $walletId
 * @property string $typeWallet CLIENT - ví client MERCHANT - ví merchant
 * @property string $description
 * @property string $amount số tiền giao dịch
 * @property string $BeforeAccumulatedBalances số dư trước khi giao dịch - theo field current_balance 
 * @property string $AfterAccumulatedBalances số dư sau khi giao dịch - theo field current_balance
 * @property string $createDate
 * @property int $storeId
 * @property int $status 0- Pending ; 1 - Success; 2 - Fail
 *
 * @property WalletTransaction $walletTransaction
 */
class WalletLog extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['walletTransactionId', 'walletId', 'description', 'createDate'], 'required'],
            [['walletTransactionId', 'walletId', 'storeId', 'status'], 'integer'],
            [['description'], 'string'],
            [['amount', 'BeforeAccumulatedBalances', 'AfterAccumulatedBalances'], 'number'],
            [['createDate'], 'safe'],
            [['TypeTransaction'], 'string', 'max' => 50],
            [['typeWallet'], 'string', 'max' => 255],
            [['walletTransactionId'], 'exist', 'skipOnError' => true, 'targetClass' => WalletTransaction::className(), 'targetAttribute' => ['walletTransactionId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'walletTransactionId' => 'Wallet Transaction ID',
            'TypeTransaction' => 'Type Transaction',
            'walletId' => 'Wallet ID',
            'typeWallet' => 'Type Wallet',
            'description' => 'Description',
            'amount' => 'Amount',
            'BeforeAccumulatedBalances' => 'Before Accumulated Balances',
            'AfterAccumulatedBalances' => 'After Accumulated Balances',
            'createDate' => 'Create Date',
            'storeId' => 'Store ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransaction()
    {
        return $this->hasOne(WalletTransaction::className(), ['id' => 'walletTransactionId']);
    }
}
