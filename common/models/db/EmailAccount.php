<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "email_account".
 *
 * @property int $id
 * @property string $Email email
 * @property string $DisplayName Tên hiện thị
 * @property string $Host Tên host
 * @property int $Port Cổng 
 * @property string $Username Tên đăng nhập
 * @property string $Password Mật khẩu
 * @property int $EnableSsl Cho phép sử dụng ssl?
 * @property int $UseDefaultCredentials
 * @property int $OrganizationId
 * @property int $StoreId
 *
 * @property Store $store
 * @property QueuedEmail[] $queuedEmails
 */
class EmailAccount extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Port', 'EnableSsl', 'UseDefaultCredentials', 'OrganizationId', 'StoreId'], 'integer'],
            [['Email', 'DisplayName', 'Host', 'Username', 'Password'], 'string', 'max' => 255],
            [['StoreId'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['StoreId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Email' => 'Email',
            'DisplayName' => 'Display Name',
            'Host' => 'Host',
            'Port' => 'Port',
            'Username' => 'Username',
            'Password' => 'Password',
            'EnableSsl' => 'Enable Ssl',
            'UseDefaultCredentials' => 'Use Default Credentials',
            'OrganizationId' => 'Organization ID',
            'StoreId' => 'Store ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'StoreId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueuedEmails()
    {
        return $this->hasMany(QueuedEmail::className(), ['EmailAccountId' => 'id']);
    }
}
