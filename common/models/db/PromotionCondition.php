<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%promotion_condition}}".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $promotion_id Promotion ID
 * @property string $name name of condition
 * @property string $value mixed value
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
        return '{{%promotion_condition}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'promotion_id', 'name'], 'required'],
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
            'id' => Yii::t('db', 'ID'),
            'store_id' => Yii::t('db', 'Store ID'),
            'promotion_id' => Yii::t('db', 'Promotion ID'),
            'name' => Yii::t('db', 'Name'),
            'value' => Yii::t('db', 'Value'),
            'created_by' => Yii::t('db', 'Created By'),
            'created_at' => Yii::t('db', 'Created At'),
            'updated_by' => Yii::t('db', 'Updated By'),
            'updated_at' => Yii::t('db', 'Updated At'),
        ];
    }
}
