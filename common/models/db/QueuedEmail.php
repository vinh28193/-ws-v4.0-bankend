<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "queued_email".
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
        return 'queued_email';
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
            'id' => 'ID',
            'Priority' => 'Priority',
            'From' => 'From',
            'FromName' => 'From Name',
            'To' => 'To',
            'ToName' => 'To Name',
            'CC' => 'Cc',
            'Bcc' => 'Bcc',
            'Subject' => 'Subject',
            'Body' => 'Body',
            'CreatedTime' => 'Created Time',
            'SentTries' => 'Sent Tries',
            'SentOn' => 'Sent On',
            'EmailAccountId' => 'Email Account ID',
            'CampaignId' => 'Campaign ID',
            'TemplateId' => 'Template ID',
            'RecipientId' => 'Recipient ID',
            'Opened' => 'Opened',
            'Openedon' => 'Openedon',
            'Status' => 'Status',
            'OrderId' => 'Order ID',
            'api_id' => 'Api ID',
            'Bounce' => 'Bounce',
            'Clicked' => 'Clicked',
            'Sent' => 'Sent',
            'Using' => 'Using',
            'OrderType' => 'Order Type',
            'StatusV3' => 'Status V3',
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
