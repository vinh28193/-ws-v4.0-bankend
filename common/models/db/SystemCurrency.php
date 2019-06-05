<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "{{%system_currency}}".
 *
 * @property int $id ID
 * @property string $name
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $status
 * @property string $version version 4.0
 */
class SystemCurrency extends \common\components\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%system_currency}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'currency_code', 'currency_symbol', 'status', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('db', 'ID'),
            'name' => Yii::t('db', 'Name'),
            'currency_code' => Yii::t('db', 'Currency Code'),
            'currency_symbol' => Yii::t('db', 'Currency Symbol'),
            'status' => Yii::t('db', 'Status'),
            'version' => Yii::t('db', 'Version'),
        ];
    }
}
