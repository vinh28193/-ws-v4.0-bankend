<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%queued_email}}".
 *
 * @property int $id
 * @property int $Priority
 * @property string $From
 * @property string $FromName
 * @property string $To
 * @property string $ToName
 * @property string $CC
 * @property string $Bcc
 * @property string $Subject
 * @property string $Body
 * @property string $CreatedTime
 * @property int $SentTries
 * @property string $SentOn
 * @property int $EmailAccountId
 * @property int $CampaignId
 * @property int $TemplateId
 * @property string $RecipientId
 * @property int $Opened
 * @property string $Openedon
 * @property string $Status
 * @property int $OrderId
 * @property string $api_id
 * @property int $Bounce
 * @property int $Clicked
 * @property int $Sent
 * @property int $Using Flag select email send
 * @property string $OrderType loai order
 * @property int $StatusV3
 *
 * @property EmailAccount $emailAccount
 * @property Order $order
 */
class QueuedEmail extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%queued_email}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Priority', 'SentTries', 'EmailAccountId', 'CampaignId', 'TemplateId', 'Opened', 'OrderId', 'Bounce', 'Clicked', 'Sent', 'Using', 'StatusV3'], 'integer'],
            [['Body'], 'string'],
            [['CreatedTime', 'SentOn', 'Openedon'], 'safe'],
            [['From', 'FromName', 'To', 'ToName', 'CC', 'Bcc'], 'string', 'max' => 500],
            [['Subject'], 'string', 'max' => 1000],
            [['RecipientId'], 'string', 'max' => 50],
            [['Status'], 'string', 'max' => 30],
            [['api_id'], 'string', 'max' => 200],
            [['OrderType'], 'string', 'max' => 10],
            [['EmailAccountId'], 'exist', 'skipOnError' => true, 'targetClass' => EmailAccount::className(), 'targetAttribute' => ['EmailAccountId' => 'id']],
            [['OrderId'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['OrderId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'Priority' => Yii::t('db', 'Priority'),
            'From' => Yii::t('db', 'From'),
            'FromName' => Yii::t('db', 'From Name'),
            'To' => Yii::t('db', 'To'),
            'ToName' => Yii::t('db', 'To Name'),
            'CC' => Yii::t('db', 'Cc'),
            'Bcc' => Yii::t('db', 'Bcc'),
            'Subject' => Yii::t('db', 'Subject'),
            'Body' => Yii::t('db', 'Body'),
            'CreatedTime' => Yii::t('db', 'Created Time'),
            'SentTries' => Yii::t('db', 'Sent Tries'),
            'SentOn' => Yii::t('db', 'Sent On'),
            'EmailAccountId' => Yii::t('db', 'Email Account ID'),
            'CampaignId' => Yii::t('db', 'Campaign ID'),
            'TemplateId' => Yii::t('db', 'Template ID'),
            'RecipientId' => Yii::t('db', 'Recipient ID'),
            'Opened' => Yii::t('db', 'Opened'),
            'Openedon' => Yii::t('db', 'Openedon'),
            'Status' => Yii::t('db', 'Status'),
            'OrderId' => Yii::t('db', 'Order ID'),
            'api_id' => Yii::t('db', 'Api ID'),
            'Bounce' => Yii::t('db', 'Bounce'),
            'Clicked' => Yii::t('db', 'Clicked'),
            'Sent' => Yii::t('db', 'Sent'),
            'Using' => Yii::t('db', 'Using'),
            'OrderType' => Yii::t('db', 'Order Type'),
            'StatusV3' => Yii::t('db', 'Status V3'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmailAccount()
    {
        return $this->hasOne(EmailAccount::className(), ['id' => 'EmailAccountId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'OrderId']);
    }
}
