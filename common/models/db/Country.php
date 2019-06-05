<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%country}}".
 *
 * @property string $code
 * @property string $name
 * @property string $version version 4.0
 */
class Country extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['name', 'version'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('db', 'Code'),
            'name' => Yii::t('db', 'Name'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
