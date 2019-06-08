<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%store_additional_fee}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property string $name Fee Name
 * @property string $type origin/addition/discount
 * @property string $label Label of fee
 * @property string $currency Currency (USD/VND)
 * @property string $description Description
 * @property resource $condition_data Fee Data
 * @property string $condition_description Fee Rules Description
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_time Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_time Updated at (timestamp)
 * @property string $fee_rate Fee Rate
 * @property string $version version 4.0
 */
class StoreAdditionalFee extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%store_additional_fee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'type', 'label'], 'required'],
            [['store_id', 'status', 'created_by', 'created_time', 'updated_by', 'updated_time'], 'integer'],
            [['description', 'condition_data', 'condition_description'], 'string'],
            [['fee_rate'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 32],
            [['label'], 'string', 'max' => 80],
            [['currency'], 'string', 'max' => 11],
            [['version'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'label' => 'Label',
            'currency' => 'Currency',
            'description' => 'Description',
            'condition_data' => 'Condition Data',
            'condition_description' => 'Condition Description',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'updated_by' => 'Updated By',
            'updated_time' => 'Updated Time',
            'fee_rate' => 'Fee Rate',
            'version' => 'Version',
        ];
    }
}
