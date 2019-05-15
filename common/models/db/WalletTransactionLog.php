<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "wallet_transaction_log".
 *
 * @property int $id
 * @property int $wallet_transaction_id
 * @property string $create_at
 * @property string $update_at
 * @property string $type
 * @property string $user_name
 * @property string $user_action
 * @property string $content
 */
class WalletTransactionLog extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wallet_transaction_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wallet_transaction_id'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['type', 'user_name', 'user_action'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'wallet_transaction_id' => 'Wallet Transaction ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'type' => 'Type',
            'user_name' => 'User Name',
            'user_action' => 'User Action',
            'content' => 'Content',
        ];
    }
}
