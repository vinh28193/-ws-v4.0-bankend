<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "notify_template".
 *
 * @property int $id
 * @property string $type
 * @property string $receive  0 : phone | 1: email
 * @property int $store
 * @property string $from_name
 * @property string $from_address
 * @property string $to_name
 * @property string $to_address
 * @property string $subject
 * @property string $html_content
 * @property string $text_content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class NotifyTemplate extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notify_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store', 'status', 'created_at', 'updated_at'], 'integer'],
            [['subject', 'html_content'], 'string'],
            [['type', 'receive', 'from_name', 'from_address', 'to_name', 'to_address', 'text_content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'receive' => 'Receive',
            'store' => 'Store',
            'from_name' => 'From Name',
            'from_address' => 'From Address',
            'to_name' => 'To Name',
            'to_address' => 'To Address',
            'subject' => 'Subject',
            'html_content' => 'Html Content',
            'text_content' => 'Text Content',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
