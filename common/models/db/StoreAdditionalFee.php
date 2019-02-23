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
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 */
class StoreAdditionalFee extends \yii\db\ActiveRecord
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
            [['store_id', 'is_convert', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['description', 'condition_data', 'condition_description'], 'string'],
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
