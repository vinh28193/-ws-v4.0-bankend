<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "store_additional_fee".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property string $name Fee Name
 * @property string $currency Currency (USD/VND)
 * @property string $description Description
 * @property string $condition_name Fee Name
 * @property resource $condition_data Fee Data
 * @property string $condition_description Fee Rules Description
 * @property int $is_convert Is Convert (1:Can Convert;2:Can Not)
 * @property int $is_read_only Is Read Only
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_time Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_time Updated at (timestamp)
 * @property string $fee_rate Fee Rate
 */
class StoreAdditionalFee extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_additional_fee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'name'], 'required'],
            [['store_id', 'is_convert', 'is_read_only', 'status', 'created_by', 'created_time', 'updated_by', 'updated_time'], 'integer'],
            [['description', 'condition_data', 'condition_description'], 'string'],
            [['fee_rate'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 11],
            [['condition_name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'currency' => 'Currency',
            'description' => 'Description',
            'condition_name' => 'Condition Name',
            'condition_data' => 'Condition Data',
            'condition_description' => 'Condition Description',
            'is_convert' => 'Is Convert',
            'is_read_only' => 'Is Read Only',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'fee_rate' => 'Fee Rate',
        ];
    }
}
