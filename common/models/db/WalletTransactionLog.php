<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%wallet_transaction_log}}".
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
        return '{{%wallet_transaction_log}}';
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
            'id' => Yii::t('db', 'ID'),
            'wallet_transaction_id' => Yii::t('db', 'Wallet Transaction ID'),
            'create_at' => Yii::t('db', 'Create At'),
            'update_at' => Yii::t('db', 'Update At'),
            'type' => Yii::t('db', 'Type'),
            'user_name' => Yii::t('db', 'User Name'),
            'user_action' => Yii::t('db', 'User Action'),
            'content' => Yii::t('db', 'Content'),
        ];
    }
}
