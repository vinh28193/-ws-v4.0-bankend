<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_exchange_rate".
 *
 * @property int $id ID
 * @property int $store_id
 * @property string $from form currency
 * @property string $to to currency
 * @property string $rate current exchange rate
 * @property int $status Status (1:Active;2:Inactive)
 * @property string $sync_at Sync At
 */
class SystemExchangeRate extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_exchange_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'status'], 'integer'],
            [['from', 'to', 'rate'], 'required'],
            [['rate'], 'number'],
            [['sync_at'], 'safe'],
            [['from', 'to'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'from' => 'From',
            'to' => 'To',
            'rate' => 'Rate',
            'status' => 'Status',
            'sync_at' => 'Sync At',
        ];
    }
}
