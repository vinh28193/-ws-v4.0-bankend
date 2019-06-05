<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%wallet_merchant}}".
 *
 * @property int $id
 * @property string $account_number Mã ví - Ma tien to: S(danh cho master)  , nội bộ 
 * @property string $account_email Email 
 * @property string $account_token
 * @property string $account_bank_id
 * @property int $parent_account_id
 * @property string $description mo ta ve tai khoan
 * @property string $opening_balance Số dư mở ví
 * @property string $current_balance Số dư hiện tại
 * @property string $total_credit_amount Tổng số giao dịch phát sinh tăng
 * @property string $total_debit_amount Tổng số giao dịch phát sinh giảm
 * @property string $previous_current_balance Số dư kỳ trước 
 * @property string $last_amount
 * @property string $last_updated
 * @property int $last_edit_user_id
 * @property string $note
 * @property int $store_id
 * @property int $active
 * @property string $account_ref_payment_mapping Mã tài khoản ngân lượng / IdoMog mapping tùy thuộc vào StoreId
 * @property int $payment_provider_id
 *
 * @property WalletTransaction[] $walletTransactions
 */
class WalletMerchant extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wallet_merchant}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_account_id', 'last_edit_user_id', 'store_id', 'active', 'payment_provider_id'], 'integer'],
            [['opening_balance', 'current_balance', 'total_credit_amount', 'total_debit_amount', 'previous_current_balance', 'last_amount'], 'number'],
            [['last_updated'], 'safe'],
            [['account_number'], 'string', 'max' => 50],
            [['account_email', 'account_token', 'account_bank_id', 'description', 'account_ref_payment_mapping'], 'string', 'max' => 255],
            [['note'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'account_number' => Yii::t('db', 'Account Number'),
            'account_email' => Yii::t('db', 'Account Email'),
            'account_token' => Yii::t('db', 'Account Token'),
            'account_bank_id' => Yii::t('db', 'Account Bank ID'),
            'parent_account_id' => Yii::t('db', 'Parent Account ID'),
            'description' => Yii::t('db', 'Description'),
            'opening_balance' => Yii::t('db', 'Opening Balance'),
            'current_balance' => Yii::t('db', 'Current Balance'),
            'total_credit_amount' => Yii::t('db', 'Total Credit Amount'),
            'total_debit_amount' => Yii::t('db', 'Total Debit Amount'),
            'previous_current_balance' => Yii::t('db', 'Previous Current Balance'),
            'last_amount' => Yii::t('db', 'Last Amount'),
            'last_updated' => Yii::t('db', 'Last Updated'),
            'last_edit_user_id' => Yii::t('db', 'Last Edit User ID'),
            'note' => Yii::t('db', 'Note'),
            'store_id' => Yii::t('db', 'Store ID'),
            'active' => Yii::t('db', 'Active'),
            'account_ref_payment_mapping' => Yii::t('db', 'Account Ref Payment Mapping'),
            'payment_provider_id' => Yii::t('db', 'Payment Provider ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWalletTransactions()
    {
        return $this->hasMany(WalletTransaction::className(), ['wallet_merchant_id' => 'id']);
    }
}
