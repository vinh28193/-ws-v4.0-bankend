<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "system_currency".
 *
 * @property int $id ID
 * @property string $name
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $status
 *
 * @property Store[] $stores
 */
class SystemCurrency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_currency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'currency_code', 'currency_symbol', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'currency_code' => 'Currency Code',
            'currency_symbol' => 'Currency Symbol',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Store::className(), ['currency_id' => 'id']);
    }
}
