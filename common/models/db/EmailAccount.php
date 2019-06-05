<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%email_account}}".
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
 * @property QueuedEmail[] $queuedEmails
 */
class EmailAccount extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Port', 'EnableSsl', 'UseDefaultCredentials', 'OrganizationId', 'StoreId'], 'integer'],
            [['Email', 'DisplayName', 'Host', 'Username', 'Password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'Email' => Yii::t('db', 'Email'),
            'DisplayName' => Yii::t('db', 'Display Name'),
            'Host' => Yii::t('db', 'Host'),
            'Port' => Yii::t('db', 'Port'),
            'Username' => Yii::t('db', 'Username'),
            'Password' => Yii::t('db', 'Password'),
            'EnableSsl' => Yii::t('db', 'Enable Ssl'),
            'UseDefaultCredentials' => Yii::t('db', 'Use Default Credentials'),
            'OrganizationId' => Yii::t('db', 'Organization ID'),
            'StoreId' => Yii::t('db', 'Store ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQueuedEmails()
    {
        return $this->hasMany(QueuedEmail::className(), ['EmailAccountId' => 'id']);
    }
}
