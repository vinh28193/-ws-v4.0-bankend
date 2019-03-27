<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "list_account_purchase".
 *
 * @property int $id
 * @property string $account
 * @property string $email
 * @property string $type
 * @property int $active
 * @property int $updated_at
 * @property int $created_at
 */
class ListAccountPurchase extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'list_account_purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account', 'email', 'type'], 'required'],
            [['active', 'updated_at', 'created_at'], 'integer'],
            [['account', 'email', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => 'Account',
            'email' => 'Email',
            'type' => 'Type',
            'active' => 'Active',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
