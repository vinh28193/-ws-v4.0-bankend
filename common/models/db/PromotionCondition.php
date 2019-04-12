<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "promotion_condition".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $promotion_id Promotion ID
 * @property string $name name of condition
 * @property resource $value mixed value
 * @property int $created_by Created by
 * @property int $created_at Created at (timestamp)
 * @property int $updated_by Updated by
 * @property int $updated_at Updated at (timestamp)
 */
class PromotionCondition extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_condition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'value'], 'required'],
            [['store_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['promotion_id'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 80],
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
            'promotion_id' => 'Promotion ID',
            'name' => 'Name',
            'value' => 'Value',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
