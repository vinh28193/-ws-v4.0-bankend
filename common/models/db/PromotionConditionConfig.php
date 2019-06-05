<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%promotion_condition_config}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $name name of condition
 * @property string $operator Operator of condition
 * @property string $type_cast php type cast (integer,string,float ..etc)
 * @property string $description description
 * @property int $status Status (1:Active;2:Inactive)
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 */
class PromotionConditionConfig extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%promotion_condition_config}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'operator'], 'required'],
            [['store_id', 'status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 80],
            [['operator', 'type_cast'], 'string', 'max' => 10],
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
            'operator' => Yii::t('db', 'Operator'),
            'type_cast' => Yii::t('db', 'Type Cast'),
            'description' => Yii::t('db', 'Description'),
            'status' => Yii::t('db', 'Status'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_at' => Yii::t('db', 'Updated At'),
        ];
    }
}
