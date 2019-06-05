<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%store_additional_fee}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID reference
 * @property string $name Fee Name
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
            [['store_id', 'name', 'label'], 'required'],
            [['store_id', 'status', 'created_by', 'created_time', 'updated_by', 'updated_time'], 'integer'],
            [['description', 'condition_data', 'condition_description'], 'string'],
            [['fee_rate'], 'number'],
            [['name'], 'string', 'max' => 50],
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
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'name' => Yii::t('db', 'Name'),
            'label' => Yii::t('db', 'Label'),
            'currency' => Yii::t('db', 'Currency'),
            'description' => Yii::t('db', 'Description'),
            'condition_data' => Yii::t('db', 'Condition Data'),
            'condition_description' => Yii::t('db', 'Condition Description'),
            'status' => Yii::t('db', 'Status'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_time' => Yii::t('db', 'Created Time'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_time' => Yii::t('db', 'Updated Time'),
            'fee_rate' => Yii::t('db', 'Fee Rate'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
