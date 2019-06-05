<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%wallet_log}}".
 *
 * @property int $id
 * @property int $walletTransactionId
 * @property string $TypeTransaction  TOPUP - nạp tiền REFUN - nạp tiền do refun WITHDRAW - rút tiền FREEZE - đóng băng UNFREEZEADD - mở đóng băng cộng UNFREEZEREDUCE - mở đóng băng trừ PAYMENT - thanh toán 
 * @property int $walletId
 * @property string $typeWallet CLIENT - ví client MERCHANT - ví merchant
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
        return '{{%wallet_log}}';
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
            'id' => Yii::t('db', 'ID'),
            'walletTransactionId' => Yii::t('db', 'Wallet Transaction ID'),
            'TypeTransaction' => Yii::t('db', 'Type Transaction'),
            'walletId' => Yii::t('db', 'Wallet ID'),
            'typeWallet' => Yii::t('db', 'Type Wallet'),
            'description' => Yii::t('db', 'Description'),
            'amount' => Yii::t('db', 'Amount'),
            'BeforeAccumulatedBalances' => Yii::t('db', 'Before Accumulated Balances'),
            'AfterAccumulatedBalances' => Yii::t('db', 'After Accumulated Balances'),
            'createDate' => Yii::t('db', 'Create Date'),
            'storeId' => Yii::t('db', 'Store ID'),
            'status' => Yii::t('db', 'Status'),
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
